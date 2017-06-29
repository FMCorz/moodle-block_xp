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
 * Block XP restore steplib.
 *
 * @package    block_xp
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block XP restore structure step class.
 *
 * @package    block_xp
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_xp_block_structure_step extends restore_structure_step {

    /**
     * Execution conditions.
     *
     * @return bool
     */
    protected function execute_condition() {
        global $DB;

        // No restore on the front page.
        if ($this->get_courseid() == SITEID) {
            return false;
        }

        return true;
    }

    /**
     * Define structure.
     */
    protected function define_structure() {
        global $DB;

        $paths = array();
        $userinfo = $this->get_setting_value('users');

        // Define each path.
        $paths[] = new restore_path_element('block', '/block');
        $paths[] = new restore_path_element('config', '/block/config');
        $paths[] = new restore_path_element('filter', '/block/filters/filter');

        if ($userinfo) {
            $paths[] = new restore_path_element('xp', '/block/xps/xp');
            $paths[] = new restore_path_element('log', '/block/logs/log');
        }

        return $paths;
    }

    /**
     * Process block.
     */
    protected function process_block($data) {
        // Nothing to do here... \o/!
    }

    /**
     * Process config.
     */
    protected function process_config($data) {
        global $DB;
        $data['courseid'] = $this->get_courseid();

        // Guarantees that older backups are given the expected legacy value here.
        if (!isset($data['defaultfilters'])) {
            $data['defaultfilters'] = \block_xp\local\config\course_world_config::DEFAULT_FILTERS_STATIC;
        }

        if ($DB->record_exists('block_xp_config', array('courseid' => $data['courseid']))) {
            $this->log('block_xp: config not restored, existing config was found', backup::LOG_DEBUG);
            return;
        }
        $DB->insert_record('block_xp_config', $data);
    }

    /**
     * Process filter.
     */
    protected function process_filter($data) {
        global $DB;
        $data['courseid'] = $this->get_courseid();
        $DB->insert_record('block_xp_filters', $data);
    }

    /**
     * Process log.
     */
    protected function process_log($data) {
        global $DB;
        $data['courseid'] = $this->get_courseid();
        $data['userid'] = $this->get_mappingid('user', $data['userid']);
        $DB->insert_record('block_xp_log', $data);
    }

    /**
     * Process XP.
     */
    protected function process_xp($data) {
        global $DB;
        $data['courseid'] = $this->get_courseid();
        $data['userid'] = $this->get_mappingid('user', $data['userid']);
        if ($DB->record_exists('block_xp', array('courseid' => $data['courseid'], 'userid' => $data['userid']))) {
            $this->log("block_xp: XP of user with id '{$data['userid']}' not restored, existing entry found", backup::LOG_DEBUG);
            return;
        }
        $DB->insert_record('block_xp', $data);
    }

    /**
     * After execute.
     */
    protected function after_execute() {
        $this->add_related_files('block_xp', 'badges', null, $this->task->get_old_course_contextid());
    }

}
