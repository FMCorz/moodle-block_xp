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
 * Main factory.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\factory;
defined('MOODLE_INTERNAL') || die();

/**
 * Main factory.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class factory implements filter_manager_factory, manager_factory {

    protected $filtermanagers = [];
    protected $managers = [];

    public function get_filter_manager(\block_xp\local\manager_interface $manager) {
        $courseid = $manager->get_courseid();
        if (!isset($this->filtermanagers[$courseid])) {
            $this->filtermanagers[$courseid] = new \block_xp\local\filter_manager($manager);
        }
        return $this->filtermanagers[$courseid];
    }

    public function get_manager($courseid) {
        global $CFG;

        // When the block was set up for the whole site we attach it to the site course.
        if ($CFG->block_xp_context == CONTEXT_SYSTEM) {
            $courseid = SITEID;
        }

        $courseid = intval($courseid);
        if (!isset($this->managers[$courseid])) {
            $this->managers[$courseid] = new \block_xp\local\manager($courseid);
        }
        return $this->managers[$courseid];
    }

}
