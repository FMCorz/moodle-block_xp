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

global $CFG;
require_once($CFG->dirroot . '/blocks/xp/tests/fixtures/events.php');

/**
 * filterset testcase.
 *
 * @package    block_xp
 * @copyright  2017 Ruben Cancho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_filterset_testcase extends advanced_testcase {


    protected function setUp() {
        $this->resetAfterTest(true);
    }

    public function test_course_already_has_default_filters() {
        global $DB;

        $this->assertEquals(5,$DB->count_records('block_xp_filters', array('courseid' => 0)));
    }

    public function test_import_does_not_duplicate_default_filters() {
        global $DB;

        $this->assertEquals(5,$DB->count_records('block_xp_filters', array('courseid' => 0)));

        $staticfilters = new block_xp_filterset_static();
        $defaultfilters = new block_xp_filterset_default();
        $defaultfilters->import($staticfilters);

        $this->assertEquals(5,$DB->count_records('block_xp_filters', array('courseid' => 0)));

    }

    public function test_import_default_rules_to_empty_course() {
        global $DB;

        $course = $this->getDataGenerator()->create_course();

        $defaultfilters = new block_xp_filterset_default();
        $coursefilters = new block_xp_filterset_course($course->id);

        $this->assertEquals(0,$DB->count_records('block_xp_filters', array('courseid' => $course->id)));

        $coursefilters->import($defaultfilters);

        $this->assertEquals(5,$DB->count_records('block_xp_filters', array('courseid' => $course->id)));
    }

    public function test_import_default_rules_to_non_empty_course() {

    }

    public function test_add_rules_to_course_filterset() {
        global $DB;

        $course = $this->getDataGenerator()->create_course();
        $defaultfilters = new block_xp_filterset_default();
        $coursefilters = new block_xp_filterset_course($course->id);
        $coursefilters->import($defaultfilters);
        $filterset = new block_xp_filterset_course($course->id);

        $rule = new block_xp_rule_property(block_xp_rule_base::EQ, 'c', 'crud');
        $filter = new block_xp_filter_course($course->id);
        $filter->load(array('points' => 100, 'rule' => $rule, 'sortorder'));

        $filterset->add($filter, 1);

        $filterset->save();

        // Test if filterset contains filter in the correct position
        $this->assertTrue($filter == $filterset->get()[1]);

        // Test if filterset is saved correctly in DB
        $filterset2 = new block_xp_filterset_course($course->id);
        $this->assertTrue($filter->get_rule() == $filterset2->get()[1]->get_rule());

    }

}