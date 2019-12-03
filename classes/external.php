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

}
