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
use block_xp\local\world;
use block_xp\local\xp\admin_filter_manager;
use core_component;
use moodle_url;
use ReflectionClass;

/**
 * Base testcase class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base_testcase extends \advanced_testcase {

    /** @var bool */
    private static $fixtureautoloadregistered = false;

    /** @var \block_xp_generator */
    protected $xpgenerator;

    /**
     * PHP Unit setup method.
     *
     * This method is final not to be overridden. It was never used directly, instead
     * usage of the setup_test method was required. We maintain this behaviour for simplicity.
     */
    final public function setUp(): void {
        self::register_tests_fixtures_autoloader();
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
     * Assert log count.
     *
     * @param world $world The world.
     * @param int $expected The expected count.
     */
    protected function assert_log_count(world $world, $expected) {
        global $DB;
        $this->assertEquals($expected, $DB->count_records('block_xp_logs', ['contextid' => (int) $world->get_context()->id]));
    }

    /**
     * Assert log count for user.
     *
     * @param world $world The world.
     * @param int $userid The user ID.
     * @param int $expected The expected count.
     */
    protected function assert_log_count_for_user(world $world, $userid, $expected) {
        global $DB;
        $this->assertEquals($expected, $DB->count_records('block_xp_logs', [
            'userid' => (int) $userid,
            'contextid' => (int) $world->get_context()->id,
        ]));
    }

    /**
     * Get the frozen clock.
     *
     * This skips the test if the clock is not mockable.
     *
     * @param int|null $ts
     * @return \frozen_clock
     */
    protected function get_frozen_clock(?int $ts = null): \frozen_clock {
        if (!method_exists($this, 'mock_clock_with_frozen')) {
            $this->markTestSkipped('This test requires the ability to mock clocks.');
        }
        $this->reset_container(); // Just in case our objects cached the time object.
        return $this->mock_clock_with_frozen($ts);
    }

    /**
     * Get the incrementing clock.
     *
     * This skips the test if the clock is not mockable.
     *
     * @param int|null $starttime
     * @return \incrementing_clock
     */
    protected function get_incrementing_clock(?int $starttime = null): \incrementing_clock {
        if (!method_exists($this, 'mock_clock_with_incrementing')) {
            $this->markTestSkipped('This test requires the ability to mock clocks.');
        }
        $this->reset_container(); // Just in case our objects cached the time object.
        return $this->mock_clock_with_incrementing($starttime);
    }

    /**
     * Get instantiable classes.
     *
     * @param string $namespace The namespace relative to block_xp.
     * @param string|null $withinterface The interface the class must implement.
     * @return Generator<ReflectionClass>
     */
    protected function get_instantiable_classes($namespace, $withinterface = null) {
        $classes = array_keys(core_component::get_component_classes_in_namespace('block_xp', $namespace));
        foreach ($classes as $classname) {
            $class = new ReflectionClass($classname);
            if (!$class->isInstantiable()) {
                continue;
            } else if ($withinterface && !$class->implementsInterface($withinterface)) {
                continue;
            }
            yield $class;
        }
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
     * Get the generator.
     *
     * @return \block_xp_generator
     */
    protected function get_xp_generator() {
        if (!$this->xpgenerator) {
            $this->xpgenerator = $this->getDataGenerator()->get_plugin_generator('block_xp');
        }
        return $this->xpgenerator;
    }

    /**
     * Reset the container.
     */
    protected function reset_container() {
        \block_xp\di::set_container(new \block_xp\local\default_container());
    }

    /**
     * Restore the legacy default event rules.
     *
     * @return void
     */
    protected function restore_legacy_default_event_rules() {
        // Restore the legacy default event rules.
        $filters = admin_filter_manager::legacy_default_filters(\block_xp_filter::CATEGORY_EVENTS);
        foreach ($filters as $filter) {
            $data = $filter->export();
            $data->courseid = 0;
            $filter = \block_xp_filter::load_from_data($data);
            $filter->save();
        }
        (new admin_filter_manager(\block_xp\di::get('db')))->mark_as_customised();
    }

    /**
     * Register autoload for fixtures.
     *
     * @return void
     */
    protected static function register_tests_fixtures_autoloader(): void {
        if (self::$fixtureautoloadregistered) {
            return;
        }
        $prefixes = static::get_autoload_prefixes();
        spl_autoload_register(function ($class) use ($prefixes) {
            global $CFG;

            foreach ($prefixes as $prefix => $basepath) {
                if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
                    continue;
                }

                $relative = substr($class, strlen($prefix));
                $relative = str_replace('\\', DIRECTORY_SEPARATOR, $relative);
                $file = $CFG->dirroot . $basepath . $relative . '.php';
                if (is_readable($file)) {
                    require_once($file);
                }
                return;
            }
        }, true, true);
        self::$fixtureautoloadregistered = true;
    }

    protected static function get_autoload_prefixes() {
        return [
            'block_xp\\tests\\fixtures\\' => '/blocks/xp/tests/fixtures/',
            'block_xp\\tests\\mocks\\' => '/blocks/xp/tests/mocks/',

            // Special case for events that must belong to the event namespace.
            'block_xp\\event' => '/blocks/xp/tests/fixtures',
        ];
    }
}
