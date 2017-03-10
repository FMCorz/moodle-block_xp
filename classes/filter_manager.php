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
 * Filter manager.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Filter manager class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_filter_manager {

    /**
     * The block XP manager.
     *
     * @var block_xp_manager.
     */
    protected $manager;

    /**
     * Constructor.
     *
     * @param block_xp_manager $manager The XP manager.
     */
    public function __construct(block_xp_manager $manager) {
        $this->manager = $manager;
    }

    /**
     * Set cache filters to course filters and return them.
     *
     * @return array of filters.
     */
    public function get_all_filters() {
        $filters = $this->get_course_filters();
        $this->set_cache_filters($filters, $this->get_courseid());
        return $filters;
    }

    /**
     * Returns the course id
     *
     * @return course id
     */
    public function get_courseid() {
        return $this->manager->get_courseid();
    }


    /**
     * Return the points filtered for this event.
     *
     * @param \core\event\base $event The event.
     * @return int points.
     */
    public function get_points_for_event(\core\event\base $event) {
        foreach ($this->get_all_filters() as $filter) {
            if ($filter->match($event)) {
                return $filter->get_points();
            }
        }
        throw new coding_exception('The event did not match any filter.');
    }

    /**
     * Get the default filters.
     *
     * @return array Of filter objects.
     */
    public static function get_static_filters() {
        $static_filters = new block_xp_filterset_static();
        $static_filters->load();

        return $static_filters->get();
    }

    /**
     * Get the filters defined for the course.
     *
     * @return array of filter data from the DB, though properties is already json_decoded.
     */
    public function get_course_filters() {
        global $DB;
        $results = $DB->get_recordset('block_xp_filters', array('courseid' => $this->get_courseid()),
            'sortorder ASC, id ASC');
        $filters = array();
        foreach ($results as $key => $filter) {
            $filters[$filter->id] = block_xp_filter::load_from_data($filter);
        }
        $results->close();
        return $filters;
    }

    /**
     * Alias of get_course_filters()
     *
     * @return array of filters
     */
    public function get_user_filters() {
        return $this->get_course_filters();
    }

    /**
     * Used to populate default filters table with predefined filters.
     *
     * @return void
     */
    public static function save_default_filters() {
        $static_filters = new block_xp_filterset_static();
        $default_filters = new block_xp_filterset_default();

        $default_filters->import($static_filters);
    }

    /**
     * Used when adding block to course
     *
     *  @return void */
    public function copy_default_filters_to_course() {
        $default_filters = new block_xp_filterset_default();
        $course_filters = new block_xp_filterset_course($this->get_courseid());

        if ($course_filters->empty()) {
            $course_filters->import($default_filters);
        }
    }

    /**
     * Invalidate the filters cache.
     *
     * @return void
     */
    public function invalidate_filters_cache() {
        $cache = cache::make('block_xp', 'filters');
        $cache->delete('filters_' . $this->get_courseid());
    }

    /**
     * Add an array of filters to cache
     *
     * @return void
     */
    public function set_cache_filters($filters, $course_id) {
        $cache = cache::make('block_xp', 'filters');
        $key = 'filters_' . $course_id;
        if (false === ($filters = $cache->get($key))) {
            $cache->set($key, $filters);
        }
    }

}
