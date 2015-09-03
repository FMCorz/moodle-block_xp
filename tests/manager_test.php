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
 * Block XP manager test.
 *
 * @package    block_xp
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/blocks/xp/tests/fixtures/events.php');

/**
 * Manager testcase.
 *
 * @package    block_xp
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_manager_testcase extends advanced_testcase {

    protected function setUp() {
        $this->resetAfterTest();
        block_xp_manager::purge_static_caches();
    }

    public function test_get_course() {
        global $PAGE;

        $c1 = $this->getDataGenerator()->create_course();
        $c2 = $this->getDataGenerator()->create_course();

        // Get a course that is not attached to the current page.
        $this->assertEquals(SITEID, $PAGE->course->id);
        $manager = block_xp_manager::get($c1->id);
        $course = $manager->get_course();
        $this->assertEquals($c1->id, $course->id);
        $this->assertSame($course, $manager->get_course()); // Confirm that the course is cached.

        // Get a course that is attached to the current page.
        $PAGE->set_course($c2);
        $manager = block_xp_manager::get($c2->id);
        $this->assertSame($PAGE->course, $manager->get_course());
    }

    public function test_reset_data() {
        global $DB;

        $c1 = $this->getDataGenerator()->create_course();
        $c2 = $this->getDataGenerator()->create_course();
        $u1 = $this->getDataGenerator()->create_user();
        $u2 = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user($u1->id, $c1->id);
        $this->getDataGenerator()->enrol_user($u2->id, $c1->id);
        $this->getDataGenerator()->enrol_user($u1->id, $c2->id);

        $manager = block_xp_manager::get($c1->id);
        $manager->update_config(array('enabled' => true, 'timebetweensameactions' => 0));

        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $manager->capture_event($e);
        $manager->capture_event($e);

        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c1->id));
        $manager->capture_event($e);
        $manager->capture_event($e);

        $manager = block_xp_manager::get($c2->id);
        $manager->update_config(array('enabled' => true, 'timebetweensameactions' => 0));

        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c2->id));
        $manager->capture_event($e);

        $this->assertEquals(2, $DB->count_records('block_xp', array('courseid' => $c1->id)));
        $this->assertEquals(4, $DB->count_records('block_xp_log', array('courseid' => $c1->id)));
        $this->assertEquals(1, $DB->count_records('block_xp', array('courseid' => $c2->id)));
        $this->assertEquals(1, $DB->count_records('block_xp_log', array('courseid' => $c2->id)));

        $manager = block_xp_manager::get($c1->id);
        $manager->reset_data();

        $this->assertEquals(0, $DB->count_records('block_xp', array('courseid' => $c1->id)));
        $this->assertEquals(0, $DB->count_records('block_xp_log', array('courseid' => $c1->id)));
        $this->assertEquals(1, $DB->count_records('block_xp', array('courseid' => $c2->id)));
        $this->assertEquals(1, $DB->count_records('block_xp_log', array('courseid' => $c2->id)));
    }


    public function test_reset_data_with_groups() {
        global $DB;

        $c1 = $this->getDataGenerator()->create_course();
        $c2 = $this->getDataGenerator()->create_course();
        $u1 = $this->getDataGenerator()->create_user();
        $u2 = $this->getDataGenerator()->create_user();
        $g1 = $this->getDataGenerator()->create_group(array('courseid' => $c1->id));

        $this->getDataGenerator()->enrol_user($u1->id, $c1->id);
        $this->getDataGenerator()->enrol_user($u2->id, $c1->id);
        $this->getDataGenerator()->enrol_user($u1->id, $c2->id);
        $this->getDataGenerator()->create_group_member(array('groupid' => $g1->id, 'userid' => $u1->id));

        $manager = block_xp_manager::get($c1->id);
        $manager->update_config(array('enabled' => true, 'timebetweensameactions' => 0));

        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $manager->capture_event($e);
        $manager->capture_event($e);

        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c1->id));
        $manager->capture_event($e);
        $manager->capture_event($e);

        $manager = block_xp_manager::get($c2->id);
        $manager->update_config(array('enabled' => true, 'timebetweensameactions' => 0));

        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c2->id));
        $manager->capture_event($e);

        $this->assertEquals(1, $DB->count_records('block_xp', array('courseid' => $c1->id, 'userid' => $u1->id)));
        $this->assertEquals(1, $DB->count_records('block_xp', array('courseid' => $c1->id, 'userid' => $u2->id)));
        $this->assertEquals(2, $DB->count_records('block_xp_log', array('courseid' => $c1->id, 'userid' => $u1->id)));
        $this->assertEquals(2, $DB->count_records('block_xp_log', array('courseid' => $c1->id, 'userid' => $u2->id)));
        $this->assertEquals(1, $DB->count_records('block_xp', array('courseid' => $c2->id)));
        $this->assertEquals(1, $DB->count_records('block_xp_log', array('courseid' => $c2->id)));

        $manager = block_xp_manager::get($c1->id);
        $manager->reset_data($g1->id);

        $this->assertEquals(0, $DB->count_records('block_xp', array('courseid' => $c1->id, 'userid' => $u1->id)));
        $this->assertEquals(1, $DB->count_records('block_xp', array('courseid' => $c1->id, 'userid' => $u2->id)));
        $this->assertEquals(0, $DB->count_records('block_xp_log', array('courseid' => $c1->id, 'userid' => $u1->id)));
        $this->assertEquals(2, $DB->count_records('block_xp_log', array('courseid' => $c1->id, 'userid' => $u2->id)));
        $this->assertEquals(1, $DB->count_records('block_xp', array('courseid' => $c2->id)));
        $this->assertEquals(1, $DB->count_records('block_xp_log', array('courseid' => $c2->id)));
    }
}
