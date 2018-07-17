<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * State store leaderboard.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\xp;
defined('MOODLE_INTERNAL') || die();

use moodle_database;
use block_xp\local\sql\limit;

/**
 * State store leaderboard.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_user_state_store_leaderboard {

    /** @var moodle_database The database. */
    protected $db;
    /** @var int The course ID. */
    protected $courseid;
    /** @var int The group ID. */
    protected $groupid;
    /** @var levels_info The levels info. */
    protected $levelsinfo;
    /** @var string The DB table. */
    protected $table = 'block_xp';
    /** @var string SQL fields. */
    protected $fields;
    /** @var string SQL from. */
    protected $from;
    /** @var string SQL where. */
    protected $where;
    /** @var string SQL order. */
    protected $order;
    /** @var array SQL params. */
    protected $params;

    /**
     * Constructor.
     *
     * @param moodle_database $db The DB.
     * @param levels_info $levelsinfo The levels info.
     * @param int $courseid The course ID.
     * @param int $groupid The group ID, or zero.
     */
    public function __construct(moodle_database $db, levels_info $levelsinfo, $courseid, $groupid = 0) {

        debugging('The class block_xp\\local\\xp\\course_user_state_store_leaderboard is deprecated, please use ' .
            'block_xp\\local\\leaderboard\\course_user_leaderboard instead.', DEBUG_DEVELOPER);

        $this->db = $db;
        $this->levelsinfo = $levelsinfo;
        $this->courseid = $courseid;
        $this->groupid = $groupid;

        $params = [];
        $groupsql = '';
        if ($groupid) {
            $groupsql = "JOIN {groups_members} gm
                           ON gm.groupid = :groupid
                          AND gm.userid = x.userid";
            $params['groupid'] = $groupid;
        }

        $this->fields = 'x.*, ' .
            \user_picture::fields('u', null, 'userid') . ', ' .
            \context_helper::get_preload_record_columns_sql('ctx');
        $this->from = "{{$this->table}} x
                       $groupsql
                  JOIN {user} u
                    ON x.userid = u.id
                  JOIN {context} ctx
                    ON ctx.instanceid = u.id
                   AND ctx.contextlevel = :contextlevel";
        $this->where = "x.courseid = :courseid";
        $this->order = "x.xp DESC, x.userid ASC";
        $this->params = $params + [
            'contextlevel' => CONTEXT_USER,
            'courseid' => $this->courseid,
        ];
    }

    /**
     * Get the number of rows in the leaderboard.
     *
     * @return int
     */
    public function get_count() {
        $sql = "SELECT COUNT('x')
                  FROM {$this->from}
                 WHERE {$this->where}";
        return $this->db->count_records_sql($sql, $this->params);
    }

    /**
     * Get position.
     *
     * @param state $state The state.
     * @return int Indexed from 0.
     */
    public function get_position(state $state) {
        $sql = "SELECT COUNT('x')
                  FROM {$this->from}
                 WHERE {$this->where}
                   AND (x.xp > :posxp
                    OR (x.xp = :posxpeq AND x.userid < :posid))";
        $params = $this->params + [
            'posxp' => $state->get_xp(),
            'posxpeq' => $state->get_xp(),
            'posid' => $state->get_id()
        ];
        return $this->db->count_records_sql($sql, $params);
    }

    /**
     * Get rank.
     *
     * @param int $id The object ID.
     * @return int Indexed from 1.
     */
    public function get_rank(state $state) {
        $sql = "SELECT COUNT('x')
                  FROM {$this->from}
                 WHERE {$this->where}
                   AND (x.xp > :posxp)";
        return $this->db->count_records_sql($sql, $this->params + ['posxp' => $state->get_xp()]) + 1;
    }

    /**
     * Get the ranking.
     *
     * @param limit $limit The limit.
     * @return Traversable
     */
    public function get_ranking(limit $limit = null) {
        $recordset = $this->get_ranking_recordset($limit);

        $rank = null;
        $offset = null;
        $lastxp = null;
        $ranking = [];

        foreach ($recordset as $record) {
            $state = $this->make_state_from_record($record);

            if ($rank === null || $lastxp !== $state->get_xp()) {
                if ($rank === null) {
                    $pos = $this->get_position($state);
                    $rank = $this->get_rank($state);
                    $offset = 1 + ($pos + 1 - $rank);
                } else {
                    $rank += $offset;
                    $offset = 1;
                }
                $lastxp = $state->get_xp();
            } else {
                $offset++;
            }

            $rankobj = new \stdClass($rank, $state);
            $ranking[] = new state_rank($rank, $state);

        }

        $recordset->close();
        return $ranking;
    }

    /**
     * Get ranking recordset.
     *
     * @param limit|null $limit The limit.
     * @return moodle_recordset
     */
    protected function get_ranking_recordset(limit $limit = null) {
        $sql = "SELECT {$this->fields}
                  FROM {$this->from}
                 WHERE {$this->where}
              ORDER BY {$this->order}";
        if ($limit) {
            $recordset = $this->db->get_recordset_sql($sql, $this->params, $limit->get_offset(), $limit->get_count());
        } else {
            $recordset = $this->db->get_recordset_sql($sql, $this->params);
        }
        return $recordset;
    }

    /**
     * Get the relative ranking.
     *
     * Automatically appends the state we are relative to if not seen.
     *
     * @param state $relativeto The state to compare with.
     * @param limit $limit The limit.
     * @return Traversable
     */
    public function get_relative_ranking(state $relativeto, limit $limit = null) {
        $recordset = $this->get_ranking_recordset($limit);

        $offset = $relativeto->get_xp();
        $ranking = [];

        foreach ($recordset as $record) {
            $state = $this->make_state_from_record($record);
            $rank = $state->get_xp() - $offset;
            $rankobj = new \stdClass($rank, $state);
            $ranking[] = new state_rank($rank, $state);
        }

        $recordset->close();
        return $ranking;
    }

    /**
     * Make a user_state from the record.
     *
     * @param stdClass $record The row.
     * @param string $useridfield The user ID field.
     * @return user_state
     */
    protected function make_state_from_record(\stdClass $record, $useridfield = 'userid') {
        $user = \user_picture::unalias($record, null, $useridfield);
        \context_helper::preload_from_record($record);
        $xp = !empty($record->xp) ? $record->xp : 0;
        return new user_state($user, $xp, $this->levelsinfo);
    }

}
