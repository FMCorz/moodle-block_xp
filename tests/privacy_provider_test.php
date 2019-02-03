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
 * Test privacy provider.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once(__DIR__ . '/base_testcase.php');
require_once(__DIR__ . '/fixtures/events.php');

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\writer;
use block_xp\di;
use block_xp\local\config\course_world_config;
use block_xp\local\controller\promo_controller;
use block_xp\local\controller\ladder_controller;
use block_xp\privacy\provider;

/**
 * Privacy provider testcase.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_privacy_provider_testcase extends block_xp_base_testcase {

    public function setUp() {
        global $CFG;
        if (!class_exists('core_privacy\manager')) {
            $this->markTestSkipped('Moodle versions does not support privacy subsystem.');
        }
        parent::setUp();
    }

    protected function get_world($courseid) {
        $world = di::get('course_world_factory')->get_world($courseid);
        $world->get_config()->set('enabled', 1);
        $world->get_config()->set('enablecheatguard', 0);
        $world->get_config()->set('defaultfilters', course_world_config::DEFAULT_FILTERS_MISSING);
        return $world;
    }

    public function test_get_metadata() {
        $data = provider::get_metadata(new collection('block_xp'));
        $this->assertCount(7, $data->get_collection());
    }

    public function test_export_user_prefs() {
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $u1 = $dg->create_user();
        $u2 = $dg->create_user();

        $noticeindic = di::get('user_notice_indicator');
        $genericindic = di::get('user_generic_indicator');

        $genericindic->set_user_flag($u1->id, promo_controller::SEEN_FLAG, 1);
        $genericindic->set_user_flag($u1->id, ladder_controller::PAGE_SIZE_FLAG, 50);
        $noticeindic->set_user_flag($u1->id, 'block_intro_' . $c1->id, 1);
        $noticeindic->set_user_flag($u2->id, 'block_intro_' . $c1->id, 1);
        set_user_preference('block_xp_notify_level_up_' . $c2->id, 1, $u1->id);
        set_user_preference('block_xp_notify_level_up_' . $c1->id, 1, $u2->id);
        set_user_preference('block_xp_notices', 1, $u1->id);

        provider::export_user_preferences($u1->id);

        $writer = writer::with_context(context_system::instance());
        $prefs = $writer->get_user_preferences('block_xp');
        $prefkeys = array_keys((array) $prefs);

        $this->assertTrue(in_array('block_xp_notices', $prefkeys));
        $this->assertTrue(in_array('block_xp-generic-' . promo_controller::SEEN_FLAG, $prefkeys));
        $this->assertTrue(in_array('block_xp-generic-' . ladder_controller::PAGE_SIZE_FLAG, $prefkeys));
        $this->assertTrue(in_array('block_xp_notify_level_up_' . $c2->id, $prefkeys));
        $this->assertFalse(in_array('block_xp_notify_level_up_' . $c1->id, $prefkeys));
        $this->assertTrue(in_array('block_xp-notice-block_intro_' . $c1->id, $prefkeys));
        $this->assertFalse(in_array('block_xp-notice-block_intro_' . $c2->id, $prefkeys));
    }

    public function test_get_contexts_for_userid() {
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $c3 = $dg->create_course();
        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();

        // Set the system first to create a context_system context.
        $config = di::get('config');
        $config->set('context', CONTEXT_SYSTEM);

        $world = $this->get_world(SITEID);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);

        // Set back to course mode to get data on both sides.
        $this->reset_container(); // We should not have to do this really...
        $config = di::get('config');
        $config->set('context', CONTEXT_COURSE);

        $world = $this->get_world($c1->id);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);

        $world = $this->get_world($c2->id);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c2->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c2->id));
        $strategy->collect_event($e);

        $world = $this->get_world($c3->id);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c3->id));
        $strategy->collect_event($e);

        $contextlist = provider::get_contexts_for_userid($u1->id);
        $this->assert_contextlist_equals($contextlist, [
            context_system::instance()->id,
            context_course::instance($c1->id)->id,
            context_course::instance($c2->id)->id,
        ]);

        $contextlist = provider::get_contexts_for_userid($u2->id);
        $this->assert_contextlist_equals($contextlist, [
            context_course::instance($c2->id)->id,
            context_course::instance($c3->id)->id,
        ]);

        $contextlist = provider::get_contexts_for_userid($u3->id);
        $this->assert_contextlist_equals($contextlist, []);
    }

    public function test_delete_data_for_all_users_in_context() {
        $db = di::get('db');
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $u1 = $dg->create_user();
        $u2 = $dg->create_user();

        $world = $this->get_world($c1->id);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        set_user_preference('block_xp_notify_level_up_' . $c1->id, 1, $u1->id);
        set_user_preference('block_xp_notify_level_up_' . $c1->id, 1, $u2->id);

        $this->assertNotEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertNotEquals(0, $world->get_store()->get_state($u2->id)->get_xp());
        $this->assertTrue($db->record_exists('block_xp_log', ['courseid' => $c1->id]));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c1->id, null, $u1->id));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c1->id, null, $u2->id));

        $world = $this->get_world($c2->id);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c2->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c2->id));
        $strategy->collect_event($e);
        set_user_preference('block_xp_notify_level_up_' . $c2->id, 1, $u1->id);
        set_user_preference('block_xp_notify_level_up_' . $c2->id, 1, $u2->id);

        $this->assertNotEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertNotEquals(0, $world->get_store()->get_state($u2->id)->get_xp());
        $this->assertTrue($db->record_exists('block_xp_log', ['courseid' => $c2->id]));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c2->id, null, $u1->id));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c2->id, null, $u2->id));

        provider::delete_data_for_all_users_in_context(context_course::instance($c1->id));

        $world = $this->get_world($c1->id);
        $this->assertEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertEquals(0, $world->get_store()->get_state($u2->id)->get_xp());
        $this->assertFalse($db->record_exists('block_xp_log', ['courseid' => $c1->id]));
        $this->assertNull(get_user_preferences('block_xp_notify_level_up_' . $c1->id, null, $u1->id));
        $this->assertNull(get_user_preferences('block_xp_notify_level_up_' . $c1->id, null, $u2->id));

        $world = $this->get_world($c2->id);
        $this->assertNotEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertNotEquals(0, $world->get_store()->get_state($u2->id)->get_xp());
        $this->assertTrue($db->record_exists('block_xp_log', ['courseid' => $c2->id]));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c2->id, null, $u1->id));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c2->id, null, $u2->id));
    }

    public function test_delete_data_for_user() {
        $db = di::get('db');
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $u1 = $dg->create_user();
        $u2 = $dg->create_user();

        $world = $this->get_world($c1->id);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        set_user_preference('block_xp_notify_level_up_' . $c1->id, 1, $u1->id);
        set_user_preference('block_xp_notify_level_up_' . $c1->id, 1, $u2->id);

        $this->assertNotEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertNotEquals(0, $world->get_store()->get_state($u2->id)->get_xp());
        $this->assertTrue($db->record_exists('block_xp_log', ['courseid' => $c1->id]));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c1->id, null, $u1->id));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c1->id, null, $u2->id));

        $world = $this->get_world($c2->id);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c2->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c2->id));
        $strategy->collect_event($e);
        set_user_preference('block_xp_notify_level_up_' . $c2->id, 1, $u1->id);
        set_user_preference('block_xp_notify_level_up_' . $c2->id, 1, $u2->id);

        $this->assertNotEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertNotEquals(0, $world->get_store()->get_state($u2->id)->get_xp());
        $this->assertTrue($db->record_exists('block_xp_log', ['courseid' => $c2->id]));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c2->id, null, $u1->id));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c2->id, null, $u2->id));

        $contextlist = new approved_contextlist($u1, 'block_xp', [context_course::instance($c1->id)->id]);
        provider::delete_data_for_user($contextlist);

        $world = $this->get_world($c1->id);
        $this->assertEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertNotEquals(0, $world->get_store()->get_state($u2->id)->get_xp());
        $this->assertFalse($db->record_exists('block_xp_log', ['courseid' => $c1->id, 'userid' => $u1->id]));
        $this->assertNull(get_user_preferences('block_xp_notify_level_up_' . $c1->id, null, $u1->id));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c1->id, null, $u2->id));

        $world = $this->get_world($c2->id);
        $this->assertNotEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertNotEquals(0, $world->get_store()->get_state($u2->id)->get_xp());
        $this->assertTrue($db->record_exists('block_xp_log', ['courseid' => $c2->id, 'userid' => $u1->id]));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c2->id, null, $u1->id));
        $this->assertNotNull(get_user_preferences('block_xp_notify_level_up_' . $c2->id, null, $u2->id));
    }

    public function test_export_data_for_user() {
        $db = di::get('db');
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $u1 = $dg->create_user();
        $u2 = $dg->create_user();

        $world = $this->get_world($c1->id);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c1->id));
        $strategy->collect_event($e);

        $this->assertNotEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertNotEquals(0, $world->get_store()->get_state($u2->id)->get_xp());
        $userstate = $world->get_store()->get_state($u1->id);

        $world = $this->get_world($c2->id);
        $strategy = $world->get_collection_strategy();
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u1->id, 'courseid' => $c2->id));
        $strategy->collect_event($e);
        $e = \block_xp\event\something_happened::mock(array('crud' => 'c', 'userid' => $u2->id, 'courseid' => $c2->id));
        $strategy->collect_event($e);

        $this->assertNotEquals(0, $world->get_store()->get_state($u1->id)->get_xp());
        $this->assertNotEquals(0, $world->get_store()->get_state($u2->id)->get_xp());

        $contextlist = new approved_contextlist($u1, 'block_xp', [context_course::instance($c1->id)->id]);
        provider::export_user_data($contextlist);

        $writer = writer::with_context(context_course::instance($c1->id));
        $logs = $writer->get_data([get_string('pluginname', 'block_xp'), get_string('privacy:path:logs', 'block_xp')]);
        $level = $writer->get_data([get_string('pluginname', 'block_xp'), get_string('privacy:path:level', 'block_xp')]);

        $this->assertNotEmpty($level);
        $this->assertEquals($userstate->get_xp(), $level->points);
        $this->assertEquals($userstate->get_level()->get_level(), $level->level);
        $this->assertEquals($u1->id, $level->userid);

        $this->assertNotEmpty($logs);
        $this->assertCount(6, $logs->data);
        foreach ($logs->data as $log) {
            $this->assertEquals('block_xp: something happened', $log->eventname);
            $this->assertEquals(45, $log->points);
            $this->assertEquals($u1->id, $log->userid);
        }
    }

    protected function assert_contextlist_equals($contextlist, $expectedids) {
        $contextids = array_map('intval', $contextlist->get_contextids());
        sort($contextids);
        sort($expectedids);
        $this->assertEquals($expectedids, $contextids);
    }
}
