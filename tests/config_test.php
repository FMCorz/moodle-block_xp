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
 * Config test case.
 *
 * @package    block_xp
 * @copyright  2020 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once(__DIR__ . '/base_testcase.php');
require_once(__DIR__ . '/fixtures/events.php');

use block_xp\di;
use block_xp\local\config\config_stack;
use block_xp\local\config\filtered_config;
use block_xp\local\config\mdl_locked_config;
use block_xp\local\config\static_config;

/**
 * Config test case.
 *
 * @package    block_xp
 * @copyright  2020 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_config_testcase extends block_xp_base_testcase {

    public function test_mdl_locked_config() {
        global $DB;

        $config = new mdl_locked_config('block_xp', ['testa', 'testb']);

        // Check whether it is reported as has properly.
        $this->assertTrue($config->has('testa'));
        $this->assertTrue($config->has('testb'));
        $this->assertFalse($config->has('testc'));

        // Check that the default value is "not locked".
        $this->assertFalse($config->get('testa'));
        $this->assertFalse($config->get('testb'));

        // Check that get all returns what is expected.
        $this->assertEquals(['testa' => false, 'testb' => false], $config->get_all());

        // Check that outside modifications are reflected in object.
        set_config('testa_locked', true, 'block_xp');
        $this->assertTrue($config->get('testa'));
        $this->assertFalse($config->get('testb'));
        set_config('testa_locked', false, 'block_xp');
        $this->assertFalse($config->get('testa'));
        $this->assertFalse($config->get('testb'));

        // Check that inside modifications are reflected.
        $config->set('testb', true);
        $this->assertFalse($config->get('testa'));
        $this->assertTrue($config->get('testb'));
        $this->assertFalse((bool) get_config('block_xp', 'testa_locked'));
        $this->assertTrue((bool) get_config('block_xp', 'testb_locked'));
        $config->set('testb', false);
        $this->assertFalse($config->get('testa'));
        $this->assertFalse($config->get('testb'));
        $this->assertFalse((bool) get_config('block_xp', 'testa_locked'));
        $this->assertFalse((bool) get_config('block_xp', 'testb_locked'));

        // Check that inside many modification are reflected.
        $config->set('testa', true);
        $this->assertTrue($config->get('testa'));
        $config->set_many(['testa' => false, 'testb' => true]);
        $this->assertFalse($config->get('testa'));
        $this->assertTrue($config->get('testb'));
        $this->assertFalse((bool) get_config('block_xp', 'testa_locked'));
        $this->assertTrue((bool) get_config('block_xp', 'testb_locked'));
    }

    public function test_config_stack_with_locked() {
        $master = new static_config([
            'testa' => 'abc',
            'testb' => 'def',
            'testc' => 'ghk',
        ]);
        $overrides = new static_config([
            'testa' => 'ABC',
            'testb' => 'DEF',
            'testc' => 'GHK',
        ]);

        // Try a stack where nothing is locked.
        $locked = new mdl_locked_config('block_xp', ['testa', 'testb']);
        $filteredoverrides = new filtered_config($overrides, array_keys(array_filter($locked->get_all())));
        $this->assertEmpty($filteredoverrides->get_all());
        $this->assertFalse($filteredoverrides->has('testa'));
        $this->assertFalse($filteredoverrides->has('testb'));
        $this->assertFalse($filteredoverrides->has('testc'));

        $stack = new config_stack([$filteredoverrides, $master]);
        $this->assertEquals($master->get_all(), $stack->get_all());

        // Try a stack with one lock.
        $locked = new mdl_locked_config('block_xp', ['testa', 'testb']);
        $locked->set('testa', true);
        $filteredoverrides = new filtered_config($overrides, array_keys(array_filter($locked->get_all())));
        $this->assertNotEmpty($filteredoverrides->get_all());
        $this->assertTrue($filteredoverrides->has('testa'));
        $this->assertFalse($filteredoverrides->has('testb'));
        $this->assertFalse($filteredoverrides->has('testc'));

        $stack = new config_stack([$filteredoverrides, $master]);
        $this->assertNotEquals($master->get_all(), $stack->get_all());
        $this->assertNotEquals($master->get('testa'), $stack->get('testa'));
        $this->assertEquals($master->get('testb'), $stack->get('testb'));
        $this->assertEquals($master->get('testc'), $stack->get('testc'));
        $locked->set('testa', false);

        // Try a stack with locks and irrelevant keys.
        $locked = new mdl_locked_config('block_xp', ['testa', 'testb', 'testx']);
        $locked->set('testb', true);
        $locked->set('testx', true);
        $filteredoverrides = new filtered_config($overrides, array_keys(array_filter($locked->get_all())));
        $this->assertNotEmpty($filteredoverrides->get_all());
        $this->assertFalse($filteredoverrides->has('testa'));
        $this->assertTrue($filteredoverrides->has('testb'));
        $this->assertFalse($filteredoverrides->has('testc'));
        $this->assertFalse($filteredoverrides->has('testx'));

        $stack = new config_stack([$filteredoverrides, $master]);
        $this->assertNotEquals($master->get_all(), $stack->get_all());
        $this->assertNotEquals($master->get('testb'), $stack->get('testb'));
        $this->assertEquals($master->get('testa'), $stack->get('testa'));
        $this->assertEquals($master->get('testc'), $stack->get('testc'));
        $this->assertFalse($stack->has('testx'));

    }

}
