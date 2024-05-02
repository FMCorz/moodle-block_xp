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

/**
 * External function.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
class get_rules extends external_api {

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'kind' => new external_value(PARAM_ALPHANUMEXT, ''),
            'contextid' => new external_value(PARAM_INT),
            'childcontextid' => new external_value(PARAM_INT, '', VALUE_DEFAULT, null),
        ]);
    }

    /**
     * External function.
     *
     * @param string $kind The kind of rule.
     * @param int $contextid The context ID.
     * @param int $childcontextid The child context ID.
     * @return object[]
     */
    public static function execute($kind, $contextid, $childcontextid = null) {
        $params = self::validate_parameters(self::execute_parameters(), compact('kind', 'contextid', 'childcontextid'));
        $kind = $params['kind'];
        $contextid = $params['contextid'];
        $childcontextid = $params['childcontextid'] ?? 0;

        $world = self::require_manage_permissions_and_get_world($contextid);
        $isadmin = empty($world);

        $completiontypes = ['cm_completion', 'course_completion', 'section_completion'];

        if ($isadmin) {
            $rules = di::get('admin_rule_manager')->get_rules();
        } else {
            $context = $world->get_context();
            $childcontext = null;
            if ($childcontextid) {
                $childcontext = \context::instance_by_id($childcontextid);
                if (!$context->is_parent_of($childcontext, false)) {
                    throw new \moodle_exception('invalidcontext');
                }
            }

            $manager = di::get('world_rule_manager_factory')->get_rule_manager($world);
            $rules = $manager->get_rules($childcontext);
        }

        if ($kind === 'completion') {
            $rules = array_values(array_filter($rules, function ($rule) use ($completiontypes) {
                return in_array($rule->get_type_name(), $completiontypes);
            }));
        } else {
            $rules = array_values(array_filter($rules, function ($rule) use ($completiontypes) {
                return !in_array($rule->get_type_name(), $completiontypes);
            }));
        }

        $rules = di::get('rule_sorter')->sort($rules);
        $ruleserializer = di::get('serializer_factory')->get_rule_serializer();
        return array_values(array_map(function ($instance) use ($ruleserializer) {
            return $ruleserializer->serialize($instance);
        }, $rules));
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
        return new external_multiple_structure(
            new external_single_structure([
                'id' => new external_value(PARAM_INT),
                'points' => new external_value(PARAM_INT),
                'typename' => new external_value(PARAM_ALPHANUMEXT),
                'filtername' => new external_value(PARAM_ALPHANUMEXT),
                'label' => new external_value(PARAM_RAW),
                'limit' => new external_single_structure([
                    'max' => new external_value(PARAM_INT),
                    'timewindow' => new external_value(PARAM_INT),
                    'scope' => new external_value(PARAM_INT),
                ], '', VALUE_DEFAULT, null, NULL_ALLOWED),
                'repeatlimit' => new external_single_structure([
                    'max' => new external_value(PARAM_INT),
                    'timewindow' => new external_value(PARAM_INT),
                    'scope' => new external_value(PARAM_INT),
                ], '', VALUE_DEFAULT, null, NULL_ALLOWED),
            ])
        );
    }

}
