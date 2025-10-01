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
 * Test.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \block_xp\local\block\course_block
 */
final class instance_test extends base_testcase {


    /**
     * Setup the blocks.
     *
     * @param string|string[] $addto
     * @return array Context map.
     */
    protected function setup_blocks($addto) {
        global $CFG;
        require_once($CFG->dirroot . '/my/lib.php');

        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $u1 = $dg->create_user();

        $blocks = [];
        $contextmap = [
            'sys' => \context_system::instance(),
            'fp' => \context_course::instance(SITEID),
            'c1' => \context_course::instance($c1->id),
            'c2' => \context_course::instance($c2->id),
            'u1' => \context_user::instance($u1->id),
        ];

        $mypage = my_get_page(null, MY_PAGE_PRIVATE);
        foreach ((array) $addto as $ctxname) {
            $pagetypepattern = null;
            $subpagepattern = null;

            if ($ctxname === 'my') {
                $ctxname = 'sys';
                $pagetypepattern = 'my-index';
                $subpagepattern = $mypage->id;
            }

            $blocks[] = $this->add_block_in_context('xp', $contextmap[$ctxname], $pagetypepattern, $subpagepattern);
        }

        return ['contextmap' => $contextmap, 'blocks' => $blocks];
    }

    /**
     * Provider.
     */
    public static function enabled_when_block_added_provider(): Generator {
        yield [CONTEXT_SYSTEM, 'sys', 'sys', true];
        yield [CONTEXT_SYSTEM, 'fp', 'sys', true];
        yield [CONTEXT_SYSTEM, 'my', 'sys', true];
        yield [CONTEXT_SYSTEM, 'c1', 'sys', true];

        yield [CONTEXT_COURSE, 'sys', 'fp', true];
        yield [CONTEXT_COURSE, 'sys', 'c1', false];
        yield [CONTEXT_COURSE, 'fp', 'fp', true];
        yield [CONTEXT_COURSE, 'c1', 'c1', true];
        yield [CONTEXT_COURSE, 'c1', 'c2', false];
    }

    /**
     * Test enabling when block is added.
     *
     * @param int $mode
     * @param string|string[] $addto
     * @param string $checkin
     * @param bool $enabled
     * @dataProvider enabled_when_block_added_provider
     */
    public function test_enabled_when_block_added($mode, $addto, $checkin, $enabled): void {
        $adminconfig = di::get('config');
        $adminconfig->set('context', $mode);

        ['contextmap' => $contextmap] = $this->setup_blocks($addto);
        $config = $this->get_world($contextmap[$checkin]->instanceid)->get_config();
        $this->assertEquals($enabled, (bool) $config->get('enabled'));
    }

    /**
     * Provider.
     */
    public static function disabled_when_block_removed_provider(): Generator {
        yield [CONTEXT_SYSTEM, ['fp'], [], 'sys', true];
        yield [CONTEXT_SYSTEM, ['fp'], 0, 'sys', false];
        yield [CONTEXT_SYSTEM, ['my'], 0, 'sys', false];
        yield [CONTEXT_SYSTEM, ['fp', 'u1'], 1, 'sys', true];
        yield [CONTEXT_SYSTEM, ['fp', 'my'], 0, 'sys', true];
        yield [CONTEXT_SYSTEM, ['fp', 'my'], 1, 'sys', true];
        yield [CONTEXT_SYSTEM, ['fp', 'my'], [0, 1], 'sys', false];

        yield [CONTEXT_COURSE, ['c1'], [], 'c1', true];
        yield [CONTEXT_COURSE, ['c1'], [0], 'c1', false];
    }

    /**
     * Test enabling when block is added.
     *
     * @param int $mode
     * @param string|string[] $addto
     * @param int|int[] $removeidx
     * @param string $checkin
     * @param bool $enabled
     * @dataProvider disabled_when_block_removed_provider
     */
    public function test_disabled_when_block_removed($mode, $addto, $removeidx, $checkin, $enabled): void {
        $adminconfig = di::get('config');
        $adminconfig->set('context', $mode);

        ['contextmap' => $contextmap, 'blocks' => $blocks] = $this->setup_blocks($addto);
        foreach ((array) $removeidx as $idx) {
            blocks_delete_instance($blocks[$idx]->instance);
        }

        $config = $this->get_world($contextmap[$checkin]->instanceid)->get_config();
        $this->assertEquals($enabled, (bool) $config->get('enabled'));
    }

}
