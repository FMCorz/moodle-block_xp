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
 * Leaderboard tests.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once(__DIR__ . '/base_testcase.php');

use block_xp\local\leaderboard\anonymised_leaderboard;
use block_xp\local\leaderboard\course_user_leaderboard;
use block_xp\local\leaderboard\neighboured_leaderboard;
use block_xp\local\leaderboard\relative_ranker;
use block_xp\local\leaderboard\null_ranker;
use block_xp\local\sql\limit;

/**
 * Leaderboard testcase.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_leaderboard_testcase extends block_xp_base_testcase {

    protected function get_leaderboard($world, $groupid = 0, $ranker = null) {
        global $DB, $USER;
        return new course_user_leaderboard(
            $DB,
            $world->get_levels_info(),
            $world->get_courseid(),
            ['rank', 'fullname'],
            $ranker,
            $groupid
        );
    }

    protected function get_world($courseid) {
        return \block_xp\di::get('course_world_factory')->get_world($courseid);
    }

    /**
     * Basic test of the leaderboard.
     */
    public function test_basic_leaderboard() {
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();
        $c2 = $dg->create_course();

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();
        $u4 = $dg->create_user();
        $u5 = $dg->create_user();
        $u6 = $dg->create_user();
        $u7 = $dg->create_user();
        $u8 = $dg->create_user();

        $world1 = $this->get_world($c1->id);
        $store1 = $world1->get_store();
        $store1->set($u1->id, 100);
        $store1->set($u2->id, 120);
        $store1->set($u3->id, 130);
        $world2 = $this->get_world($c2->id);
        $store2 = $world2->get_store();
        $store2->set($u4->id, 140);

        $lb = $this->get_leaderboard($world1);

        $this->assertEquals(3, $lb->get_count());
        $this->assertEquals(2, $lb->get_position($u1->id));
        $this->assertEquals(1, $lb->get_position($u2->id));
        $this->assertEquals(0, $lb->get_position($u3->id));
        $this->assertEquals(3, $lb->get_rank($u1->id)->get_rank());
        $this->assertEquals(2, $lb->get_rank($u2->id)->get_rank());
        $this->assertEquals(1, $lb->get_rank($u3->id)->get_rank());

        $this->assertNull($lb->get_position($u4->id));
        $this->assertNull($lb->get_rank($u4->id));

        $store1->set($u5->id, 10);
        $store1->set($u6->id, 20);
        $store1->set($u7->id, 20);
        $store1->set($u8->id, 30);

        // Testing limits.
        $ranking = $lb->get_ranking(new limit(0, 0));
        $this->assertCount(7, $ranking);
        $expected = [
            [$u3, 1],
            [$u2, 2],
            [$u1, 3],
            [$u8, 4],
            [$u6, 5],
            [$u7, 5],
            [$u5, 7],
        ];
        $this->assert_ranking($ranking, $expected);

        $ranking = $lb->get_ranking(new limit(2, 0));
        $this->assertCount(2, $ranking);
        $expected = [
            [$u3, 1],
            [$u2, 2],
        ];
        $this->assert_ranking($ranking, $expected);

        $ranking = $lb->get_ranking(new limit(3, 2));
        $this->assertCount(3, $ranking);
        $expected = [
            [$u1, 3],
            [$u8, 4],
            [$u6, 5],
        ];
        $this->assert_ranking($ranking, $expected);

        $ranking = $lb->get_ranking(new limit(3, 10));
        $this->assertCount(0, $ranking);
        $expected = [];
        $this->assert_ranking($ranking, $expected);
    }

    /**
     * Basic test of the leaderboard.
     */
    public function test_group_leaderboard() {
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();
        $c2 = $dg->create_course();

        $g1 = $dg->create_group(['courseid' => $c1->id]);
        $g2 = $dg->create_group(['courseid' => $c1->id]);
        $g3 = $dg->create_group(['courseid' => $c2->id]);

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();
        $u4 = $dg->create_user();
        $u5 = $dg->create_user();
        $u6 = $dg->create_user();
        $u7 = $dg->create_user();
        $u8 = $dg->create_user();

        $dg->enrol_user($u1->id, $c1->id);
        $dg->enrol_user($u2->id, $c1->id);
        $dg->enrol_user($u3->id, $c1->id);
        $dg->enrol_user($u4->id, $c1->id);
        $dg->enrol_user($u5->id, $c1->id);
        $dg->enrol_user($u6->id, $c1->id);
        $dg->enrol_user($u7->id, $c1->id);
        $dg->enrol_user($u8->id, $c1->id);

        $dg->create_group_member(['groupid' => $g1->id, 'userid' => $u1->id]);
        $dg->create_group_member(['groupid' => $g1->id, 'userid' => $u2->id]);
        $dg->create_group_member(['groupid' => $g1->id, 'userid' => $u3->id]);
        $dg->create_group_member(['groupid' => $g1->id, 'userid' => $u4->id]);

        $dg->create_group_member(['groupid' => $g2->id, 'userid' => $u4->id]);
        $dg->create_group_member(['groupid' => $g2->id, 'userid' => $u5->id]);
        $dg->create_group_member(['groupid' => $g2->id, 'userid' => $u6->id]);

        $world1 = $this->get_world($c1->id);
        $store1 = $world1->get_store();
        $store1->set($u1->id, 10);
        $store1->set($u2->id, 20);
        $store1->set($u3->id, 30);
        $store1->set($u4->id, 40);
        $store1->set($u5->id, 50);
        $store1->set($u6->id, 60);
        $store1->set($u7->id, 70);
        $store1->set($u8->id, 80);

        $lb = $this->get_leaderboard($world1, 0);
        $this->assertEquals(8, $lb->get_count());
        $this->assertEquals(8, $lb->get_rank($u1->id)->get_rank());
        $this->assertEquals(1, $lb->get_rank($u8->id)->get_rank());

        $lb = $this->get_leaderboard($world1, $g3->id);
        $this->assertEquals(0, $lb->get_count());
        $this->assertEquals(null, $lb->get_rank($u1->id));
        $this->assertEquals(null, $lb->get_rank($u8->id));

        $lb = $this->get_leaderboard($world1, $g1->id);
        $this->assertEquals(4, $lb->get_count());
        $this->assertEquals(4, $lb->get_rank($u1->id)->get_rank());
        $this->assertEquals(1, $lb->get_rank($u4->id)->get_rank());
        $this->assertEquals(null, $lb->get_rank($u8->id));
        $this->assert_ranking($lb->get_ranking(new limit(0, 0)), [[$u4, 1], [$u3, 2], [$u2, 3], [$u1, 4]]);

        $lb = $this->get_leaderboard($world1, $g2->id);
        $this->assertEquals(3, $lb->get_count());
        $this->assertEquals(null, $lb->get_rank($u1->id));
        $this->assertEquals(3, $lb->get_rank($u4->id)->get_rank());
        $this->assertEquals(1, $lb->get_rank($u6->id)->get_rank());
        $this->assertEquals(null, $lb->get_rank($u8->id));
        $this->assert_ranking($lb->get_ranking(new limit(0, 0)), [[$u6, 1], [$u5, 2], [$u4, 3]]);
    }

    /**
     * Anonymised leaderboard.
     */
    public function test_anonymised_leaderboard() {
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();

        $world1 = $this->get_world($c1->id);
        $store1 = $world1->get_store();
        $store1->set($u1->id, 100);
        $store1->set($u2->id, 120);
        $store1->set($u3->id, 130);

        $lb = $this->get_leaderboard($world1);
        $this->assertEquals($u1->firstname, $lb->get_rank($u1->id)->get_state()->get_user()->firstname);
        $this->assertEquals($u2->firstname, $lb->get_rank($u2->id)->get_state()->get_user()->firstname);
        $this->assertEquals($u3->firstname, $lb->get_rank($u3->id)->get_state()->get_user()->firstname);

        $guest = guest_user();
        $alb = new anonymised_leaderboard($lb, $world1->get_levels_info(), $guest, [$u2->id]);
        $this->assertEquals($guest->firstname, $alb->get_rank($u1->id)->get_state()->get_user()->firstname);
        $this->assertEquals($u2->firstname, $alb->get_rank($u2->id)->get_state()->get_user()->firstname);
        $this->assertEquals($guest->firstname, $alb->get_rank($u3->id)->get_state()->get_user()->firstname);
        $this->assert_ranking($alb->get_ranking(new limit(0, 0)), [[$guest, 1], [$u2, 2], [$guest, 3]]);
    }

    /**
     * Neighboured leaderboard.
     */
    public function test_neighboured_leaderboard() {
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();
        $u4 = $dg->create_user();
        $u5 = $dg->create_user();
        $u6 = $dg->create_user();
        $u7 = $dg->create_user();
        $u8 = $dg->create_user();

        $world1 = $this->get_world($c1->id);
        $store1 = $world1->get_store();
        $store1->set($u5->id, 20);
        $store1->set($u6->id, 30);
        $store1->set($u7->id, 40);
        $store1->set($u1->id, 100);
        $store1->set($u4->id, 110);
        $store1->set($u2->id, 120);
        $store1->set($u3->id, 130);

        $lb = $this->get_leaderboard($world1, 0);
        $nlb = new neighboured_leaderboard($lb, $u1->id, 2);
        $this->assertEquals(5, $nlb->get_count());
        $this->assertEquals(2, $nlb->get_position($u1->id));
        $this->assertEquals(null, $nlb->get_position($u2->id));
        $this->assertEquals(null, $nlb->get_position($u8->id));
        $this->assertEquals(4, $nlb->get_rank($u1->id)->get_rank());
        $this->assertEquals(null, $nlb->get_rank($u2->id));
        $this->assertEquals(null, $nlb->get_rank($u8->id));
        $ranking = $nlb->get_ranking(new limit(0, 0));
        $this->assertCount(5, $ranking);
        $expected = [
            [$u2, 2],
            [$u4, 3],
            [$u1, 4],
            [$u7, 5],
            [$u6, 6],
        ];
        $this->assert_ranking($ranking, $expected);

        // Relative to the first person.
        $nlb = new neighboured_leaderboard($lb, $u3->id, 2);
        $ranking = $nlb->get_ranking(new limit(0, 0));
        $expected = [
            [$u3, 1],
            [$u2, 2],
            [$u4, 3],
        ];
        $this->assert_ranking($ranking, $expected);

        // Relative to the second person.
        $nlb = new neighboured_leaderboard($lb, $u2->id, 2);
        $ranking = $nlb->get_ranking(new limit(0, 0));
        $expected = [
            [$u3, 1],
            [$u2, 2],
            [$u4, 3],
            [$u1, 4],
        ];
        $this->assert_ranking($ranking, $expected);

        // Relative to the second last.
        $nlb = new neighboured_leaderboard($lb, $u6->id, 2);
        $ranking = $nlb->get_ranking(new limit(0, 0));
        $expected = [
            [$u1, 4],
            [$u7, 5],
            [$u6, 6],
            [$u5, 7],
        ];
        $this->assert_ranking($ranking, $expected);

        // Relative to the last.
        $nlb = new neighboured_leaderboard($lb, $u5->id, 2);
        $ranking = $nlb->get_ranking(new limit(0, 0));
        $expected = [
            [$u7, 5],
            [$u6, 6],
            [$u5, 7],
        ];
        $this->assert_ranking($ranking, $expected);
    }

    public function test_relative_ranker() {
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();

        $world1 = $this->get_world($c1->id);
        $store1 = $world1->get_store();
        $store1->set($u1->id, 100);
        $store1->set($u2->id, 120);
        $store1->set($u3->id, 130);

        $lb = $this->get_leaderboard($world1, 0, new relative_ranker($store1->get_state($u2->id)));
        $this->assertEquals(0, $lb->get_rank($u2->id)->get_rank());
        $this->assertEquals(-20, $lb->get_rank($u1->id)->get_rank());
        $this->assertEquals(10, $lb->get_rank($u3->id)->get_rank());
        $expected = [[$u3, 10], [$u2, 0], [$u1, -20]];
        $this->assert_ranking($lb->get_ranking(new limit(0, 0)), $expected);

        $lb = $this->get_leaderboard($world1, 0, new relative_ranker());
        $this->assertEquals(0, $lb->get_rank($u2->id)->get_rank());
        $this->assertEquals(0, $lb->get_rank($u1->id)->get_rank());
        $this->assertEquals(0, $lb->get_rank($u3->id)->get_rank());
        $expected = [[$u3, 0], [$u2, -10], [$u1, -30]];
        $this->assert_ranking($lb->get_ranking(new limit(0, 0)), $expected);
    }

    public function test_null_ranker() {
        $dg = $this->getDataGenerator();
        $c1 = $dg->create_course();

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();

        $world1 = $this->get_world($c1->id);
        $store1 = $world1->get_store();
        $store1->set($u1->id, 100);
        $store1->set($u2->id, 120);
        $store1->set($u3->id, 130);

        $lb = $this->get_leaderboard($world1, 0, new null_ranker());
        $this->assertEquals(0, $lb->get_rank($u2->id)->get_rank());
        $this->assertEquals(0, $lb->get_rank($u1->id)->get_rank());
        $this->assertEquals(0, $lb->get_rank($u3->id)->get_rank());
        $expected = [[$u3, 0], [$u2, 0], [$u1, 0]];
        $this->assert_ranking($lb->get_ranking(new limit(0, 0)), $expected);
    }

    protected function assert_ranking($ranking, array $expected) {
        $i = 0;
        foreach ($ranking as $rank) {
            $this->assertEquals($expected[$i][0]->id, $rank->get_state()->get_id(), $i);
            $this->assertEquals($expected[$i][1], $rank->get_rank(), $i);
            $i++;
        }
        $this->assertEquals($i, count($expected));
    }

}
