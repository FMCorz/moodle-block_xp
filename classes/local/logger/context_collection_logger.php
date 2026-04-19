<?php
// This file is part of Level Up XP.
//
// Level Up XP is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Level Up XP is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Level Up XP.  If not, see <https://www.gnu.org/licenses/>.
//
// https://levelup.plus

/**
 * Collection logger.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\logger;

use block_xp\di;
use block_xp\local\activity\user_recent_activity_repository;
use block_xp\local\activity\xp_activity;
use block_xp\local\factory\reason_from_log_entry_factory;
use block_xp\local\reason\reason;
use block_xp\local\reason\reason_with_rule;
use block_xp\local\reason\reason_with_short_description;
use block_xp\local\reason\reason_with_subtype;
use block_xp\local\reason\reason_with_tracking;
use block_xp\local\reason\resolver;
use block_xp\local\ruletype\limit_spec;
use block_xp\local\ruletype\ruletype;
use block_xp\local\ruletype\resolver as ruletype_resolver;
use block_xp\local\utils\reason_utils;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use moodle_database;

/**
 * Collection logger.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class context_collection_logger implements
    collection_logger,
    collection_logger_with_group_reset,
    collection_logger_with_id_reset,
    reason_collection_logger,
    reason_limit_indicator,
    reason_occurrence_indicator,
    ruletype_occurrence_indicator,
    user_recent_activity_repository {

    /** @var int The context ID. */
    protected $contextid;
    /** @var moodle_database The DB. */
    protected $db;
    /** @var reason_from_log_entry_factory The reason factory. */
    protected $reasonfactory;
    /** @var resolver The reason resolver. */
    protected $reasonresolver;
    /** @var ruletype_resolver|null The rule type reason resolver. */
    protected $ruletyperesolver;
    /** @var string */
    protected $table = 'block_xp_logs';

    /**
     * Constructor.
     *
     * @param moodle_database $db The DB.
     * @param int $contextid The context ID.
     */
    public function __construct(moodle_database $db, int $contextid) {
        $this->db = $db;
        $this->contextid = $contextid;
    }

    /**
     * Delete logs older than a certain date.
     *
     * @param \DateTime $dt The date.
     * @return void
     */
    public function delete_older_than(DateTime $dt) {
        $this->db->delete_records_select(
            $this->table,
            'contextid = :contextid AND timerecorded < :time',
            [
                'contextid' => $this->contextid,
                'time' => $dt->getTimestamp(),
            ]
        );
    }

    /**
     * Get the limit time window filter SQL.
     *
     * @param int $timewindow The time window.
     * @return array With SQL, and params.
     */
    protected function get_limit_time_window_filter_sql(int $timewindow): array {
        if ($timewindow === limit_spec::WINDOW_NONE) {
            return ['1=1', []];
        }

        if ($timewindow === limit_spec::WINDOW_1H) {
            $since = di::get('clock')->now()->sub(new DateInterval('PT1H'));
            return ['(timerecorded >= :limittimewindow)', ['limittimewindow' => $since->getTimestamp()]];
        } else if ($timewindow === limit_spec::WINDOW_HOURLY) {
            $now = di::get('clock')->now();
            $since = $now->setTime((int) $now->format('H'), 0, 0, 0);
            return ['(timerecorded >= :limittimewindow)', ['limittimewindow' => $since->getTimestamp()]];
        }

        return ['1=1', []];
    }

    /**
     * Get the reason filter SQL.
     *
     * @param reason $reason
     * @param int|null $scope The scope, or null for full scope.
     * @return array With SQL, and params.
     */
    protected function get_reason_filter_sql(reason $reason, ?int $scope = null): array {
        $conditions = ['reasontypehash = :reasontypehash'];
        $params = ['reasontypehash' => $this->get_reason_type_hash($reason)];
        $scope ??= limit_spec::SCOPE_ENV | limit_spec::SCOPE_PARENT | limit_spec::SCOPE_OBJECT;

        if ($reason instanceof reason_with_tracking) {
            $fields = [];
            if ($scope & limit_spec::SCOPE_ENV) {
                $fields['envid'] = $reason->get_env_id();
            }
            if ($scope & limit_spec::SCOPE_PARENT) {
                $fields['parentid'] = $reason->get_parent_id();
            }
            if ($scope & limit_spec::SCOPE_OBJECT) {
                $fields['objectid'] = $reason->get_object_id();
            }
            foreach ($fields as $field => $value) {
                if ($value === null) {
                    continue;
                }
                $conditions[] = "$field = :reason$field";
                $params["reason$field"] = $value;
            }
        }

        return ['(' . implode(' AND ', $conditions) . ')', $params];
    }

    /**
     * Get the reason's hash.
     *
     * The hash is short and there can be collisions on 9 characters (36 bits), however as most queries
     * would include additional fields, it would be quite unfortunate that our little variety of reasons
     * creates collisions that would negatively impact the logger. We can improve the logger further at
     * a later stage if needed.
     *
     * @param reason $reason The reason.
     * @return string
     */
    protected function get_reason_type_hash(reason $reason): string {
        $name = $this->reasonresolver->get_name($reason);
        $subtype = $reason instanceof reason_with_subtype ? $reason->get_subtype() : null;
        return substr(sha1($name . ':' . ($subtype ?? '')), 0, 9);
    }

    /**
     * Has the reason ever happened.
     *
     * @param int $id The ID.
     * @param reason $reason The reason.
     * @param DateTime $since The date. This is using DateTime for historical reasons.
     * @return bool
     */
    public function has_reason_happened_since($userid, reason $reason, DateTime $since) {
        $conditions = [
            'contextid = :contextid',
            'userid = :userid',
            'timerecorded >= :timerecorded',
        ];
        $params = [
            'contextid' => $this->contextid,
            'userid' => $userid,
            'timerecorded' => $since->getTimestamp(),
        ];

        [$reasonsql, $reasonparams] = $this->get_reason_filter_sql($reason);
        $conditions[] = $reasonsql;
        $params = array_merge($params, $reasonparams);

        return $this->db->record_exists_select($this->table, implode(' AND ', $conditions), $params);
    }

    /**
     * Has the rule type happened since.
     *
     * @param int $id The ID.
     * @param ruletype $ruletype The rule type.
     * @param DateTimeImmutable $since The date.
     * @param int $maxcount The number of times to require to return true.
     * @return bool
     */
    public function has_ruletype_happened_since(int $id, ruletype $ruletype, DateTimeImmutable $since, int $maxcount = 1): bool {
        if (!$this->ruletyperesolver) {
            debugging('Cannot check rule type occurrence without rule type resolver.', DEBUG_DEVELOPER);
            return false;
        }

        $params = [
            'contextid' => $this->contextid,
            'userid' => $id,
            'timerecorded' => $since->getTimestamp(),
            'ruletype' => $this->ruletyperesolver->get_type_name($ruletype),
        ];

        $sql = "SELECT [[SELECT]]
                  FROM {{$this->table}} l
                  JOIN {block_xp_rule} r ON l.ruleid = r.id
                 WHERE l.contextid = :contextid
                   AND l.userid = :userid
                   AND l.timerecorded >= :timerecorded
                   AND l.ruleid IS NOT NULL
                   AND r.type = :ruletype";

        if ($maxcount === 1) {
            return $this->db->record_exists_sql(str_replace('[[SELECT]]', 1, $sql), $params);
        }
        return $this->db->count_records_sql(str_replace('[[SELECT]]', 'COUNT(*)', $sql), $params) >= $maxcount;
    }

    /**
     * Is the rule type reason limit reached.
     *
     * @param int $id The ID.
     * @param ruletype $ruletype The rule type.
     * @param reason $reason The reason.
     * @param limit_spec $limit The limit.
     * @return bool
     */
    public function is_ruletype_reason_limit_reached(int $id, ruletype $ruletype, reason $reason, limit_spec $limit): bool {
        if (!$this->ruletyperesolver) {
            debugging('Cannot check rule type occurrence without rule type resolver.', DEBUG_DEVELOPER);
            return false;
        } else if ($limit->get_max() <= 0) {
            return false;
        }

        $maxcount = $limit->get_max();
        [$timesql, $timeparams] = $this->get_limit_time_window_filter_sql($limit->get_time_window());
        [$reasonsql, $reasonparams] = $this->get_reason_filter_sql($reason, $limit->get_scope());

        $params = [
            'contextid' => $this->contextid,
            'userid' => $id,
            'ruletype' => $this->ruletyperesolver->get_type_name($ruletype),
        ] + $timeparams + $reasonparams;

        $sql = "SELECT [[SELECT]]
                  FROM {{$this->table}} l
                  JOIN {block_xp_rule} r ON l.ruleid = r.id
                 WHERE l.contextid = :contextid
                   AND l.userid = :userid
                   AND l.ruleid IS NOT NULL
                   AND $timesql
                   AND r.type = :ruletype
                   AND $reasonsql";

        if ($maxcount === 1) {
            return $this->db->record_exists_sql(str_replace('[[SELECT]]', 1, $sql), $params);
        }
        return $this->db->count_records_sql(str_replace('[[SELECT]]', 'COUNT(*)', $sql), $params) >= $maxcount;
    }

    /**
     * Is the rule reason limit reached.
     *
     * @param int $userid The user ID.
     * @param int $ruleid The rule ID.
     * @param reason $reason The reason.
     * @param limit_spec $limit The limit.
     * @return bool
     */
    public function is_rule_reason_limit_reached(int $userid, int $ruleid, reason $reason, limit_spec $limit): bool {
        if ($limit->get_max() <= 0) {
            return false;
        }

        $params = [
            'contextid' => $this->contextid,
            'userid' => $userid,
            'ruleid' => $ruleid,
        ];

        [$reasonsql, $reasonparams] = $this->get_reason_filter_sql($reason, $limit->get_scope());
        $params += $reasonparams;

        [$limittimewindowsql, $limittimewindowparams] = $this->get_limit_time_window_filter_sql($limit->get_time_window());
        $params += $limittimewindowparams;

        $sql = "SELECT [[SELECT]]
                  FROM {{$this->table}}
                 WHERE contextid = :contextid
                   AND userid = :userid
                   AND ruleid = :ruleid
                   AND $reasonsql
                   AND $limittimewindowsql";

        if ($limit->get_max() === 1) {
            return $this->db->record_exists_sql(str_replace('[[SELECT]]', 1, $sql), $params);
        }
        return $this->db->count_records_sql(str_replace('[[SELECT]]', 'COUNT(*)', $sql), $params) >= $limit->get_max();
    }

    /**
     * Log.
     *
     * @param int $id The target.
     * @param int $points The points.
     * @param string $signature A signature.
     * @param DateTime|null $time When that happened.
     */
    public function log($id, $points, $signature, ?DateTime $time = null) {
        debugging("This log method is not implemented, use log_reason instead.", DEBUG_DEVELOPER);
    }

    /**
     * Log.
     *
     * @param int $id The target.
     * @param int $points The points.
     * @param reason $reason A reason.
     * @param DateTime|null $time When that happened.
     */
    public function log_reason($id, $points, reason $reason, ?DateTime $time = null) {
        if (!$this->reasonresolver) {
            debugging('The reason resolver is not set, cannot log reason.', DEBUG_DEVELOPER);
            return;
        }
        $name = $this->reasonresolver->get_name($reason);
        $subtype = $reason instanceof reason_with_subtype ? $reason->get_subtype() : null;

        $parentid = null;
        $objectid = null;
        $envid = null;
        if ($reason instanceof reason_with_tracking) {
            $parentid = $reason->get_parent_id();
            $objectid = $reason->get_object_id();
            $envid = $reason->get_env_id();
        } else {
            $backfill = reason_utils::get_backfilled_tracking_values($reason);
            $envid = $backfill->envid ?? null;
            $parentid = $backfill->parentid ?? null;
            $objectid = $backfill->objectid ?? null;
        }

        $record = (object) [
            'contextid' => $this->contextid,
            'userid' => $id,
            'points' => $points,
            'reason' => $name,
            'subtype' => $subtype,
            'envid' => $envid,
            'parentid' => $parentid,
            'objectid' => $objectid,
            'ruleid' => $reason instanceof reason_with_rule ? $reason->get_rule_id() : null,
            'reasontypehash' => $this->get_reason_type_hash($reason),
            'timerecorded' => $time ? $time->getTimestamp() : di::get('clock')->time(),
        ];
        $this->db->insert_record($this->table, $record);
    }

    /**
     * Purge all logs.
     *
     * @return void
     */
    public function reset() {
        $this->db->delete_records($this->table, ['contextid' => $this->contextid]);
    }

    /**
     * Purge logs for users in a group.
     *
     * @param int $groupid The group ID.
     * @return void
     */
    public function reset_by_group($groupid) {
        $sql = "DELETE
                  FROM {{$this->table}}
                 WHERE contextid = :contextid
                   AND userid IN
               (SELECT gm.userid
                  FROM {groups_members} gm
                 WHERE gm.groupid = :groupid)";

        $params = [
            'contextid' => $this->contextid,
            'groupid' => $groupid,
        ];

        $this->db->execute($sql, $params);
    }

    /**
     * Purge by ID.
     *
     * @param int $id The ID.
     * @return void
     */
    public function reset_by_id($id) {
        $this->db->delete_records(
            $this->table,
            [
                'contextid' => $this->contextid,
                'userid' => $id,
            ]
        );
    }

    /**
     * Get the recent user's activity.
     *
     * @param int $userid The user ID.
     * @param int $count The number of entries.
     * @return activity[]
     */
    public function get_user_recent_activity($userid, $count = 0) {
        $results = $this->db->get_records_select($this->table, 'contextid = :contextid AND userid = :userid AND points > 0', [
            'contextid' => $this->contextid,
            'userid' => $userid,
        ], 'timerecorded DESC, id DESC', '*', 0, $count);
        return array_map(function ($row) {
            $reason = $this->reasonfactory ? $this->reasonfactory->get_reason_from_log_entry($row->reason, $row) : null;
            $desc = $reason && $reason instanceof reason_with_short_description ? $reason->get_short_description() : '';
            return new xp_activity(new DateTime('@' . $row->timerecorded), $desc, $row->points);
        }, $results);
    }

    /**
     * Set the reason from log entry factory.
     *
     * @param reason_from_log_entry_factory $reasonfactory The reason factory.
     */
    public function set_reason_from_log_entry_factory(reason_from_log_entry_factory $reasonfactory) {
        $this->reasonfactory = $reasonfactory;
    }

    /**
     * Set the reason resolver.
     *
     * @param resolver $reasonresolver The reason resolver.
     */
    public function set_reason_resolver(resolver $reasonresolver) {
        $this->reasonresolver = $reasonresolver;
    }

    /**
     * Set the rule type resolver.
     *
     * @param ruletype_resolver $ruletyperesolver The rule type resolver.
     */
    public function set_rule_type_resolver(ruletype_resolver $ruletyperesolver) {
        $this->ruletyperesolver = $ruletyperesolver;
    }

}
