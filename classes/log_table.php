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
 * Block XP log table.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/tablelib.php');

/**
 * Block XP log table class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_log_table extends table_sql {

    /** @var string The key of the user ID column. */
    public $useridfield = 'userid';

    /** @var int The course ID. */
    protected $courseid;

    /**
     * Constructor.
     *
     * @param string $uniqueid Unique ID.
     * @param int $courseid Course ID.
     * @param int $groupid Group ID.
     */
    public function __construct($uniqueid, $courseid, $groupid) {
        parent::__construct($uniqueid);
        $this->courseid = $courseid;

        // Define columns.
        $this->define_columns(array(
            'time',
            'fullname',
            'xp',
            'eventname'
        ));
        $this->define_headers(array(
            get_string('eventtime', 'block_xp'),
            get_string('fullname'),
            get_string('xp', 'block_xp'),
            get_string('eventname', 'block_xp')
        ));

        // Define SQL.
        $sqlfrom = '';
        $sqlparams = array();
        if ($groupid) {
            $sqlfrom = '{block_xp_log} x
                     JOIN {groups_members} gm
                       ON gm.groupid = :groupid
                      AND gm.userid = x.userid
                LEFT JOIN {user} u
                       ON x.userid = u.id';
            $sqlparams = array('groupid' => $groupid);
        } else {
            $sqlfrom = '{block_xp_log} x LEFT JOIN {user} u ON x.userid = u.id';
        }

        // Define SQL.
        $this->sql = new stdClass();
        $this->sql->fields = 'x.*, ' . get_all_user_name_fields(true, 'u');
        $this->sql->from = $sqlfrom;
        $this->sql->where = 'courseid = :courseid';
        $this->sql->params = array_merge(array('courseid' => $courseid), $sqlparams);

        // Define various table settings.
        $this->sortable(true, 'time', SORT_DESC);
        $this->collapsible(false);
    }

    /**
     * Formats the column time.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_time($row) {
        return userdate($row->time);
    }

}
