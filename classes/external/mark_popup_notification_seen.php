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

namespace block_xp\external;

use block_xp\di;

/**
 * External function.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mark_popup_notification_seen extends external_api {
    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, '', VALUE_DEFAULT),
            'courseid' => new external_value(PARAM_INT, 'Deprecated parameter.', VALUE_DEFAULT),
            'level' => new external_value(PARAM_INT),
        ]);
    }

    /**
     * Exwecute.
     *
     * @param ?int $contextid The context ID.
     * @param ?int $courseid The course ID.
     * @param int $level The level.
     * @return bool
     */
    public static function execute($contextid, $courseid, $level) {
        global $USER;

        $params = self::validate_parameters(self::execute_parameters(), compact('contextid', 'courseid', 'level'));
        $contextid = $params['contextid'];
        $courseid = $params['courseid'];
        $level = $params['level'];

        // Pre-checks.
        if (!empty($contextid)) {
            $world = di::get('context_world_factory')->get_world_from_context(\context::instance_by_id($contextid));
        } else {
            $world = di::get('course_world_factory')->get_world($courseid);
        }

        self::validate_context($world->get_context());

        // Permission checks.
        $perms = $world->get_access_permissions();
        $perms->require_access();

        $userlevel = $world->get_store()->get_state($USER->id)->get_level()->get_level();
        $service = di::get('world_level_up_notification_service_factory')->get_for_world($world);
        $service->mark_as_notified($USER->id, $level);

        // Special case to remove 0 when we are at the same level.
        if ($level == $userlevel) {
            $service->mark_as_notified($USER->id, 0);
        }

        return true;
    }

    /**
     * External function return values.
     *
     * @return external_value
     */
    public static function execute_returns() {
        return new external_value(PARAM_BOOL);
    }
}
