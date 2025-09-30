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

namespace block_xp\local\config;

use block_xp\di;
use block_xp\tests\base_testcase;

/**
 * Tests.
 *
 * @package    block_xp
 * @category   test
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \local_xp\local\config\bulk_world_config_setter
 */
final class bulk_world_config_setter_test extends base_testcase {

    /**
     * Test bulk override.
     */
    public function test_bulk_override(): void {
        $c1 = $this->getDataGenerator()->create_course();
        $c2 = $this->getDataGenerator()->create_course();

        $cfg1 = $this->get_world($c1->id)->get_config();
        $cfg2 = $this->get_world($c2->id)->get_config();

        $cfg1->set('enableladder', 0);
        $cfg2->set('enableladder', 1);
        $cfg1->set('enableinfos', 1);
        $cfg2->set('enableinfos', 0);
        $cfg1->set('maxactionspertime', 123);
        $cfg2->set('maxactionspertime', 456);
        $cfg1->set('timeformaxactions', 3600);
        $cfg2->set('timeformaxactions', 3600);
        $cfg1->set('levelsdata', '{}');
        $cfg2->set('levelsdata', '{"invalid": true}');

        di::get('bulk_world_config_setter')->set_from(new static_config([
            'enableladder' => 1,
            'enableinfos' => 1,
            'timeformaxactions' => 7200,
            'levelsdata' => '',
        ]));

        $this->reset_container();
        $cfg1 = $this->get_world($c1->id)->get_config();
        $cfg2 = $this->get_world($c2->id)->get_config();

        $this->assertEquals(1, $cfg1->get('enableladder'));
        $this->assertEquals(1, $cfg2->get('enableladder'));
        $this->assertEquals(1, $cfg1->get('enableinfos'));
        $this->assertEquals(1, $cfg2->get('enableinfos'));
        $this->assertEquals(123, $cfg1->get('maxactionspertime'));
        $this->assertEquals(456, $cfg2->get('maxactionspertime'));
        $this->assertEquals(7200, $cfg1->get('timeformaxactions'));
        $this->assertEquals(7200, $cfg2->get('timeformaxactions'));
        $this->assertEquals('', $cfg1->get('levelsdata'));
        $this->assertEquals('', $cfg2->get('levelsdata'));
    }

    /**
     * Test bulk override with invalid keys.
     */
    public function test_bulk_override_with_invalid_keys(): void {
        $c1 = $this->getDataGenerator()->create_course();

        $cfg1 = $this->get_world($c1->id)->get_config();
        $data = $cfg1->get_all();

        di::get('bulk_world_config_setter')->set_from(new static_config([
            'blah' => 1,
            'foo' => 'abc',
            'id' => 9876,
            'courseid' => 1234,
            'contextid' => 3456,
        ]));

        $this->reset_container();
        $cfg1 = $this->get_world($c1->id)->get_config();
        $this->assertEquals($data, $cfg1->get_all());
    }

    /**
     * Test bulk override from admin defaults.
     */
    public function test_bulk_override_from_admin_defaults(): void {
        $c1 = $this->getDataGenerator()->create_course();
        $c2 = $this->getDataGenerator()->create_course();

        $cfg1 = $this->get_world($c1->id)->get_config();
        $cfg2 = $this->get_world($c2->id)->get_config();

        $cfg1->set('enableladder', 0);
        $cfg2->set('enableladder', 1);
        $cfg1->set('enableinfos', 1);
        $cfg2->set('enableinfos', 0);
        $cfg1->set('maxactionspertime', 123);
        $cfg2->set('maxactionspertime', 456);
        $cfg1->set('timeformaxactions', 3600);
        $cfg2->set('timeformaxactions', 3600);
        $cfg1->set('levelsdata', '{}');
        $cfg2->set('levelsdata', '{"invalid": true}');

        di::get('bulk_world_config_setter')->set_from_admin_defaults(new static_config([
            'enableladder' => 1,
            'enableinfos' => 1,
            'timeformaxactions' => 7200,
            'levelsdata' => '',
        ]));

        $this->reset_container();
        $cfg1 = $this->get_world($c1->id)->get_config();
        $cfg2 = $this->get_world($c2->id)->get_config();

        $this->assertEquals(1, $cfg1->get('enableladder'));
        $this->assertEquals(1, $cfg2->get('enableladder'));
        $this->assertEquals(1, $cfg1->get('enableinfos'));
        $this->assertEquals(1, $cfg2->get('enableinfos'));
        $this->assertEquals(123, $cfg1->get('maxactionspertime'));
        $this->assertEquals(456, $cfg2->get('maxactionspertime'));
        $this->assertEquals(7200, $cfg1->get('timeformaxactions'));
        $this->assertEquals(7200, $cfg2->get('timeformaxactions'));
        $this->assertEquals('{}', $cfg1->get('levelsdata'));
        $this->assertEquals('{"invalid": true}', $cfg2->get('levelsdata'));
    }

}
