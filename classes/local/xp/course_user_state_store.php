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
// See <https://levelup.plus>.

/**
 * User state course store.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\xp;

use context_course;
use context_helper;
use moodle_database;
use stdClass;
use block_xp\local\iterator\map_recordset;
use block_xp\local\logger\collection_logger_with_group_reset;
use block_xp\local\logger\collection_logger_with_id_reset;
use block_xp\local\logger\collection_logger;
use block_xp\local\logger\reason_collection_logger;
use block_xp\local\observer\level_up_state_store_observer;
use block_xp\local\observer\points_changed_state_store_observer;
use block_xp\local\observer\points_increased_state_store_observer;
use block_xp\local\reason\reason;
use block_xp\local\sql\limit;
use block_xp\local\utils\user_utils;

/**
 * User state course store.
 *
 * This is a repository of XP of each user.
 *
 * It also used to store the level of each user in the 'lvl' column, for ordering purposes,
 * but no longer does. When levels_info were changed, the levels had to be updated.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_user_state_store implements
    course_state_store,
    state_store_with_delete,
    state_store_with_presence,
    state_store_with_query,
    state_store_with_reason {
    /** @var moodle_database The database. */
    protected $db;
    /** @var int The course ID. */
    protected $courseid;
    /** @var levels_info The levels info. */
    protected $levelsinfo;
    /** @var string The DB table. */
    protected $table = 'block_xp';
    /** @var collection_logger The logger. */
    protected $logger;
    /** @var level_up_state_store_observer The observer. */
    protected $observer;
    /** @var points_increased_state_store_observer The observer. */
    protected $pointsobserver;

    /**
     * Constructor.
     *
     * @param moodle_database $db The DB.
     * @param levels_info $levelsinfo The levels info.
     * @param int $courseid The course ID.
     * @param collection_logger $logger The reason logger.
     * @param level_up_state_store_observer $observer The observer.
     * @param points_increased_state_store_observer $pointsobserver The observer.
     */
    public function __construct(
        moodle_database $db,
        levels_info $levelsinfo,
        $courseid,
        collection_logger $logger,
        ?level_up_state_store_observer $observer = null,
        ?points_increased_state_store_observer $pointsobserver = null
    ) {
        $this->db = $db;
        $this->levelsinfo = $levelsinfo;
        $this->courseid = $courseid;
        $this->logger = $logger;
        $this->observer = $observer;
        $this->pointsobserver = $pointsobserver;
    }

    /**
     * Count matching states.
     *
     * @param state_store_query $query The query.
     * @return int
     */
    public function count(state_store_query $query): int {
        $sql = $this->prepare_query($query);
        return (int) $this->db->count_records_sql("SELECT COUNT(1) FROM {$sql->from} WHERE {$sql->where}", $sql->params);
    }

    /**
     * Get a state.
     *
     * @param int $id The object ID.
     * @return state
     */
    public function get_state($id) {
        $userfields = user_utils::picture_fields('u', 'userid');
        $contextfields = context_helper::get_preload_record_columns_sql('ctx');

        $sql = "SELECT u.id, x.userid, x.xp, $userfields, $contextfields
                  FROM {user} u
                  JOIN {context} ctx
                    ON ctx.instanceid = u.id
                   AND ctx.contextlevel = :contextlevel
             LEFT JOIN {{$this->table}} x
                    ON x.userid = u.id
                   AND x.courseid = :courseid
                 WHERE u.id = :userid";

        $params = [
            'contextlevel' => CONTEXT_USER,
            'courseid' => $this->courseid,
            'userid' => $id,
        ];

        return $this->make_state_from_record($this->db->get_record_sql($sql, $params, MUST_EXIST));
    }

    /**
     * Get supported sort fields and their SQL expressions.
     *
     * @return string[] SQL expressions keyed by query field name.
     */
    protected function get_supported_query_sort_fields(): array {
        return [
            'xp' => 'COALESCE(x.xp, 0)',
            'id' => 'u.id',
            'firstname' => 'u.firstname',
            'lastname' => 'u.lastname',
            'firstnamephonetic' => 'u.firstnamephonetic',
            'lastnamephonetic' => 'u.lastnamephonetic',
            'middlename' => 'u.middlename',
            'alternatename' => 'u.alternatename',
        ];
    }

    /**
     * List matching states.
     *
     * @param state_store_query $query The query.
     * @param limit $limit The limit.
     * @return iterable<state>
     */
    public function list(state_store_query $query, limit $limit) {
        $sql = $this->prepare_query($query);
        $recordset = $this->db->get_recordset_sql(
            "SELECT {$sql->fields} FROM {$sql->from} WHERE {$sql->where} ORDER BY {$sql->orderby}",
            $sql->params,
            $limit->get_offset(),
            $limit->get_count()
        );
        return new map_recordset($recordset, function ($record) {
            return $this->make_state_from_record($record, 'id');
        });
    }

    /**
     * Prepare the SQL parts shared by listing and counting states.
     *
     * Includes users enrolled with permission to earn XP, and users with stored XP.
     * Query conditions further restrict that population. Missing stored XP is represented
     * by a zero-point state, as it is in get_state().
     *
     * @param state_store_query $query The query.
     * @return stdClass SQL fields, from, where, params and orderby.
     */
    protected function prepare_query(state_store_query $query): stdClass {
        $context = context_course::instance($this->courseid);
        [$enrolledsql, $enrolledparams] = get_enrolled_sql($context, 'block/xp:earnxp');

        $sql = new stdClass();
        $sql->fields = user_utils::picture_fields('u') . ', u.idnumber, u.email, u.username, u.suspended, x.xp, ' .
            context_helper::get_preload_record_columns_sql('ctx');
        $sql->from = "{user} u
                       JOIN {context} ctx
                         ON ctx.instanceid = u.id
                        AND ctx.contextlevel = :contextlevel
                  LEFT JOIN {{$this->table}} x
                         ON x.userid = u.id
                        AND x.courseid = :courseid";
        $sql->where = "u.deleted = 0 AND (x.userid IS NOT NULL OR u.id IN ($enrolledsql))";
        $sql->params = array_merge($enrolledparams, [
            'contextlevel' => CONTEXT_USER,
            'courseid' => $this->courseid,
        ]);

        if ($query->has_condition('userfilter')) {
            [$filtersql, $filterparams] = $query->get_condition('userfilter')->get_sql('u.id');
            $sql->where .= " AND ($filtersql)";
            $sql->params = array_merge($sql->params, $filterparams);
        }
        if ($query->has_condition('term')) {
            [$termsql, $termparams] = user_utils::get_filter_user_by_term_sql($query->get_condition('term'));
            $sql->where .= " AND ($termsql)";
            $sql->params = array_merge($sql->params, $termparams);
        }

        $orderbyaliases = $this->get_supported_query_sort_fields();
        $orderby = [];
        foreach ($query->get_order_by() ?: [['xp', SORT_DESC]] as [$field, $direction]) {
            if (!isset($orderbyaliases[$field])) {
                continue;
            }
            $orderby[$field] = $this->db->sql_order_by_null(
                $orderbyaliases[$field],
                $direction === SORT_ASC ? SORT_ASC : SORT_DESC
            );
        }
        if (!isset($orderby['id'])) {
            $orderby['id'] = 'u.id ASC';
        }
        $sql->orderby = implode(', ', $orderby);

        return $sql;
    }

    /**
     * Delete a state.
     *
     * @param int $id The object ID.
     * @return void
     */
    public function delete($id) {
        $params = [];
        $params['userid'] = $id;
        $params['courseid'] = $this->courseid;
        $this->db->delete_records($this->table, $params);

        if ($this->logger instanceof collection_logger_with_id_reset) {
            $this->logger->reset_by_id($id);
        }
    }

    /**
     * Return whether the entry exists.
     *
     * @param int $id The receiver.
     * @return stdClass|false
     */
    protected function exists($id) {
        $params = [];
        $params['userid'] = $id;
        $params['courseid'] = $this->courseid;
        return $this->db->get_record($this->table, $params);
    }

    /**
     * Whether a state exists.
     *
     * @param int $id The object ID.
     * @return bool
     */
    public function has($id) {
        return (bool) $this->exists($id);
    }

    /**
     * Add a certain amount of experience points.
     *
     * @param int $id The receiver.
     * @param int $amount The amount.
     */
    public function increase($id, $amount) {
        $prexp = 0;
        $postxp = $amount;

        if ($record = $this->exists($id)) {
            $prexp = $record->xp;
            $postxp = $prexp + $amount;

            $sql = "UPDATE {{$this->table}}
                       SET xp = xp + :xp
                     WHERE courseid = :courseid
                       AND userid = :userid";
            $params = [
                'xp' => $amount,
                'courseid' => $this->courseid,
                'userid' => $id,
            ];
            $this->db->execute($sql, $params);
        } else {
            $this->insert($id, $amount);
        }

        $this->observe_increase($id, $prexp, $postxp);
    }

    /**
     * Add a certain amount of experience points.
     *
     * @param int $id The receiver.
     * @param int $amount The amount.
     * @param reason $reason A reason.
     */
    public function increase_with_reason($id, $amount, reason $reason) {
        $this->increase($id, $amount);
        if ($this->logger instanceof reason_collection_logger) {
            $this->logger->log_reason($id, $amount, $reason);
        }
    }

    /**
     * Insert the entry in the database.
     *
     * @param int $id The receiver.
     * @param int $amount The amount.
     */
    protected function insert($id, $amount) {
        $record = new stdClass();
        $record->courseid = $this->courseid;
        $record->userid = $id;
        $record->xp = $amount;
        $this->db->insert_record($this->table, $record);
    }

    /**
     * Make a user_state from the record.
     *
     * @param stdClass $record The row.
     * @param string $useridfield The user ID field.
     * @return user_state
     */
    public function make_state_from_record(stdClass $record, $useridfield = 'userid') {
        $user = $this->make_user_from_record($record, $useridfield);
        context_helper::preload_from_record($record);
        $xp = !empty($record->xp) ? $record->xp : 0;
        $state = new user_state($user, $xp, $this->levelsinfo, $this->courseid);
        $state->set_present(isset($record->xp));
        return $state;
    }

    /**
     * Make a user from a state record.
     *
     * @param stdClass $record The row.
     * @param string $useridfield The user ID field.
     * @return stdClass
     */
    protected function make_user_from_record(stdClass $record, $useridfield = 'userid'): stdClass {
        $user = user_utils::unalias_picture_fields($record, $useridfield);
        foreach (['idnumber', 'username', 'suspended'] as $field) {
            if (property_exists($record, $field)) {
                $user->{$field} = $record->{$field};
            }
        }
        return $user;
    }

    /**
     * Observe when increased.
     *
     * @param int $id The recipient.
     * @param int $beforexp The points before.
     * @param int $afterxp The points after.
     * @return void
     */
    protected function observe_increase($id, $beforexp, $afterxp) {
        $xpgained = $afterxp - $beforexp;

        if ($this->pointsobserver && $xpgained > 0) {
            $this->pointsobserver->points_increased($this, $id, $xpgained);
        }

        if ($this->pointsobserver instanceof points_changed_state_store_observer && $beforexp != $afterxp) {
            $this->pointsobserver->points_changed($this, $id, $beforexp, $afterxp);
        }

        if ($this->observer) {
            $beforelevel = $this->levelsinfo->get_level_from_xp($beforexp);
            $afterlevel = $this->levelsinfo->get_level_from_xp($afterxp);
            if ($beforelevel->get_level() < $afterlevel->get_level()) {
                $this->observer->leveled_up($this, $id, $beforelevel, $afterlevel);
            }
        }
    }

    /**
     * Observe when set.
     *
     * @param int $id The recipient.
     * @param int $beforexp The points before.
     * @param int $afterxp The points after.
     * @return void
     */
    protected function observe_set($id, $beforexp, $afterxp) {
        if ($this->pointsobserver instanceof points_changed_state_store_observer && $beforexp != $afterxp) {
            $this->pointsobserver->points_changed($this, $id, $beforexp, $afterxp);
        }

        if (!$this->observer instanceof level_up_state_store_observer) {
            $beforelevel = $this->levelsinfo->get_level_from_xp($beforexp);
            $afterlevel = $this->levelsinfo->get_level_from_xp($afterxp);
            if ($beforelevel->get_level() < $afterlevel->get_level()) {
                $this->observer->leveled_up($this, $id, $beforelevel, $afterlevel);
            }
        }
    }

    /**
     * Recalculate all the levels.
     *
     * Remember, these values are used for ordering only.
     *
     * @deprecated Since Level Up XP 3.15 without replacement.
     * @return void
     */
    public function recalculate_levels() {
        debugging('Reclaculating levels has been deprecated and made ineffective, do not use.', DEBUG_DEVELOPER);
    }

    /**
     * Reset all experience points.
     *
     * @return void
     */
    public function reset() {
        $this->db->delete_records($this->table, ['courseid' => $this->courseid]);
        $this->logger->reset();
    }

    /**
     * Reset all experience for users in a group.
     *
     * @param int $groupid The group ID.
     * @return void
     */
    public function reset_by_group($groupid) {
        $sql = "DELETE
                  FROM {{$this->table}}
                 WHERE courseid = :courseid
                   AND userid IN
               (SELECT gm.userid
                  FROM {groups_members} gm
                 WHERE gm.groupid = :groupid)";

        $params = [
            'courseid' => $this->courseid,
            'groupid' => $groupid,
        ];

        $this->db->execute($sql, $params);

        if ($this->logger instanceof collection_logger_with_group_reset) {
            $this->logger->reset_by_group($groupid);
        }
    }

    /**
     * Set the amount of experience points.
     *
     * @param int $id The receiver.
     * @param int $amount The amount.
     */
    public function set($id, $amount) {
        $prexp = 0;
        $postxp = $amount;

        if ($record = $this->exists($id)) {
            $prexp = $record->xp;
            $postxp = $amount;

            $sql = "UPDATE {{$this->table}}
                       SET xp = :xp
                     WHERE courseid = :courseid
                       AND userid = :userid";
            $params = [
                'xp' => $amount,
                'courseid' => $this->courseid,
                'userid' => $id,
            ];
            $this->db->execute($sql, $params);
        } else {
            $this->insert($id, $amount);
        }

        $this->observe_set($id, $prexp, $postxp);
    }

    /**
     * Set the amount of experience points.
     *
     * @param int $id The receiver.
     * @param int $amount The amount.
     * @param reason $reason A reason.
     */
    public function set_with_reason($id, $amount, reason $reason) {
        $this->set($id, $amount);
        $this->logger->log_reason($id, $amount, $reason);
    }
}
