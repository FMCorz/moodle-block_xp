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
 * Base testcase.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\tests;

use block_manager;
use moodle_page;
use moodle_url;

/**
 * Base testcase class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base_testcase extends \advanced_testcase {

    use setup_trait;

    /**
     * Setup test.
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
        $page = new moodle_page();
        $page->set_context($context);
        $page->set_pagetype('page-type');
        $page->set_url(new moodle_url('/example/view.php'));
        $blockmanager = new block_manager($page);
        $blockmanager->add_regions(['xptest'], false);
        $blockmanager->set_default_region('xptest');
        $blockmanager->add_block($name, 'xptest', 0, false, $pagetypepattern, $subpagepattern);
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
