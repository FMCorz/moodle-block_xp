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
 * External.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/course/externallib.php');

use block_xp\local\xp\algo_levels_info;
use context_course;
use context_system;
use core_text;
use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;

/**
 * External class.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends external_api {

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function search_courses_parameters() {
        return new external_function_parameters([
            'query' => new external_value(PARAM_RAW),
        ]);
    }

    /**
     * Search courses.
     *
     * The only reason this exists is to include the frontpage in the search.
     *
     * @param string $query The query.
     * @return array
     */
    public static function search_courses($query) {
        global $SITE;

        $params = self::validate_parameters(self::search_courses_parameters(), compact('query'));
        $query = core_text::strtolower(trim($params['query']));
        self::validate_context(context_system::instance());

        $courses = \core_course_external::search_courses('search', $query, 0, 25)['courses'];

        if (strpos(core_text::strtolower($SITE->shortname), $query) !== false
                || strpos(core_text::strtolower($SITE->fullname), $query) !== false) {

            array_unshift($courses, array_merge((array) $SITE, [
                'displayname' => external_format_string(get_course_display_name_for_list($SITE),
                    context_course::instance($SITE->id))
            ]));
        }

        return array_values(array_map(function($course) {
            return [
                'id' => $course['id'],
                'fullname' => $course['fullname'],
                'displayname' => $course['displayname'],
                'shortname' => $course['shortname'],
                'categoryid' => $course['categoryid'],
            ];
        }, $courses));
    }

    /**
     * External function return values.
     *
     * @return external_value
     */
    public static function search_courses_returns() {
        return new external_multiple_structure(new external_single_structure([
            'id' => new external_value(PARAM_INT),
            'fullname' => new external_value(PARAM_TEXT),
            'displayname' => new external_value(PARAM_TEXT),
            'shortname' => new external_value(PARAM_TEXT),
            'categoryid' => new external_value(PARAM_INT),
        ]));
    }

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function search_modules_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT),
            'query' => new external_value(PARAM_RAW),
        ]);
    }

    /**
     * Search modules.
     *
     * @param int $courseid The course ID.
     * @param string $query The query.
     * @return array
     */
    public static function search_modules($courseid, $query) {
        $params = self::validate_parameters(self::search_modules_parameters(), compact('courseid', 'query'));
        $courseid = $params['courseid'];
        $query = core_text::strtolower(trim($params['query']));

        // We fetch the world, but do not update the $courseid as per world::get_courseid, because
        // if we are using the plugin for the whole site, then users should be able to search in
        // any course. And if we're using the plugin per course, then they need permissions within
        // that course.
        $world = di::get('course_world_factory')->get_world($courseid);
        self::validate_context($world->get_context());
        $world->get_access_permissions()->require_manage();

        $modinfo = get_fast_modinfo($courseid);
        $courseformat = course_get_format($courseid);
        $sections = [];

        foreach ($modinfo->get_sections() as $sectionnum => $cmids) {

            $modules = [];
            foreach ($cmids as $cmid) {
                $cm = $modinfo->get_cm($cmid);
                $name = $cm->get_formatted_name();
                $comparablename = core_text::strtolower($name);

                if ($query == '*' || strpos($comparablename, $query) !== false) {
                    $modules[] = [
                        'cmid' => $cm->id,
                        'contextid' => $cm->context->id,
                        'name' => $cm->get_formatted_name()
                    ];
                }
            }

            if (!empty($modules)) {
                $sections[] = [
                    'name' => $courseformat->get_section_name($sectionnum),
                    'modules' => $modules
                ];
            }
        }

        return $sections;
    }

    /**
     * External function return values.
     *
     * @return external_value
     */
    public static function search_modules_returns() {
        return new external_multiple_structure(new external_single_structure([
            'name' => new external_value(PARAM_RAW, 'The section name'),
            'modules' => new external_multiple_structure(new external_single_structure([
                'cmid' => new external_value(PARAM_INT),
                'contextid' => new external_value(PARAM_INT),
                'name' => new external_value(PARAM_RAW),
            ]))
        ]));
    }

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function set_default_levels_info_parameters() {
        return new external_function_parameters([
            'levels' => new external_multiple_structure(new external_single_structure([
                'level' => new external_value(PARAM_INT),
                'xprequired' => new external_value(PARAM_INT),
                'name' => new external_value(PARAM_NOTAGS, '', VALUE_DEFAULT, ''),
                'description' => new external_value(PARAM_NOTAGS, '', VALUE_DEFAULT, ''),
            ])),
            'algo' => new external_single_structure([
                'enabled' => new external_value(PARAM_BOOL),
                'base' => new external_value(PARAM_INT),
                'coef' => new external_value(PARAM_FLOAT),
            ])
        ]);
    }

    /**
     * Allow AJAX use.
     *
     * @return true
     */
    public static function set_default_levels_info_is_allowed_from_ajax() {
        return true;
    }

    /**
     * External function.
     *
     * @param int $courseid The course ID.
     * @return object
     */
    public static function set_default_levels_info($levels, $algo) {
        global $USER;
        $params = self::validate_parameters(self::set_default_levels_info_parameters(), compact('levels', 'algo'));
        extract($params);

        // Permission checks.
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('moodle/site:config', $context);

        $levelsinfo = static::clean_levels_info_data($levels, $algo);
        $config = di::get('config');
        $config->set('levelsdata', json_encode($levelsinfo->jsonSerialize()));

        return (object) ['success' => true];
    }

    /**
     * External function return definition.
     *
     * @return external_description
     */
    public static function set_default_levels_info_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL)
        ]);
    }

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function set_levels_info_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT),
            'levels' => new external_multiple_structure(new external_single_structure([
                'level' => new external_value(PARAM_INT),
                'xprequired' => new external_value(PARAM_INT),
                'name' => new external_value(PARAM_NOTAGS, '', VALUE_DEFAULT, ''),
                'description' => new external_value(PARAM_NOTAGS, '', VALUE_DEFAULT, ''),
            ])),
            'algo' => new external_single_structure([
                'enabled' => new external_value(PARAM_BOOL),
                'base' => new external_value(PARAM_INT),
                'coef' => new external_value(PARAM_FLOAT),
            ])
        ]);
    }

    /**
     * Allow AJAX use.
     *
     * @return true
     */
    public static function set_levels_info_is_allowed_from_ajax() {
        return true;
    }

    /**
     * External function.
     *
     * @param int $courseid The course ID.
     * @return object
     */
    public static function set_levels_info($courseid, $levels, $algo) {
        global $USER;
        $params = self::validate_parameters(self::set_levels_info_parameters(), compact('courseid', 'levels', 'algo'));
        extract($params);

        // Pre-checks.
        $worldfactory = di::get('course_world_factory');
        $world = $worldfactory->get_world($courseid);
        $config = $world->get_config();
        $courseid = $world->get_courseid(); // Ensure that we get the real course ID.
        self::validate_context($world->get_context());

        // Permission checks.
        $perms = $world->get_access_permissions();
        $perms->require_manage();

        // Sort levels.
        usort($levels, function($l1, $l2) {
            return $l1['level'] - $l2['level'];
        });

        // Pseudo validation, we basically ignore errors.
        if (count($levels) < 2 || count($levels) > 99) {
            $levelsinfo = algo_levels_info::make_from_defaults();

        } else {
            $lastpts = null;
            $levelsdata = array_reduce(array_keys($levels), function($carry, $key) use ($levels, &$lastpts) {
                $level = $levels[$key];
                $levelnb = $level['level'];

                if ($lastpts === null) {
                    $xp = 0;
                } else {
                    $xp = min(max($lastpts + 1, $level['xprequired']), PHP_INT_MAX);
                }

                $carry['xp'][$levelnb] = $xp;
                if (!empty($level['name'])) {
                    $carry['name'][$levelnb] = core_text::substr($level['name'], 0, 40);
                }
                if (!empty($level['description'])) {
                    $carry['desc'][$levelnb] = core_text::substr($level['description'], 0, 255);
                }

                $lastpts = $xp;
                return $carry;
            }, ['xp' => [], 'name' => [], 'desc' => []]);

            // Normalise data if it's incorrect.
            $algo['base'] = min(max(1, $algo['base']), PHP_INT_MAX);
            $algo['coef'] = min(max(1, $algo['coef']), PHP_INT_MAX);

            $levelsinfo = new algo_levels_info([
                'xp' => $levelsdata['xp'],
                'desc' => $levelsdata['desc'],
                'name' => $levelsdata['name'],
                'base' => $algo['base'],
                'coef' => $algo['coef'],
                'usealgo' => $algo['enabled'],
            ]);
        }

        // Serialise and encode within the config object?
        // Or better if the levels info can save itself?
        $config->set('levelsdata', json_encode($levelsinfo->jsonSerialize()));

        // Reset the levels in the store, this is very specific to that store.
        // We probably could write that better in a different manner...
        $store = $world->get_store();
        if ($store instanceof \block_xp\local\xp\course_user_state_store) {
            $store->recalculate_levels();
        }

        return (object) ['success' => true];
    }

    /**
     * External function return definition.
     *
     * @return external_description
     */
    public static function set_levels_info_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL)
        ]);
    }

    protected static function clean_levels_info_data($levels, $algo) {
        // Sort levels.
        usort($levels, function($l1, $l2) {
            return $l1['level'] - $l2['level'];
        });

        // Pseudo validation, we basically ignore errors.
        if (count($levels) < 2 || count($levels) > 99) {
            $levelsinfo = algo_levels_info::make_from_defaults();

        } else {
            $lastpts = null;
            $levelsdata = array_reduce(array_keys($levels), function($carry, $key) use ($levels, &$lastpts) {
                $level = $levels[$key];
                $levelnb = $level['level'];

                if ($lastpts === null) {
                    $xp = 0;
                } else {
                    $xp = min(max($lastpts + 1, $level['xprequired']), PHP_INT_MAX);
                }

                $carry['xp'][$levelnb] = $xp;
                if (!empty($level['name'])) {
                    $carry['name'][$levelnb] = core_text::substr($level['name'], 0, 40);
                }
                if (!empty($level['description'])) {
                    $carry['desc'][$levelnb] = core_text::substr($level['description'], 0, 255);
                }

                $lastpts = $xp;
                return $carry;
            }, ['xp' => [], 'name' => [], 'desc' => []]);

            // Normalise data if it's incorrect.
            $algo['base'] = min(max(1, $algo['base']), PHP_INT_MAX);
            $algo['coef'] = min(max(1, $algo['coef']), PHP_INT_MAX);

            $levelsinfo = new algo_levels_info([
                'xp' => $levelsdata['xp'],
                'desc' => $levelsdata['desc'],
                'name' => $levelsdata['name'],
                'base' => $algo['base'],
                'coef' => $algo['coef'],
                'usealgo' => $algo['enabled'],
            ]);
        }

        return $levelsinfo;
    }
}
