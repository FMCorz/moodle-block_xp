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
 * Block XP ladder table.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/tablelib.php');

/**
 * Block XP ladder table class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_ladder_table extends table_sql {

    /** @var string The key of the user ID column. */
    public $useridfield = 'userid';

    /** @var block_xp_manager XP Manager. */
    protected $xpmanager = null;

    /** @var block_xp_manager XP Manager. */
    protected $xpoutput = null;

    /**
     * Constructor.
     *
     * @param string $uniqueid Unique ID.
     * @param int $courseid Course ID.
     * @param int $groupid Group ID.
     */
    public function __construct($uniqueid, $courseid, $groupid) {
        global $PAGE;
        parent::__construct($uniqueid);

        // Block XP stuff.
        $this->xpmanager = block_xp_manager::get($courseid);
        $this->xpoutput = $PAGE->get_renderer('block_xp');

        // Define columns.
        $this->define_columns(array(
            'rank',
            'userpic',
            'fullname',
            'lvl',
            'xp',
            'progress'
        ));
        $this->define_headers(array(
            get_string('rank', 'block_xp'),
            '',
            get_string('fullname'),
            get_string('level', 'block_xp'),
            get_string('xp', 'block_xp'),
            get_string('progress', 'block_xp'),
        ));

        // Define SQL.
        $sqlfrom = '';
        $sqlparams = array();
        if ($groupid) {
            $sqlfrom = '{block_xp} x
                     JOIN {groups_members} gm
                       ON gm.groupid = :groupid
                      AND gm.userid = x.userid
                LEFT JOIN {user} u
                       ON x.userid = u.id';
            $sqlparams = array('groupid' => $groupid);
        } else {
            $sqlfrom = '{block_xp} x LEFT JOIN {user} u ON x.userid = u.id';
        }

        $this->sql = new stdClass();
        $this->sql->fields = 'x.*, ' . user_picture::fields('u');
        $this->sql->from = $sqlfrom;
        $this->sql->where = 'courseid = :courseid';
        $this->sql->params = array_merge(array('courseid' => $courseid), $sqlparams);


        // Define various table settings.
        $this->sortable(false);
        $this->no_sorting('userpic');
        $this->no_sorting('progress');
        $this->collapsible(false);
    }

    /**
     * Process the data returned by the query.
     *
     * This is not very efficient, but it gives an accurate to each student.
     *
     * @return void
     */
    function build_table() {
        global $USER;

        $i = 0;
        $rank = 0;
        $lastlvl = -1;
        $lastxp = -1;
        $offset = 1;

        if ($this->rawdata) {
            foreach ($this->rawdata as $row) {

                // If this row is different than the previous one.
                if ($row->lvl != $lastlvl || $row->xp != $lastxp) {
                    $rank += $offset;
                    $offset = 1;
                    $lastlvl = $row->lvl;
                    $lastxp = $row->xp;
                } else {
                    $offset++;
                }

                $row->rank = $rank;

                $i++;

                if ($i > $this->pagesize * ($this->currpage + 1)) {
                    // We do not need to do anything any more.
                    return;
                } else if ($i > ($this->pagesize * $this->currpage)) {
                    // We display the results for that page only.
                    $classes = ($USER->id == $row->userid) ? 'highlight' : '';
                    $formattedrow = $this->format_row($row);
                    $this->add_data_keyed($formattedrow, $classes);
                }
            }
        }
    }

    /**
     * Formats the column progress.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_progress($row) {
        $progress = $this->xpmanager->get_progress_for_user($row->userid);
        return $this->xpoutput->progress_bar($progress);
    }

    /**
     * Formats the column userpic.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_userpic($row) {
        global $OUTPUT;
        return $OUTPUT->user_picture($row);
    }

    /**
     * Get the columns to sort by.
     *
     * @return array column => SORT_ constant.
     */
    public function get_sort_columns() {
        return array('lvl' => SORT_DESC, 'xp' => SORT_DESC);
    }

    /**
     * Query the db.
     *
     * This is overridden to get all the records in order to generate
     * an accurate rank.
     *
     * @param int $pagesize Size of page for paginated displayed table.
     * @param bool $useinitialsbar Do you want to use the initials bar?
     */
    function query_db($pagesize, $useinitialsbar=true) {
        global $DB;

        if (!$this->is_downloading()) {
            if ($this->countsql === NULL) {
                $this->countsql = 'SELECT COUNT(1) FROM '.$this->sql->from.' WHERE '.$this->sql->where;
                $this->countparams = $this->sql->params;
            }
            $grandtotal = $DB->count_records_sql($this->countsql, $this->countparams);
            if ($useinitialsbar && !$this->is_downloading()) {
                $this->initialbars($grandtotal > $pagesize);
            }

            list($wsql, $wparams) = $this->get_sql_where();
            if ($wsql) {
                $this->countsql .= ' AND '.$wsql;
                $this->countparams = array_merge($this->countparams, $wparams);

                $this->sql->where .= ' AND '.$wsql;
                $this->sql->params = array_merge($this->sql->params, $wparams);

                $total  = $DB->count_records_sql($this->countsql, $this->countparams);
            } else {
                $total = $grandtotal;
            }

            $this->pagesize($pagesize, $total);
        }

        $sort = $this->get_sql_sort();
        if ($sort) {
            $sort = "ORDER BY $sort";
        }
        $sql = "SELECT
                {$this->sql->fields}
                FROM {$this->sql->from}
                WHERE {$this->sql->where}
                {$sort}";

        $this->rawdata = $DB->get_recordset_sql($sql, $this->sql->params);
    }

}
