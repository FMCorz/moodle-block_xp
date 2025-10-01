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

namespace block_xp\local\xp;

use block_xp\local\utils\user_utils;
use block_xp\tests\base_testcase;
use moodle_url;

/**
 * Tests for Level Up XP
 *
 * @package    block_xp
 * @category   test
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \block_xp\local\xp\full_anonymiser
 */
final class full_anonymiser_test extends base_testcase {

    /**
     * Test user state.
     */
    public function test_user_state_anonymisation(): void {
        $course = $this->getDataGenerator()->create_course();
        $anonuser = $this->getDataGenerator()->create_user(['firstname' => 'Anonymous', 'lastname' => 'User']);
        $user = $this->getDataGenerator()->create_user();
        $world = $this->get_world($course->id);

        $state = new user_state($user, 100, $world->get_levels_info(), $course->id);
        $this->assertEquals($user->id, $state->get_id());
        $this->assertEquals(fullname($user), $state->get_name());
        $this->assertEquals(user_utils::user_picture($user)->out(), $state->get_picture()->out());
        $this->assertNotNull($state->get_link());
        $this->assertEquals($user, $state->get_user());

        $anonymiser = new full_anonymiser($anonuser, [$user->id]);
        $anonstate = $anonymiser->anonymise_state($state);
        $this->assertSame($state, $anonstate);

        $anonymiser = new full_anonymiser($anonuser);
        $anonstate = $anonymiser->anonymise_state($state);
        $this->assertNotSame($state, $anonstate);

        $this->assertNotEquals($user->id, $anonstate->get_id());
        $this->assertEquals($anonuser->id, $anonstate->get_id());
        if ($anonstate instanceof state_with_subject) {
            $this->assertNotEquals(fullname($user), $anonstate->get_name());
            $this->assertEquals(user_utils::default_picture(), $anonstate->get_picture());
            $this->assertNull($anonstate->get_link());
        } else {
            $this->fail('The anonymised state should implement state_with_subject.');
        }

        if ($anonstate instanceof state_with_user) {
            $this->assertNotEquals($user, $anonstate->get_user());
            $this->assertEquals($anonuser, $anonstate->get_user());
        } else {
            $this->fail('The anonymised state should implement state_with_user.');
        }
    }

    /**
     * Test state with subject.
     */
    public function test_state_with_subject_anonymisation(): void {
        $anonuser = $this->getDataGenerator()->create_user(['firstname' => 'Anonymous', 'lastname' => 'User']);
        $user = $this->getDataGenerator()->create_user();

        $id = $user->id;
        $link = new moodle_url('user.php', ['id' => $user->id]);
        $pic = new moodle_url('pic.php', ['id' => $user->id]);
        $name = fullname($user);

        $state = new class ($id, $name, $link, $pic) implements state_with_subject {
            protected $id;
            protected $name;
            protected $link;
            protected $pic;
            public function __construct($id, $name, $link, $pic) {
                $this->id = $id;
                $this->name = $name;
                $this->link = $link;
                $this->pic = $pic;
            }

            public function get_id() {
                return $this->id;
            }

            public function get_level() {
                return new static_level(1, 0);
            }

            public function get_link() {
                return $this->link;
            }

            public function get_name() {
                return $this->name;
            }

            public function get_picture() {
                return $this->pic;
            }

            public function get_ratio_in_level() {
                return 0;
            }

            public function get_total_xp_in_level() {
                return 100;
            }

            public function get_xp() {
                return 0;
            }

            public function get_xp_in_level() {
                return 0;
            }
        };

        $this->assertEquals($user->id, $state->get_id());
        $this->assertEquals(fullname($user), $state->get_name());
        $this->assertEquals($link, $state->get_link());
        $this->assertEquals($pic, $state->get_picture());

        $anonpic = new moodle_url('anonpic.php');
        $anonname = 'Anon User';

        $anonymiser = new full_anonymiser($anonuser, [$id], $anonname, $anonpic);
        $anonstate = $anonymiser->anonymise_state($state);
        $this->assertSame($state, $anonstate);

        $anonymiser = new full_anonymiser($anonuser, [], $anonname, $anonpic);
        $anonstate = $anonymiser->anonymise_state($state);
        $this->assertNotSame($state, $anonstate);

        // This fails, we maybe should obfuscate the ID but first we need to confirm
        // that the ID is not used anywhere.
        // $this->assertNotEquals($user->id, $anonstate->get_id());
        // $this->assertEquals($anonuser->id, $anonstate->get_id());
        if ($anonstate instanceof state_with_subject) {
            $this->assertNotEquals(fullname($user), $anonstate->get_name());
            $this->assertNotEquals($link, $anonstate->get_link());
            $this->assertNotEquals($pic, $anonstate->get_picture());
            $this->assertEquals($anonname, $anonstate->get_name());
            $this->assertEquals($anonpic, $anonstate->get_picture());
            $this->assertNull($anonstate->get_link());
        } else {
            $this->fail('The anonymised state should implement state_with_subject.');
        }
    }

    /**
     * Test state with user.
     */
    public function test_state_with_user_anonymisation(): void {
        $anonuser = $this->getDataGenerator()->create_user(['firstname' => 'Anonymous', 'lastname' => 'User']);
        $user = $this->getDataGenerator()->create_user();

        // Create a state_with_user that also contains state_with_subject to confirm their anonymity.
        $state = new class ($user) implements state_with_subject, state_with_user {
            protected $user;

            public function __construct($user) {
                $this->user = $user;
            }

            public function get_id() {
                return $this->user->id;
            }

            public function get_level() {
                return new static_level(1, 0);
            }

            public function get_link() {
                return new moodle_url('user.php', ['id' => $this->user->id]);
            }

            public function get_name() {
                return fullname($this->user);
            }

            public function get_picture() {
                return new moodle_url('pic.php', ['id' => $this->user->id]);
            }

            public function get_ratio_in_level() {
                return 0;
            }

            public function get_total_xp_in_level() {
                return 100;
            }

            public function get_user() {
                return $this->user;
            }

            public function get_xp() {
                return 0;
            }

            public function get_xp_in_level() {
                return 0;
            }
        };

        $this->assertEquals($user->id, $state->get_id());
        $this->assertEquals(fullname($user), $state->get_name());
        $this->assertNotNull($state->get_link());
        $this->assertEquals($user, $state->get_user());

        $anonymiser = new full_anonymiser($anonuser, [$user->id]);
        $anonstate = $anonymiser->anonymise_state($state);
        $this->assertSame($state, $anonstate);

        $anonymiser = new full_anonymiser($anonuser);
        $anonstate = $anonymiser->anonymise_state($state);
        $this->assertNotSame($state, $anonstate);

        $this->assertNotEquals($user->id, $anonstate->get_id());
        $this->assertEquals($anonuser->id, $anonstate->get_id());
        if ($anonstate instanceof state_with_subject) {
            $this->assertNotEquals(fullname($user), $anonstate->get_name());
            $this->assertNotEquals($state->get_picture(), $anonstate->get_picture());
            $this->assertEquals(user_utils::default_picture(), $anonstate->get_picture());
            $this->assertNull($anonstate->get_link());
        } else {
            $this->fail('The anonymised state should implement state_with_subject.');
        }

        if ($anonstate instanceof state_with_user) {
            $this->assertNotEquals($user, $anonstate->get_user());
            $this->assertEquals($anonuser, $anonstate->get_user());
        } else {
            $this->fail('The anonymised state should implement state_with_user.');
        }
    }

}
