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

namespace block_xp\external;

use block_xp\di;
use context;
use context_system;

/**
 * External function.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delete_rule extends external_api {

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT),
        ]);
    }

    /**
     * External function.
     *
     * @param int $id The rule ID.
     * @return bool
     */
    public static function execute($id) {
        $params = self::validate_parameters(self::execute_parameters(), compact('id'));
        $id = $params['id'];

        $db = di::get('db');
        $rule = $db->get_record('block_xp_rule', ['id' => $id], '*', MUST_EXIST);
        $world = self::require_manage_permissions_and_get_world((int) $rule->contextid);
        if ($world) {
            $manager = di::get('world_rule_manager_factory')->get_rule_manager($world);
            $manager->detach();
            $manager->delete_rule($id);
        } else {
            di::get('admin_rule_manager')->delete_rule($id);
        }
        return true;
    }

    /**
     * Require manage permissions for the given context.
     *
     * @param int $contextid The context ID, or 0 for admin defaults.
     * @return ?\block_xp\local\world
     */
    protected static function require_manage_permissions_and_get_world($contextid) {
        if (!$contextid) {
            $context = context_system::instance();
            self::validate_context($context);
            require_capability('moodle/site:config', $context);
            return;
        }

        $worldfactory = di::get('context_world_factory');
        $world = $worldfactory->get_world_from_context(context::instance_by_id($contextid));
        self::validate_context($world->get_context());
        $world->get_access_permissions()->require_manage();
        return $world;
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
