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
class create_rule extends external_api {

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT),
            'points' => new external_value(PARAM_INT),
            'type' => new external_single_structure([
                'name' => new external_value(PARAM_ALPHANUMEXT),
                'char1' => new external_value(PARAM_RAW),
            ]),
            'filter' => new external_single_structure([
                'name' => new external_value(PARAM_ALPHANUMEXT),
                'courseid' => new external_value(PARAM_INT, '', VALUE_OPTIONAL),
                'cmid' => new external_value(PARAM_INT, '', VALUE_OPTIONAL),
                'int1' => new external_value(PARAM_INT, '', VALUE_OPTIONAL),
                'char1' => new external_value(PARAM_RAW, '', VALUE_OPTIONAL),
            ]),
            'childcontextid' => new external_value(PARAM_INT, '', VALUE_DEFAULT, 0),
        ]);
    }

    /**
     * External function.
     *
     * @param int $contextid The context ID.
     * @param int $points The points.
     * @param array $type The type details.
     * @param array $filter The filter details.
     * @param int $childcontextid The child context ID.
     * @return int
     */
    public static function execute($contextid, $points, $type, $filter, $childcontextid = 0) {
        $params = self::validate_parameters(self::execute_parameters(), compact('contextid',
            'type',
            'points',
            'filter',
            'childcontextid'
        ));
        $contextid = $params['contextid'];
        $type = $params['type'];
        $points = $params['points'];
        $filter = $params['filter'];
        $childcontextid = $params['childcontextid'];

        $world = self::require_manage_permissions_and_get_world($contextid);
        $isadmin = empty($world);

        // Validate the child context.
        $context = $world ? $world->get_context() : context_system::instance();
        $childcontext = null;
        if ($childcontextid && !$isadmin) {
            $childcontext = context::instance_by_id($childcontextid);
            if (!$context->is_parent_of($childcontext, false)) {
                throw new \moodle_exception('invalidcontext', 'core_error');
            } else if ($childcontext->contextlevel != CONTEXT_COURSE) {
                throw new \moodle_exception('invalidcontext', 'core_error');
            }
            $modinfo = get_fast_modinfo($childcontext->instanceid);
            if (!can_access_course($modinfo->get_course(), null, '', true)) {
                throw new \moodle_exception('invalidcontext', 'core_error');
            }
        }
        $effectivecontext = $childcontext ?? $context;

        $typeresolver = di::get('rule_type_resolver');
        $filterhandler = di::get('rule_filter_handler');
        $filtermediator = di::get('rule_filter_mediator');

        // Validate type exists.
        $typeinst = $typeresolver->get_type($type['name']);
        if (!$typeinst) {
            throw new \moodle_exception('invaliddata', 'core_error');
        }

        // Validate filter exists.
        $filterinst = $filterhandler->get_filter($filter['name']);
        if (!$filterinst) {
            throw new \coding_exception('unknownfilter');
        }

        // Validate filter compatibility.
        if (!in_array((int) $effectivecontext->contextlevel, $filterinst->get_compatible_context_levels())) {
            throw new \moodle_exception('invaliddata', 'core_error');
        } else if ($isadmin && !$filterinst->is_compatible_with_admin()) {
            throw new \moodle_exception('invaliddata', 'core_error');
        }

        // Validate type is compatible with filter.
        if (!in_array($filter['name'], $filtermediator->get_compatible_filter_names($typeinst))) {
            throw new \moodle_exception('invaliddata', 'core_error');
        }

        // Validate multiple.
        if (!$filterinst->is_multiple_allowed()) {
            $testoptions = ['type' => $type['name'], 'filter' => $filter['name']];
            if (!$isadmin) {
                $manager = di::get('world_rule_manager_factory')->get_rule_manager($world);
                $count = $manager->count_rules($childcontext, $testoptions);
            } else {
                $count = di::get('admin_rule_manager')->count_rules($testoptions);
            }
            if ($count > 0) {
                throw new \coding_exception('multipleentriesnotpermitted');
            }
        }

        if (!$isadmin) {
            $manager = $manager ?? di::get('world_rule_manager_factory')->get_rule_manager($world);
            $manager->detach();
        }

        // Save the record.
        $db = di::get('db');
        $ruleid = $db->insert_record('block_xp_rule', (object) [
            'contextid' => $isadmin ? 0 : $contextid,
            'childcontextid' => $childcontext ? $childcontext->id : 0,
            'points' => max(0, min(9999999, $points)),
            'type' => $type['name'],
            'filter' => $filter['name'],
            'filtercourseid' => $filter['courseid'] ?? null,
            'filtercmid' => $filter['cmid'] ?? null,
            'filterint1' => $filter['int1'] ?? null,
            'filterchar1' => $filter['char1'] ?? null,
        ]);

        return $ruleid;
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
        return new external_value(PARAM_INT);
    }

}
