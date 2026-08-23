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
// See <https://levelup.plus>.

namespace block_xp\local\plugin;

use block_xp\di;
use block_xp\local\config\config;
use core_plugin_manager;
use curl;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

/**
 * Update checker.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class update_checker {
    /** @var config The config. */
    protected $config;

    /**
     * Constructor.
     *
     * @param config $config The config.
     */
    public function __construct(config $config) {
        $this->config = $config;
    }

    /**
     * Check for updates.
     */
    public function check() {
        if (!$this->is_check_allowed()) {
            return;
        }

        $response = $this->get_updates();
        if (!$response) {
            return;
        } else if (empty($response->notification_advised)) {
            return;
        }

        $allupdates = $response->updates ?? [];
        $notifiableupdates = $this->get_notifiable_updates($allupdates);
        if (!empty($notifiableupdates)) {
            $notification = $this->make_notification($notifiableupdates);
            $this->notify_admins($notification);
        }

        $this->remember_notified_updates($allupdates);
    }

    /**
     * Whether checking for updates is allowed.
     *
     * @return bool
     */
    protected function is_check_allowed() {
        global $CFG;

        if (!empty($CFG->disableupdatenotifications) || empty($CFG->updateautocheck)) {
            return false;
        }

        return true;
    }

    /**
     * Get updates from the API.
     *
     * @return object|null
     */
    protected function get_updates() {
        global $CFG;

        $pluginman = core_plugin_manager::instance();
        $addon = di::get('addon');
        $blockxp = $pluginman->get_plugin_info('block_xp');
        $localxp = ($addon->is_activated() || $addon->is_deactivated()) ? $pluginman->get_plugin_info('local_xp') : null;

        $data = [
            'moodle_version' => (int) $CFG->version,
            'moodle_branch' => (string) $CFG->branch,
            'block_xp_version' => (int) $blockxp->versiondisk,
            'block_xp_release' => (string) $blockxp->release,
            'local_xp_version' => $localxp ? (int) $localxp->versiondisk : null,
            'local_xp_release' => $localxp ? (string) $localxp->release : null,
        ];

        $apiroot = rtrim($this->config->get('apiroot'), '/');
        $curl = new curl();
        $curl->setHeader(['Content-Type: application/json']);
        $response = $curl->post($apiroot . '/v1/xp/updates', json_encode($data));
        $info = $curl->get_info();
        if ($curl->get_errno() || empty($info['http_code']) || (int) $info['http_code'] !== 200) {
            return null;
        }

        $response = json_decode($response);
        return is_object($response) ? $response : null;
    }

    /**
     * Get the updates which have not been notified yet.
     *
     * @param array $updates The updates.
     * @return array
     */
    protected function get_notifiable_updates($updates) {
        $notifiedkeys = array_filter(explode(',', $this->config->get('lastupdatesnoticekeys')));
        $result = [];
        foreach (['block_xp', 'local_xp'] as $component) {
            foreach ($updates as $update) {
                $key = $this->get_update_notice_key($update);
                if ($update->component === $component && !in_array($key, $notifiedkeys, true)) {
                    $result[] = $update;
                }
            }
        }
        return $result;
    }

    /**
     * Get the update notice key.
     *
     * @param object $update The update.
     * @return string
     */
    protected function get_update_notice_key($update) {
        return $update->component . ':' . $update->version;
    }

    /**
     * Make the notification.
     *
     * @param array $updates The updates.
     * @return object
     */
    protected function make_notification($updates) {
        $items = [];
        foreach ($updates as $update) {
            $pluginname = get_string('pluginname', $update->component);
            $items[] = '- **' . $pluginname . '** (' . $update->component . ') ' . $update->version;
        }
        $content = get_string('adminnoticeupdatesmessage', 'block_xp', implode(PHP_EOL, $items))
            . "\n\n----\n\n" . get_string('adminnoticefooter', 'block_xp');
        $html = markdown_to_html($content);

        return (object) [
            'subject' => get_string('adminnoticeupdatessubject', 'block_xp'),
            'plain' => html_to_text($html),
            'html' => $html,
        ];
    }

    /**
     * Notify the administrators.
     *
     * @param object $notification The notification.
     * @return void
     */
    protected function notify_admins($notification) {
        $userfrom = \core_user::get_noreply_user();
        foreach (get_admins() as $user) {
            try {
                $message = new \core\message\message();
                $message->component = 'block_xp';
                $message->name = 'adminnotice';
                $message->userfrom = $userfrom;
                $message->userto = $user;
                $message->subject = $notification->subject;
                $message->fullmessage = $notification->plain;
                $message->fullmessageformat = FORMAT_PLAIN;
                $message->fullmessagehtml = $notification->html;
                $message->notification = 1;
                if (message_send($message)) {
                    $sent = true;
                }
            } catch (\Throwable $e) {
                mtrace("Failed to send notice to {$user->username}: " . $e->getMessage());
            }
        }
    }

    /**
     * Remember the updates which were notified.
     *
     * @param array $updates The updates.
     */
    protected function remember_notified_updates($updates) {
        $keys = [];
        foreach ($updates as $update) {
            $keys[] = $this->get_update_notice_key($update);
        }
        $value = implode(',', $keys);
        $this->config->set('lastupdatesnoticekeys', $value);
    }
}
