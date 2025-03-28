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
use core\task\adhoc_task;

/**
 * Task.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class post_deactivation_adhoc extends adhoc_task {

    public function execute() {
        $addon = di::get('addon');
        if (!$addon->is_deactivated()) {
            return;
        }

        $config = di::get('config');
        if (!$config->get('adminnotices')) {
            mtrace('Admin notices are disabled, skipping deactivation notification.');
            return;
        }

        $pluginman = \core_plugin_manager::instance();
        $blockxp = $pluginman->get_plugin_info('block_xp');
        $localxp = $pluginman->get_plugin_info('local_xp');

        // That's odd, bail!
        if (!$blockxp || !$localxp) {
            mtrace('Plugins were not located, abandoning!');
            return;
        }

        // Only send once per major version pair.
        $key = admin_notices::get_version_pair_key($blockxp, $localxp);
        if ($config->get('lastdeactivationnoticekey') === $key) {
            return;
        }

        $contenthtml = markdown_to_html(get_string('adminnoticeaddondeactivatedmessage', 'block_xp', [
            'blockxpversion' => $blockxp->release . ' (' . $blockxp->versiondb . ')',
            'localxpversion' => $localxp->release . ' (' . $localxp->versiondb . ')',
            'localxpversionexpected' => $addon->get_expected_release(),
        ]));
        $contentplain = html_to_text($contenthtml);
        $userfrom = \core_user::get_noreply_user();

        $users = get_admins();
        foreach ($users as $user) {
            try {
                $message = new \core\message\message();
                $message->component = 'block_xp';
                $message->name = 'adminnotice';
                $message->userfrom = $userfrom;
                $message->userto = $user;
                $message->subject = get_string('adminnoticeaddondeactivatedsubject', 'block_xp');
                $message->fullmessage = $contentplain;
                $message->fullmessageformat = FORMAT_PLAIN;
                $message->fullmessagehtml = $contenthtml;
                $message->notification = 1;
                message_send($message);
            } catch (\Throwable $e) {
                mtrace("Failed to send notice to {$user->username}: " . $e->getMessage());
            }
        }

        $config->set('lastdeactivationnoticekey', $key);
    }

    /**
     * Schedule this task.
     */
    public static function schedule(): void {
        $task = new static();
        $task->set_component('block_xp');
        \core\task\manager::queue_adhoc_task($task, true);
    }

}
