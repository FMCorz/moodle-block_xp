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
 * Block XP backup steplib.
 *
 * @package    block_xp
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block XP backup structure step class.
 *
 * @package    block_xp
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_xp_block_structure_step extends backup_block_structure_step {

    /**
     * Define structure.
     */
    protected function define_structure() {
        global $DB;

        $userinfo = $this->get_setting_value('users');

        // Define each element separated.
        $xpconfig = new backup_nested_element('config', array('courseid'), array(
            'enabled', 'enablelog', 'keeplogs', 'levels', 'lastlogpurge', 'enableladder', 'enableinfos', 'levelsdata',
            'enablelevelupnotif', 'enablecustomlevelbadges', 'maxactionspertime', 'timeformaxactions', 'timebetweensameactions',
            'identitymode', 'rankmode', 'neighbours', 'enablecheatguard'
        ));
        $xpfilters = new backup_nested_element('filters');
        $xpfilter = new backup_nested_element('filter', array('courseid'), array('ruledata', 'points', 'sortorder'));
        $xplevels = new backup_nested_element('xps');
        $xplevel = new backup_nested_element('xp', array('courseid'), array('userid', 'xp', 'lvl'));
        $xplogs = new backup_nested_element('logs');
        $xplog = new backup_nested_element('log', array('courseid'), array('userid', 'eventname', 'xp', 'time'));

        // Prepare the structure.
        $xp = $this->prepare_block_structure($xpconfig);

        $xpfilters->add_child($xpfilter);
        $xp->add_child($xpfilters);

        if ($userinfo) {
            $xplevels->add_child($xplevel);
            $xp->add_child($xplevels);

            $xplogs->add_child($xplog);
            $xp->add_child($xplogs);
        }

        // Define sources.
        $xpconfig->set_source_table('block_xp_config', array('courseid' => backup::VAR_COURSEID));
        $xpfilter->set_source_table('block_xp_filters', array('courseid' => backup::VAR_COURSEID));
        $xplevel->set_source_table('block_xp', array('courseid' => backup::VAR_COURSEID));
        $xplog->set_source_table('block_xp_log', array('courseid' => backup::VAR_COURSEID));

        // Annotations.
        $xplevel->annotate_ids('user', 'userid');
        $xplog->annotate_ids('user', 'userid');
        $xp->annotate_files('block_xp', 'badges', null, context_course::instance($this->get_courseid())->id);

        // Return the root element.
        return $xp;
    }
}
