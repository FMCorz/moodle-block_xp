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

namespace block_xp;

use block_xp\local\world;
use block_xp\local\xp\course_user_state_provisioner;
use block_xp\task\state_provisioner;
use block_xp\tests\base_testcase;
use context_course;
use context_coursecat;
use context_system;

/**
 * Tests.
 *
 * @package    block_xp
 * @category   test
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \block_xp\task\state_provisioner
 * @covers     \block_xp\local\xp\course_user_state_provisioner
 */
final class state_provisioner_test extends base_testcase {

    /**
     * Provider.
     *
     * @return array
     */
    public static function course_user_state_provisioner_with_course_provider(): array {
        return [
            'norole' => [
                'rolepermission' => null,
                'expectedexists' => false,
            ],
            'roleallow' => [
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'roleinherit' => [
                'rolepermission' => 'inherit',
                'expectedexists' => false,
            ],
            'roleprevent' => [
                'rolepermission' => 'prevent',
                'expectedexists' => false,
            ],
            'roleprohibit' => [
                'rolepermission' => 'prohibit',
                'expectedexists' => false,
            ],
        ];
    }

    /**
     * Test course user state provisioner with course.
     *
     * @dataProvider course_user_state_provisioner_with_course_provider
     * @param string|null $rolepermission
     * @param bool $expectsexists
     */
    public function test_course_user_state_provisioner_with_course(?string $rolepermission, bool $expectedexists): void {
        $dg = $this->getDataGenerator();

        $config = di::get('config');
        $config->set('context', CONTEXT_COURSE);

        $c1 = $dg->create_course();
        $w1 = $this->get_world($c1->id);
        $u1 = $dg->create_user();
        $u2 = $dg->create_user();

        if ($rolepermission) {
            $roleid = $dg->create_role(['block/xp:earnxp' => $rolepermission]);
            $dg->enrol_user($u1->id, $c1->id, $roleid);
            $dg->enrol_user($u2->id, $c1->id, $roleid);
        }

        $this->assert_state_does_not_exist($w1, $u1->id);
        $this->assert_state_does_not_exist($w1, $u2->id);

        $provisioner = new course_user_state_provisioner($w1->get_context(), $w1->get_store());
        $provisioner->provision_states();

        // Enabled with permissions.
        if ($expectedexists) {
            $this->assert_state_exists($w1, $u1->id);
        } else {
            $this->assert_state_does_not_exist($w1, $u1->id);
        }
        // If u2 was enrolled (only in 'allow' case), check its state based on expectedexists.
        // Otherwise, assert it does not exist.
        if ($rolepermission === 'allow') {
            if ($expectedexists) {
                $this->assert_state_exists($w1, $u2->id);
            } else {
                $this->assert_state_does_not_exist($w1, $u2->id);
            }
        } else {
            $this->assert_state_does_not_exist($w1, $u2->id);
        }
    }

    /**
     * Test course user state provisioner with existing state course.
     */
    public function test_course_user_state_provisioner_with_course_existing_state(): void {
        $dg = $this->getDataGenerator();

        $config = di::get('config');
        $config->set('context', CONTEXT_COURSE);

        $c1 = $dg->create_course();
        $w1 = $this->get_world($c1->id);
        $u1 = $dg->create_user();
        $u2 = $dg->create_user();

        $roleid = $dg->create_role(['block/xp:earnxp' => 'allow']);
        $dg->enrol_user($u1->id, $c1->id, $roleid);
        $dg->enrol_user($u2->id, $c1->id, $roleid);
        $w1->get_store()->increase($u2->id, 1);

        $this->assert_state_does_not_exist($w1, $u1->id);
        $this->assert_state_exists($w1, $u2->id);

        $provisioner = new course_user_state_provisioner($w1->get_context(), $w1->get_store());
        $provisioner->provision_states();

        // Enabled with permissions.
        $this->assert_state_exists($w1, $u1->id);
        $this->assert_state_exists($w1, $u2->id);
    }

    /**
     * Provider.
     *
     * @return array
     */
    public static function course_user_state_provisioner_with_system_provider(): array {
        return [
            'norole' => [
                'roleincontexts' => [],
                'rolepermission' => 'allow',
                'expectedexists' => false,
            ],
            'roleinsystem' => [
                'roleincontexts' => ['sys'],
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'roleincoursecat' => [
                'roleincontexts' => ['cat1'],
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'roleincourse' => [
                'roleincontexts' => ['c1'],
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'rolemultiple' => [
                'roleincontexts' => ['sys', 'cat1', 'c1'],
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'roleinherit' => [
                'roleincontexts' => ['sys', 'cat1', 'c1'],
                'rolepermission' => 'inherit',
                'expectedexists' => false,
            ],
            'roleprevent' => [
                'roleincontexts' => ['sys', 'cat1', 'c1'],
                'rolepermission' => 'prevent',
                'expectedexists' => false,
            ],
            'roleprohibit' => [
                'roleincontexts' => ['sys', 'cat1', 'c1'],
                'rolepermission' => 'prohibit',
                'expectedexists' => false,
            ],
        ];
    }

    /**
     * Test course user state provisioner with system.
     *
     * @dataProvider course_user_state_provisioner_with_system_provider
     * @param array $roleincontexts
     * @param string $rolepermission
     * @param bool $expectedexists
     */
    public function test_course_user_state_provisioner_with_system(array $roleincontexts, string $rolepermission,
            bool $expectedexists): void {

        $dg = $this->getDataGenerator();

        $config = di::get('config');
        $config->set('context', CONTEXT_SYSTEM);

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user(['suspended' => 1]);
        $cat1 = $dg->create_category();
        $c1 = $dg->create_course(['category' => $cat1->id]);
        $contexts = [
            'sys' => context_system::instance(),
            'c1' => context_course::instance($c1->id),
            'cat1' => context_coursecat::instance($cat1->id),
        ];

        $roleid = $dg->create_role(['block/xp:earnxp' => $rolepermission]);
        foreach ($roleincontexts as $contextname) {
            $dg->role_assign($roleid, $u1->id, $contexts[$contextname]->id);
            $dg->role_assign($roleid, $u3->id, $contexts[$contextname]->id);
        }
        $world = $this->get_world(SITEID);

        $this->assert_state_does_not_exist($world, $u1->id);
        $this->assert_state_does_not_exist($world, $u2->id);
        $this->assert_state_does_not_exist($world, $u3->id);

        $provisioner = new course_user_state_provisioner($world->get_context(), $world->get_store());
        $provisioner->provision_states();

        if ($expectedexists) {
            $this->assert_state_exists($world, $u1->id);
        } else {
            $this->assert_state_does_not_exist($world, $u1->id);
        }
        $this->assert_state_does_not_exist($world, $u2->id);
        $this->assert_state_does_not_exist($world, $u3->id);
    }

    /**
     * Provider.
     *
     * @return array
     */
    public static function task_with_course_context_provider(): array {
        return [
            'norole' => [
                'isenabled' => true,
                'rolepermission' => null,
                'expectedexists' => false,
            ],
            'roleallow' => [
                'isenabled' => true,
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'roleinherit' => [
                'isenabled' => true,
                'rolepermission' => 'inherit',
                'expectedexists' => false,
            ],
            'roleprevent' => [
                'isenabled' => true,
                'rolepermission' => 'prevent',
                'expectedexists' => false,
            ],
            'roleprohibit' => [
                'isenabled' => true,
                'rolepermission' => 'prohibit',
                'expectedexists' => false,
            ],
            'coursenotenabledroleallow' => [
                'isenabled' => false,
                'rolepermission' => 'allow',
                'expectedexists' => false,
            ],
        ];
    }

    /**
     * Test task with course context.
     *
     * @dataProvider task_with_course_context_provider
     * @param bool $provisionstates
     * @param bool $isenabled
     * @param string|null $rolepermission
     * @param bool $expectedexists
     */
    public function test_task_with_course_context(bool $isenabled, ?string $rolepermission, bool $expectedexists): void {

        $dg = $this->getDataGenerator();

        $config = di::get('config');
        $config->set('context', CONTEXT_COURSE);

        $c1 = $dg->create_course();
        $w1 = $this->get_world($c1->id);
        $w1->get_config()->set('enabled', $isenabled);
        $u1 = $dg->create_user();
        $u2 = $dg->create_user();

        if ($rolepermission) {
            $roleid = $dg->create_role(['block/xp:earnxp' => $rolepermission]);
            $dg->enrol_user($u1->id, $c1->id, $roleid);
        }

        $this->assert_state_does_not_exist($w1, $u1->id);
        $this->assert_state_does_not_exist($w1, $u2->id);

        $this->run_task();

        if ($expectedexists) {
            $this->assert_state_exists($w1, $u1->id);
        } else {
            $this->assert_state_does_not_exist($w1, $u1->id);
        }
        $this->assert_state_does_not_exist($w1, $u2->id);
    }

    /**
     * Task with system context scenario provider.
     *
     * @return array
     */
    public static function task_with_system_context_provider(): array {
        return [
            'norole' => [
                'roleincontexts' => [],
                'rolepermission' => 'allow',
                'expectedexists' => false,
            ],
            'roleinsystem' => [
                'roleincontexts' => ['sys'],
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'roleincoursecat' => [
                'roleincontexts' => ['cat1'],
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'roleincourse' => [
                'roleincontexts' => ['c1'],
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'rolemultiple' => [
                'roleincontexts' => ['sys', 'cat1', 'c1'],
                'rolepermission' => 'allow',
                'expectedexists' => true,
            ],
            'roleinherit' => [
                'roleincontexts' => ['sys', 'cat1', 'c1'],
                'rolepermission' => 'inherit',
                'expectedexists' => false,
            ],
            'roleprevent' => [
                'roleincontexts' => ['sys', 'cat1', 'c1'],
                'rolepermission' => 'prevent',
                'expectedexists' => false,
            ],
            'roleprohibit' => [
                'roleincontexts' => ['sys', 'cat1', 'c1'],
                'rolepermission' => 'prohibit',
                'expectedexists' => false,
            ],
        ];
    }

    /**
     * Test task with system context.
     *
     * @dataProvider task_with_system_context_provider
     * @param bool $provisionstates
     * @param array $roleincontexts
     * @param string $rolepermission
     * @param bool $expectedexists
     * @return void
     */
    public function test_task_with_system_context(array $roleincontexts, string $rolepermission, bool $expectedexists): void {

        $dg = $this->getDataGenerator();

        $config = di::get('config');
        $config->set('context', CONTEXT_SYSTEM);

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user(['suspended' => 1]);
        $cat1 = $dg->create_category();
        $c1 = $dg->create_course(['category' => $cat1->id]);
        $contexts = [
            'sys' => context_system::instance(),
            'c1' => context_course::instance($c1->id),
            'cat1' => context_coursecat::instance($cat1->id),
        ];

        $roleid = $dg->create_role(['block/xp:earnxp' => $rolepermission]);
        foreach ($roleincontexts as $contextname) {
            $dg->role_assign($roleid, $u1->id, $contexts[$contextname]->id);
            $dg->role_assign($roleid, $u3->id, $contexts[$contextname]->id);
        }
        $world = $this->get_world(SITEID);

        $this->assert_state_does_not_exist($world, $u1->id);
        $this->assert_state_does_not_exist($world, $u2->id);
        $this->assert_state_does_not_exist($world, $u3->id);

        $this->run_task();

        if ($expectedexists) {
            $this->assert_state_exists($world, $u1->id);
        } else {
            $this->assert_state_does_not_exist($world, $u1->id);
        }
        $this->assert_state_does_not_exist($world, $u2->id);
        $this->assert_state_does_not_exist($world, $u3->id);
    }

    /**
     * Assert that the state does not exist.
     *
     * @param world $world
     * @param int $userid
     */
    protected function assert_state_does_not_exist(world $world, int $userid) {
        $context = $world->get_context();
        $courseid = $context instanceof context_system ? SITEID : $context->instanceid;
        $this->assertFalse(di::get('db')->record_exists('block_xp', ['userid' => $userid, 'courseid' => $courseid]));
    }

    /**
     * Assert that the state exists.
     *
     * @param world $world
     * @param int $userid
     */
    protected function assert_state_exists(world $world, int $userid) {
        $context = $world->get_context();
        $courseid = $context instanceof context_system ? SITEID : $context->instanceid;
        $this->assertTrue(di::get('db')->record_exists('block_xp', ['userid' => $userid, 'courseid' => $courseid]));
    }

    /**
     * Run the task.
     */
    protected function run_task(): void {
        /** @var state_provisioner */ // @codingStandardsIgnoreLine
        $task = \core\task\manager::get_scheduled_task(state_provisioner::class);
        $task->set_task_logger(new \null_progress_trace());
        $task->execute();
    }
}
