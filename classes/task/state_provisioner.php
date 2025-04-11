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

namespace block_xp\task;

use block_xp\di;
use block_xp\local\xp\course_user_state_provisioner;
use context_system;
use progress_trace;
use text_progress_trace;

/**
 * State provisioner.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class state_provisioner extends \core\task\scheduled_task {

    /** @var progress_trace */
    private $logger;

    /**
     * Get name.
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskstateprovisioner', 'block_xp');
    }

    /**
     * Execute.
     */
    public function execute() {
        $config = di::get('config');
        $logger = $this->logger ?? new text_progress_trace();

        if (!$config->get('provisionstates')) {
            $logger->output('Running task despite setting [provisionstates] being disabled.');
        }

        if ($config->get('context') == CONTEXT_SYSTEM) {
            $world = di::get('context_world_factory')->get_world_from_context(context_system::instance());
            $provisioner = new course_user_state_provisioner($world->get_context(), $world->get_store(), $logger);
            $provisioner->provision_states();

        } else {
            $logger->output('Identifying courses where XP is enabled...');
            $courseids = di::get('db')->get_fieldset_select('block_xp_config', 'courseid',
                'enabled = ? AND courseid != ?', [1, SITEID]);
            $ncourses = count($courseids);
            $logger->output("Found $ncourses course(s) where XP is enabled.");

            foreach ($courseids as $courseid) {
                $world = di::get('context_world_factory')->get_world_from_context(\context_course::instance($courseid));
                $provisioner = new course_user_state_provisioner($world->get_context(), $world->get_store(), $logger);
                $provisioner->provision_states();
            }
        }
    }

    /**
     * Set the task logger.
     *
     * @param progress_trace $logger The logger.
     */
    public function set_task_logger(progress_trace $logger) {
        $this->logger = $logger;
    }

    /**
     * Enable or disable the task.
     *
     * @param bool $enabled Whether to enable the task.
     */
    public static function set_enabled($enabled) {
        $task = \core\task\manager::get_scheduled_task('\\' . static::class);
        if (!$task) {
            return;
        }
        $task->set_disabled(!$enabled);
        try {
            \core\task\manager::configure_scheduled_task($task);
        } catch (\moodle_exception $e) {
            return;
        }
    }

}
