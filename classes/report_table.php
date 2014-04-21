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
 * Block XP report table.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/tablelib.php');

/**
 * Block XP report table class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_report_table extends table_sql {

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
     */
    public function __construct($uniqueid, $courseid) {
        global $PAGE;
        parent::__construct($uniqueid);

        // Block XP stuff.
        $this->xpmanager = block_xp_manager::get($courseid);
        $this->xpoutput = $PAGE->get_renderer('block_xp');

        // Define columns.
        $this->define_columns(array(
            'userpic',
            'fullname',
            'lvl',
            'xp',
            'progress'
        ));
        $this->define_headers(array(
            '',
            get_string('fullname'),
            get_string('level', 'block_xp'),
            get_string('xp', 'block_xp'),
            get_string('progress', 'block_xp'),
        ));

        // Define SQL.
        $this->sql = new stdClass();
        $this->sql->fields = 'x.*, ' . user_picture::fields('u');
        $this->sql->from = '{block_xp} x LEFT JOIN {user} u ON x.userid = u.id';
        $this->sql->where = 'courseid = :courseid';
        $this->sql->params = array('courseid' => $courseid);

        // Define various table settings.
        $this->sortable(true, 'lvl', SORT_DESC);
        $this->no_sorting('userpic');
        $this->no_sorting('progress');
        $this->collapsible(false);
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

}
