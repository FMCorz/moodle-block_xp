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

use block_xp\local\config\config_stack;
use block_xp\local\config\filtered_config;
use block_xp\local\config\mdl_locked_config;
use block_xp\local\config\static_config;
use block_xp\local\config\table_row_config;

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

    public function test_filtered_config() {
        $data = [
            'testa' => 'abc',
            'testb' => 'def',
            'testc' => 'ghk',
        ];
        $master = new static_config($data);

        // Test filtering nothing.
        $config = new filtered_config($master);
        $this->assertTrue($config->has('testa'));
        $this->assertTrue($config->has('testb'));
        $this->assertTrue($config->has('testc'));
        $this->assertEquals($data, $config->get_all());
        $this->assertEquals('abc', $config->get('testa'));
        $this->assertEquals('def', $config->get('testb'));
        $this->assertEquals('ghk', $config->get('testc'));

        // Test filtering allowed.
        $config = new filtered_config($master, ['testb', 'testc']);
        $this->assertFalse($config->has('testa'));
        $this->assertTrue($config->has('testb'));
        $this->assertTrue($config->has('testc'));
        $this->assertEquals(['testb' => 'def', 'testc' => 'ghk'], $config->get_all());
        $this->assertEquals('def', $config->get('testb'));
        $this->assertEquals('ghk', $config->get('testc'));
        try {
            $this->assertEquals('abc', $config->get('testa'));
        } catch (coding_exception $e) {}

        // Test filtering excluded.
        $config = new filtered_config($master, null, ['testa']);
        $this->assertFalse($config->has('testa'));
        $this->assertTrue($config->has('testb'));
        $this->assertTrue($config->has('testc'));
        $this->assertEquals(['testb' => 'def', 'testc' => 'ghk'], $config->get_all());
        $this->assertEquals('def', $config->get('testb'));
        $this->assertEquals('ghk', $config->get('testc'));
        try {
            $this->assertEquals('abc', $config->get('testa'));
        } catch (coding_exception $e) {}

        // Test filtering allowed and excluded.
        $config = new filtered_config($master, ['testa', 'testb'], ['testa']);
        $this->assertFalse($config->has('testa'));
        $this->assertTrue($config->has('testb'));
        $this->assertFalse($config->has('testc'));
        $this->assertEquals(['testb' => 'def'], $config->get_all());
        $this->assertEquals('def', $config->get('testb'));
        try {
            $this->assertEquals('abc', $config->get('testa'));
        } catch (coding_exception $e) {}
        try {
            $this->assertEquals('ghk', $config->get('testc'));
        } catch (coding_exception $e) {}
    }

    public function test_table_row_config() {
        global $DB;
        $this->make_config_table();

        $defaults = new static_config([
            'char1' => 'default_char1',
            'char2' => 'default_char2',
            'text1' => 'default_text1',
            'text2' => 'default_text2',
            'int1' => 111,
            'int2' => 222,
        ]);

        // Fallback on default when record does not exist.
        $this->assertFalse($DB->record_exists('phpunit_block_xp_config', ['courseid' => 1]));
        $config = new table_row_config($DB, 'phpunit_block_xp_config', $defaults, ['courseid' => 1]);
        $this->assertEquals('default_char1', $config->get('char1'));
        $this->assertEquals('default_char2', $config->get('char2'));
        $this->assertEquals('default_text1', $config->get('text1'));
        $this->assertEquals('default_text2', $config->get('text2'));
        $this->assertEquals(111, $config->get('int1'));
        $this->assertEquals(222, $config->get('int2'));
        $this->assertEquals([
            'char1' => 'default_char1',
            'char2' => 'default_char2',
            'text1' => 'default_text1',
            'text2' => 'default_text2',
            'int1' => 111,
            'int2' => 222,
        ], $config->get_all());
        $this->assertFalse($DB->record_exists('phpunit_block_xp_config', ['courseid' => 1]));

        // Setting a value will write to the database.
        $config->set('int1', 333);
        $this->assertEquals('default_char1', $config->get('char1'));
        $this->assertEquals('default_char2', $config->get('char2'));
        $this->assertEquals('default_text1', $config->get('text1'));
        $this->assertEquals('default_text2', $config->get('text2'));
        $this->assertEquals(333, $config->get('int1'));
        $this->assertEquals(222, $config->get('int2'));
        $this->assertEquals([
            'char1' => 'default_char1',
            'char2' => 'default_char2',
            'text1' => 'default_text1',
            'text2' => 'default_text2',
            'int1' => 333,
            'int2' => 222,
        ], $config->get_all());
        $this->assertTrue($DB->record_exists('phpunit_block_xp_config', ['courseid' => 1]));
        $this->assertEquals(333, $DB->get_field('phpunit_block_xp_config', 'int1', ['courseid' => 1]));

        // Validate that the value set is loaded with a new object.
        $config = new table_row_config($DB, 'phpunit_block_xp_config', $defaults, ['courseid' => 1]);
        $this->assertEquals(333, $config->get('int1'));

        // Validate that overriding a value works when DB record exists.
        $config->set('int2', 444);
        $this->assertEquals(333, $config->get('int1'));
        $this->assertEquals(444, $config->get('int2'));
        $this->assertEquals(444, $DB->get_field('phpunit_block_xp_config', 'int2', ['courseid' => 1]));
        $config = new table_row_config($DB, 'phpunit_block_xp_config', $defaults, ['courseid' => 1]);
        $this->assertEquals(444, $config->get('int2'));

        // Validate that the conditions work.
        $config = new table_row_config($DB, 'phpunit_block_xp_config', $defaults, ['courseid' => 1]);
        $this->assertNotEquals($defaults->get_all(), $config->get_all());
        $config = new table_row_config($DB, 'phpunit_block_xp_config', $defaults, ['courseid' => 2]);
        $this->assertEquals($defaults->get_all(), $config->get_all());
    }

    public function test_table_row_config_with_null() {
        global $DB;
        $this->make_config_table();

        $defaults = new static_config([
            'char1' => 'default_char1',
            'char2' => 'default_char2',
            'text1' => 'default_text1',
            'text2' => 'default_text2',
            'int1' => 111,
            'int2' => 222,
        ]);

        // Test the creation of a partial record (with null values).
        $DB->insert_record('phpunit_block_xp_config', ['courseid' => 1, 'int1' => 11, 'char1' => 'hh', 'text1' => 'ww']);
        $config = new table_row_config($DB, 'phpunit_block_xp_config', $defaults, ['courseid' => 1]);
        $this->assertNull($config->get('int2'));
        $this->assertNull($config->get('char2'));
        $this->assertNull($config->get('text2'));
        $this->assertEquals([
            'char1' => 'hh',
            'char2' => null,
            'text1' => 'ww',
            'text2' => null,
            'int1' => 11,
            'int2' => null,
        ], $config->get_all());

        // Test fallback on null.
        $config = new table_row_config($DB, 'phpunit_block_xp_config', $defaults, ['courseid' => 1], true);
        $this->assertEquals($defaults->get('int2'), $config->get('int2'));
        $this->assertEquals($defaults->get('char2'), $config->get('char2'));
        $this->assertEquals($defaults->get('text2'), $config->get('text2'));
        $this->assertEquals([
            'char1' => 'hh',
            'char2' => 'default_char2',
            'text1' => 'ww',
            'text2' => 'default_text2',
            'int1' => 11,
            'int2' => 222,
        ], $config->get_all());

        // Test fallback on null only works when default has key.
        $partialdefaults = new static_config([
            'char1' => 'default_char1',
            'char2' => 'default_char2',
            'text1' => 'default_text1',
            'text2' => 'default_text2',
            'int1' => 111,
        ]);
        $config = new table_row_config($DB, 'phpunit_block_xp_config', $partialdefaults, ['courseid' => 1], true);
        $this->assertEquals(null, $config->get('int2'));
        $this->assertEquals($partialdefaults->get('char2'), $config->get('char2'));
        $this->assertEquals($partialdefaults->get('text2'), $config->get('text2'));
        $this->assertEquals([
            'char1' => 'hh',
            'char2' => 'default_char2',
            'text1' => 'ww',
            'text2' => 'default_text2',
            'int1' => 11,
            'int2' => null,
        ], $config->get_all());
    }

    /**
     * Make the config table.
     */
    protected function make_config_table() {
        global $DB;
        $dbman = $DB->get_manager();

        $table = new xmldb_table('phpunit_block_xp_config');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('char1', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('char2', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        $table->add_field('text1', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('text2', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('int1', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('int2', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        $dbman->create_table($table);
    }

}
