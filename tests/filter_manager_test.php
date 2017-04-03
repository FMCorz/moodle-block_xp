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
 * Test filters.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * filterset testcase.
 *
 * @package    block_xp
 * @copyright  2017 Ruben Cancho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_filter_manager_testcase extends advanced_testcase {

    protected function setUp() {
        $this->resetAfterTest(true);
    }

    function test_append_default_filters_to_courses_with_empty_filters() {
        // Generate courses
        $course1 = $this->getDataGenerator()->create_course();
        $course2 = $this->getDataGenerator()->create_course();

        // Add default config
        $manager1 = block_xp_manager::get($course1->id);
        $manager2 = block_xp_manager::get($course2->id);

        // Create default config (simulate adding levelup block to course)
        // NOTE: This doesn't add default filters.
        $manager1->update_config(array('enabled' => true));
        $manager2->update_config(array('enabled' => true));

        $fm1 = $manager1->get_filter_manager();
        $fm2 = $manager2->get_filter_manager();

        // By default a course should have no rules.
        $this->assertSame(0, $fm1->get_all_filters()->count());

        // Add default filters to courses where block was added.
        block_xp_filter_manager::append_default_filters_to_courses();

        // Check added number of filtersets in courses.
        $this->assertSame(5, $fm1->get_all_filters()->count());
        $this->assertSame(5, $fm2->get_all_filters()->count());

        // Add default filters again, should not copy them again...
        block_xp_filter_manager::append_default_filters_to_courses($course1->id);

        // FAILING: should not add filters if they are already in the course.
        $this->assertSame(5, $fm1->get_all_filters()->count());
    }


    function test_append_default_filters_to_courses_with_filters() {
        $course = $this->getDataGenerator()->create_course();
        $manager = block_xp_manager::get($course->id);
        $manager->update_config(array('enabled' => true));

        $fm = $manager->get_filter_manager();


        // At first a course have not rules
        $this->assertSame(0, $fm->get_all_filters()->count());

        // Create rule and add it to the course.
        $rule = new block_xp_rule_property(block_xp_rule_base::CT, 'assessable_submitted', 'eventname');

        $filter = block_xp_filter_course::create_from_data(
                array('rule' => $rule, 'points' => 22, 'editable' => true));

        $filterset = block_xp_filterset_course::create($course->id);
        $filterset->add_first($filter);
        $filterset->save();

        $fm->invalidate_filters($course->id);

        // test that we have only 1 rule.
        $this->assertSame(1, $fm->get_all_filters()->count());

        block_xp_filter_manager::append_default_filters_to_courses($course->id);

        // test that we have 6 rules
        $this->assertSame(6, $fm->get_all_filters()->count());

    }

    function test_save_default_filters() {
        block_xp_filter_manager::save_default_filters();
        $this->assertSame(5, block_xp_filter_manager::get_default_filters()->count());

        // Filters do not duplicate.
        block_xp_filter_manager::save_default_filters();
        $this->assertSame(5, block_xp_filter_manager::get_default_filters()->count());
    }
}