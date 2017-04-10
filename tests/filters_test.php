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
 * Filters testcase.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_filters_testcase extends advanced_testcase {

    protected function setUp() {
        $this->resetAfterTest(true);
    }

    public function test_filter_match() {
        $rule = new block_xp_rule_property(block_xp_rule_base::EQ, 'c', 'crud');
        $filter = block_xp_filter::create_from_data(array('rule' => $rule));

        $e = \block_xp\event\something_happened::mock(array('crud' => 'c'));
        $this->assertTrue($filter->match($e));

        $e = \block_xp\event\something_happened::mock(array('crud' => 'd'));
        $this->assertFalse($filter->match($e));
    }

    public function test_filter_load_rule() {
        $rulec = new block_xp_rule_property(block_xp_rule_base::EQ, 'c', 'crud');
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c'));

        $filter = block_xp_filter::create_from_data(array('rule' => $rulec));
        $this->assertTrue($filter->match($e));

        $filter = block_xp_filter::create_from_data(array('ruledata' => json_encode($rulec->export())));
        $this->assertTrue($filter->match($e));

        $filter = block_xp_filter::create_from_data(array());
        $filter->set_rule($rulec);
        $this->assertTrue($filter->match($e));
    }

    public function test_standard_filters() {
        $course = $this->getDataGenerator()->create_course();
        $manager = block_xp_manager::get($course->id);
        block_xp_filter_manager::copy_default_filters_to_course($course->id);
        $fm = $manager->get_filter_manager();

        $c = \block_xp\event\something_happened::mock(array('crud' => 'c'));
        $r = \block_xp\event\something_happened::mock(array('crud' => 'r'));
        $u = \block_xp\event\something_happened::mock(array('crud' => 'u'));
        $d = \block_xp\event\something_happened::mock(array('crud' => 'd'));

        $this->assertSame(45, $fm->get_points_for_event($c));
        $this->assertSame(9, $fm->get_points_for_event($r));
        $this->assertSame(3, $fm->get_points_for_event($u));
        $this->assertSame(0, $fm->get_points_for_event($d));
    }

    public function test_custom_filters() {
        $course = $this->getDataGenerator()->create_course();
        $manager = block_xp_manager::get($course->id);
        $fm = $manager->get_filter_manager();
        $fm->copy_default_filters();

        $filterset = $fm->get_filters();

        $rule = new block_xp_ruleset(array(
                new block_xp_rule_property(block_xp_rule_base::GTE, 100, 'objectid'),
                new block_xp_rule_property(block_xp_rule_base::EQ, 'r', 'crud'),
        ), block_xp_ruleset::ALL);
        $data = array('points' => 130, 'rule' => $rule);

        $filter1 = new block_xp_filter_course($course->id);
        $filter1->load($data);
        $filterset->add_first($filter1);
        $filterset->save();

        $rule = new block_xp_ruleset(array(
            new block_xp_rule_property(block_xp_rule_base::EQ, 2, 'objectid'),
            new block_xp_rule_property(block_xp_rule_base::EQ, 'u', 'crud'),
        ), block_xp_ruleset::ANY);
        $data = array('points' => 120, 'rule' => $rule);

        $filter2 = new block_xp_filter_course($course->id);
        $filter2->load($data);
        $filterset->add_first($filter2);
        $filterset->save();

        // Define some custom rules, the sortorder and IDs are mixed here.
        $rule = new block_xp_rule_property(block_xp_rule_base::EQ, 'c', 'crud');
        $data = array('points' => 100, 'rule' => $rule);

        $filter3 = new block_xp_filter_course($course->id);
        $filter3->load($data);
        $filterset->add_first($filter3);
        $filterset->save();

        $fm->invalidate_filters($course->id);

        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'objectid' => 2));
        $this->assertSame(100, $fm->get_points_for_event($e));

        // We can still fallback on default filters.
        $e = \block_xp\event\something_happened::mock(array('crud' => 'd'));
        $this->assertSame(0, $fm->get_points_for_event($e));

        // Sort order is respected.
        $e = \block_xp\event\something_happened::mock(array('crud' => 'u', 'objectid' => 2));
        $this->assertSame(120, $fm->get_points_for_event($e));
        $e = \block_xp\event\something_happened::mock(array('crud' => 'r'));
        $this->assertSame(9, $fm->get_points_for_event($e));
        $e = \block_xp\event\something_happened::mock(array('crud' => 'r', 'objectid' => 100));
        $this->assertSame(130, $fm->get_points_for_event($e));

        // This filter will catch everything before the default rules.
        // We can override default filters.
        $rule = new block_xp_rule_property(block_xp_rule_base::CT, 'something', 'eventname');
        $data = array('points' => 110, 'rule' => $rule);

        $filter4 = new block_xp_filter_course($course->id);
        $filter4->load($data);
        $filterset->add($filter4, 3);
        $filterset->save();

        $fm->invalidate_filters($course->id);

        $e = \block_xp\event\something_happened::mock(array('crud' => 'd'));
        $this->assertSame(110, $fm->get_points_for_event($e));
        $e = \block_xp\event\something_happened::mock(array('crud' => 'r'));
        $this->assertSame(110, $fm->get_points_for_event($e));

        // This filter will catch everything.
        $rule = new block_xp_rule_property(block_xp_rule_base::CT, 'something', 'eventname');
        $data = array('points' => 1, 'rule' => $rule);

        $filter5 = new block_xp_filter_course($course->id);
        $filter5->load($data);
        $filterset->add_first($filter5);
        $filterset->save();

        $fm->invalidate_filters($course->id);

        $e = \block_xp\event\something_happened::mock(array('crud' => 'u', 'objectid' => 2));
        $this->assertSame(1, $fm->get_points_for_event($e));
        $e = \block_xp\event\something_happened::mock(array('crud' => 'r', 'objectid' => 100));
        $this->assertSame(1, $fm->get_points_for_event($e));
        $e = \block_xp\event\something_happened::mock(array('crud' => 'd'));
        $this->assertSame(1, $fm->get_points_for_event($e));
        $e = \block_xp\event\something_happened::mock(array('crud' => 'r'));
        $this->assertSame(1, $fm->get_points_for_event($e));

    }

}
