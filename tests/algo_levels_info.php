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
 * Test case.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp;
defined('MOODLE_INTERNAL') || die();

use block_xp\local\xp\algo_levels_info;
use block_xp\tests\base_testcase;

/**
 * Test case.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class algo_levels_info_test extends base_testcase {

    /**
     * Test default.
     *
     * @covers \block_xp\local\xp\algo_levels_info::get_xp_with_algo
     */
    public function test_get_xp_with_algo_default() {
        $result = algo_levels_info::get_xp_with_algo(10, 120, 1.3);
        $this->assertEquals([
            1 => 0,
            2 => 120,
            3 => 276,
            4 => 479,
            5 => 742,
            6 => 1085,
            7 => 1531,
            8 => 2110,
            9 => 2863,
            10 => 3842,
        ], $result);

        $result = algo_levels_info::get_xp_with_algo(10, 120, 1);
        $this->assertEquals([
            1 => 0,
            2 => 120,
            3 => 240,
            4 => 360,
            5 => 480,
            6 => 600,
            7 => 720,
            8 => 840,
            9 => 960,
            10 => 1080,
        ], $result);
    }

    /**
     * Test relative.
     *
     * @covers \block_xp\local\xp\algo_levels_info::get_xp_with_algo
     */
    public function test_get_xp_with_algo_relative_method() {
        $result = algo_levels_info::get_xp_with_algo(10, 120, 1.3, 'relative');
        $this->assertEquals([
            1 => 0,
            2 => 120,
            3 => 276,
            4 => 479,
            5 => 742,
            6 => 1085,
            7 => 1531,
            8 => 2110,
            9 => 2863,
            10 => 3842,
        ], $result);

        $result = algo_levels_info::get_xp_with_algo(10, 120, 1, 'relative');
        $this->assertEquals([
            1 => 0,
            2 => 120,
            3 => 240,
            4 => 360,
            5 => 480,
            6 => 600,
            7 => 720,
            8 => 840,
            9 => 960,
            10 => 1080,
        ], $result);
    }

    /**
     * Test flat.
     *
     * @covers \block_xp\local\xp\algo_levels_info::get_xp_with_algo
     */
    public function test_get_xp_with_algo_flat_method() {
        $result = algo_levels_info::get_xp_with_algo(10, 120, 1, 'flat');
        $this->assertEquals([
            1 => 0,
            2 => 120,
            3 => 240,
            4 => 360,
            5 => 480,
            6 => 600,
            7 => 720,
            8 => 840,
            9 => 960,
            10 => 1080,
        ], $result);
        $result = algo_levels_info::get_xp_with_algo(5, 50, 1, 'flat');
        $this->assertEquals([
            1 => 0,
            2 => 50,
            3 => 100,
            4 => 150,
            5 => 200,
        ], $result);
    }

    /**
     * Test linear.
     *
     * @covers \block_xp\local\xp\algo_levels_info::get_xp_with_algo
     */
    public function test_get_xp_with_algo_linear_method() {
        $result = algo_levels_info::get_xp_with_algo(10, 100, 1, 'linear', 20);
        $this->assertEquals([
            1 => 0,
            2 => 100,
            3 => 210,
            4 => 330,
            5 => 460,
            6 => 600,
            7 => 750,
            8 => 910,
            9 => 1080,
            10 => 1260,
        ], $result);
        $result = algo_levels_info::get_xp_with_algo(5, 500, 1, 'linear', 50);
        $this->assertEquals([
            1 => 0,
            2 => 500,
            3 => 1050,
            4 => 1650,
            5 => 2300,
        ], $result);
    }

}
