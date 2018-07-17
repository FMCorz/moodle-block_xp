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
 * Course user state leaderboard.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\leaderboard;
defined('MOODLE_INTERNAL') || die();

use coding_exception;
use context_helper;
use course_modinfo;
use moodle_database;
use stdClass;
use user_picture;
use block_xp\local\iterator\map_recordset;
use block_xp\local\sql\limit;
use block_xp\local\xp\course_user_state_store;
use block_xp\local\xp\levels_info;
use block_xp\local\xp\state_rank;
use block_xp\local\xp\user_state;

/**
 * Course user state leaderboard.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_user_leaderboard implements leaderboard {

    /** @var string[] The columns. */
    protected $columns;
    /** @var moodle_database The database. */
    protected $db;
    /** @var course_modinfo The course. */
    protected $course;
    /** @var int The course ID. */
    protected $courseid;
    /** @var int The group ID. */
    protected $groupid;
    /** @var levels_info The levels info. */
    protected $levelsinfo;
    /** @var ranker The ranker. */
    protected $ranker;
    /** @var string The DB table. */
    protected $table = 'block_xp';

    /**
     * Constructor.
     *
     * @param moodle_database $db The DB.
     * @param levels_info $levelsinfo The levels info.
     * @param int $courseid The course ID.
     * @param string[] $columns The name of the columns.
     * @param ranker $ranker An alternative ranker.
     * @param int $groupid The group ID.
     */
    public function __construct(
            moodle_database $db,
            levels_info $levelsinfo,
            $courseid,
            array $columns,
            ranker $ranker = null,
            $groupid = 0) {

        $this->db = $db;
        $this->levelsinfo = $levelsinfo;
        $this->courseid = $courseid;
        $this->ranker = $ranker;
        $this->groupid = $groupid;
        $this->columns = $columns;

        $params = [];
        $groupsql = '';
        if ($groupid) {
            $groupsql = "JOIN {groups_members} gm
                           ON gm.groupid = :groupid
                          AND gm.userid = x.userid";
            $params['groupid'] = $groupid;
        }

        $this->fields = 'x.*, ' .
            user_picture::fields('u', null, 'userid') . ', ' .
            context_helper::get_preload_record_columns_sql('ctx');
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
     * Get the leaderboard columns.
     *
     * @return array Where keys are column identifiers and values are lang_string objects.
     */
    public function get_columns() {
        return $this->columns;
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
     * Get the points of an object.
     *
     * @param int $id The object ID.
     * @return int|false False when not ranked.
     */
    protected function get_points($id) {
        $sql = "SELECT x.xp
                  FROM {$this->from}
                 WHERE {$this->where}
                   AND (x.userid = :userid)";
        $params = $this->params + ['userid' => $id];
        return $this->db->get_field_sql($sql, $params);
    }

    /**
     * Return the position of the object.
     *
     * The position is used to determine how to paginate the leaderboard.
     *
     * @param int $id The object ID.
     * @return int Indexed from 0, null when not ranked.
     */
    public function get_position($id) {
        $xp = $this->get_points($id);
        return $xp === false ? null : $this->get_position_with_xp($id, $xp);
    }

    /**
     * Get position based on ID and XP.
     *
     * @param int $id The object ID..
     * @param int $xp The amount of XP.
     * @return int Indexed from 0.
     */
    protected function get_position_with_xp($id, $xp) {
        $sql = "SELECT COUNT('x')
                  FROM {$this->from}
                 WHERE {$this->where}
                   AND (x.xp > :posxp
                    OR (x.xp = :posxpeq AND x.userid < :posid))";
        $params = $this->params + [
            'posxp' => $xp,
            'posxpeq' => $xp,
            'posid' => $id
        ];
        return $this->db->count_records_sql($sql, $params);
    }

    /**
     * Get the rank of an object.
     *
     * @param int $id The object ID.
     * @return rank|null
     */
    public function get_rank($id) {
        $state = $this->get_state($id);
        if (!$state) {
            return null;
        } else if ($this->ranker) {
            return $this->ranker->rank_state($state);
        }
        $rank = $this->get_rank_from_xp($state->get_xp());
        return new state_rank($rank, $state);
    }

    /**
     * Get the rank of an amount of XP.
     *
     * @param int $xp The xp.
     * @return int Indexed from 1.
     */
    protected function get_rank_from_xp($xp) {
        $sql = "SELECT COUNT('x')
                  FROM {$this->from}
                 WHERE {$this->where}
                   AND (x.xp > :posxp)";
        return $this->db->count_records_sql($sql, $this->params + ['posxp' => $xp]) + 1;
    }

    /**
     * Get the ranking.
     *
     * @param limit $limit The limit.
     * @return Traversable
     */
    public function get_ranking(limit $limit) {
        $recordset = $this->get_ranking_recordset($limit);

        if ($this->ranker) {
            return $this->ranker->rank_states(
                new map_recordset($recordset, function($record) {
                    return $this->make_state_from_record($record);
                })
            );
        }

        $rank = null;
        $offset = null;
        $lastxp = null;
        $ranking = [];

        foreach ($recordset as $record) {
            $state = $this->make_state_from_record($record);

            if ($rank === null || $lastxp !== $state->get_xp()) {
                if ($rank === null) {
                    $pos = $this->get_position_with_xp($state->get_id(), $state->get_xp());
                    $rank = $this->get_rank_from_xp($state->get_xp());
                    $offset = 1 + ($pos + 1 - $rank);
                } else {
                    $rank += $offset;
                    $offset = 1;
                }
                $lastxp = $state->get_xp();
            } else {
                $offset++;
            }

            $ranking[] = new state_rank($rank, $state);
        }

        $recordset->close();
        return $ranking;
    }

    /**
     * Get ranking recordset.
     *
     * @param limit $limit The limit.
     * @return moodle_recordset
     */
    protected function get_ranking_recordset(limit $limit) {
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
     * Get the state.
     *
     * @param int $id The object ID.
     * @return state|null
     */
    protected function get_state($id) {
        $sql = "SELECT {$this->fields}
                  FROM {$this->from}
                 WHERE {$this->where}
                   AND (x.userid = :userid)";
        $params = $this->params + ['userid' => $id];
        $record = $this->db->get_record_sql($sql, $params);
        return !$record ? null : $this->make_state_from_record($record);
    }

    /**
     * Make a user_state from the record.
     *
     * @param stdClass $record The row.
     * @param string $useridfield The user ID field.
     * @return user_state
     */
    protected function make_state_from_record(stdClass $record, $useridfield = 'userid') {
        $user = user_picture::unalias($record, null, $useridfield);
        context_helper::preload_from_record($record);
        $xp = !empty($record->xp) ? $record->xp : 0;
        return new user_state($user, $xp, $this->levelsinfo);
    }
}
