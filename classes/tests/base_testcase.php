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
 * Base testcase.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\tests;

use block_manager;
use moodle_url;

/**
 * Base testcase class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base_testcase extends \advanced_testcase {

    /**
     * PHP Unit setup method.
     *
     * This method is final not to be overridden. It was never used directly, instead
     * usage of the setup_test method was required. We maintain this behaviour for simplicity.
     */
    final public function setUp(): void {
        $this->setup_test();
    }

    /**
     * Setup test.
     *
     * Historically added to serve as an alternate method that did not require a return type,
     * allowing us to define setUp more dynamically to support multiple PHP versions. But
     * we now keep it as the default method to setup tests.
     */
    public function setup_test() {
        $this->resetAfterTest();
        $this->reset_container();
    }

    /**
     * Add a block.
     *
     * @param string $name
     * @param \context $context
     * @param string|null $pagetypepattern
     * @param string|null $subpagepattern
     */
    protected function add_block_in_context($name, \context $context, $pagetypepattern = null, $subpagepattern = null) {
        global $CFG, $DB, $PAGE;

        $course = null;
        if ($coursecontext = $context->get_course_context(false)) {
            $course = $DB->get_record('course', ['id' => $coursecontext->instanceid], '*', MUST_EXIST);
        }

        $PAGE->set_context($context);
        $PAGE->set_pagetype('page-type');
        $PAGE->set_url(new moodle_url('/example/view.php'));
        if ($course) {
            $PAGE->set_course($course);
        }

        $blockmanager = new block_manager($PAGE);
        $blockmanager->add_regions(['xptest'], false);
        $blockmanager->set_default_region('xptest');
        $instance = $blockmanager->add_block($name, 'xptest', 0, false, $pagetypepattern, $subpagepattern);

        // Older versions did not return the instance.
        if ($instance === null && $CFG->branch <= 401) {
            $records = $DB->get_records('block_instances', ['blockname' => $name], 'id DESC', '*', 0, 1);
            $instance = block_instance('xp', reset($records));
        }

        return $instance;
    }

    /**
     * Get world by course ID.
     *
     * @param int $courseid The course ID.
     * @return \block_xp\local\course_world
     */
    protected function get_world($courseid) {
        return \block_xp\di::get('course_world_factory')->get_world($courseid);
    }

    /**
     * Reset the container.
     */
    protected function reset_container() {
        \block_xp\di::set_container(new \block_xp\local\default_container());
    }

}
