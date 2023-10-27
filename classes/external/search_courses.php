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
 * External function.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\external;

use block_xp\local\utils\external_utils;
use context_course;
use context_system;
use core_text;

/**
 * External function.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class search_courses extends external_api {

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
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
    public static function execute($query) {
        global $CFG, $SITE;

        $params = self::validate_parameters(self::execute_parameters(), compact('query'));
        $query = core_text::strtolower(trim($params['query']));
        self::validate_context(context_system::instance());

        require_once($CFG->dirroot . '/course/externallib.php');
        $courses = \core_course_external::search_courses('search', $query, 0, 25)['courses'];

        if (strpos(core_text::strtolower($SITE->shortname), $query) !== false
                || strpos(core_text::strtolower($SITE->fullname), $query) !== false) {

            array_unshift($courses, array_merge((array) $SITE, [
                'displayname' => external_utils::format_string(get_course_display_name_for_list($SITE),
                    context_course::instance($SITE->id)),
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
    public static function execute_returns() {
        return new external_multiple_structure(new external_single_structure([
            'id' => new external_value(PARAM_INT),
            'fullname' => new external_value(PARAM_TEXT),
            'displayname' => new external_value(PARAM_TEXT),
            'shortname' => new external_value(PARAM_TEXT),
            'categoryid' => new external_value(PARAM_INT),
        ]));
    }

}
