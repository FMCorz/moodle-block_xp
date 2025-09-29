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

namespace block_xp\local\block;

use block_xp\di;
use block_xp\tests\base_testcase;
use Generator;

/**
 * Config test case.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \block_xp\local\block\default_instance_finder
 */
final class default_instance_finder_test extends base_testcase {

    /**
     * Provider.
     */
    public static function data_provider(): Generator {
        yield [[], 'sys', 0];
        yield [[], 'c1', 0];

        yield ['sys', 'sys', 1];
        yield ['c1', 'c1', 1];
        yield ['c2', 'c1', 0];
        yield ['c1', 'c2', 0];
        yield ['sys', 'c1', 0];
        yield ['c1', 'sys', 0];

        yield [['sys', 'c1'], 'sys', 1];
        yield [['c1', 'c2'], 'sys', 0];
        yield [['c1', 'c2'], 'c1', 1];

        yield [['sys', 'sys'], 'c1', 0];
        yield [['sys', 'sys'], 'sys', 2];
        yield [['c1', 'c1', 'c2'], 'c1', 2];
        yield [['c1', 'c1', 'c2'], 'c2', 1];
    }

    /**
     * Setup the blocks.
     *
     * @param string|string[] $addto
     * @return \context[] Context map.
     */
    protected function setup_blocks($addto) {
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();
        $c2 = $dg->create_course();

        $contextmap = [
            'sys' => \context_system::instance(),
            'c1'  => \context_course::instance($c1->id),
            'c2'  => \context_course::instance($c2->id),
        ];

        foreach ((array) $addto as $ctxname) {
            $this->add_block_in_context('xp', $contextmap[$ctxname]);
        }

        return $contextmap;
    }

    /**
     * Test.
     *
     * @param string[]|string $addto
     * @param string $checkin
     * @param int $ninstances
     * @dataProvider data_provider
     */
    public function test_count_instances_in_context($addto, $checkin, $ninstances): void {
        $contextmap = $this->setup_blocks($addto);
        $finder = new default_instance_finder(di::get('db'));
        $this->assertEquals($ninstances, $finder->count_instances_in_context('xp', $contextmap[$checkin]));
    }

    /**
     * Test.
     *
     * @param string[]|string $addto
     * @param string $checkin
     * @param int $ninstances
     * @dataProvider data_provider
     */
    public function test_get_instance_in_context($addto, $checkin, $ninstances): void {
        $contextmap = $this->setup_blocks($addto);
        $finder = new default_instance_finder(di::get('db'));
        $block = $finder->get_instance_in_context('xp', $contextmap[$checkin]);
        if ($ninstances === 1) {
            $this->assertNotNull($block);
            $instance = $block->instance;
            $this->assertEquals('xp', $instance->blockname);
            $this->assertEquals($contextmap[$checkin]->id, $instance->parentcontextid);
        } else {
            $this->assertNull($block);
        }
    }

    /**
     * Test.
     *
     * @param string[]|string $addto
     * @param string $checkin
     * @param int $ninstances
     * @dataProvider data_provider
     */
    public function test_has_instance_in_context($addto, $checkin, $ninstances): void {
        $contextmap = $this->setup_blocks($addto);
        $finder = new default_instance_finder(di::get('db'));
        $this->assertEquals($ninstances > 0, $finder->has_instance_in_context('xp', $contextmap[$checkin]));
    }

}
