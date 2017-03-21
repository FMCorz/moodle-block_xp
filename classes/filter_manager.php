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

    protected $courseid;

    protected $filterset;
    /**
     * Constructor.
     *
     * @param block_xp_manager $manager The XP manager.
     */
    public function __construct($courseid = 0) {
        $this->courseid = $courseid;
    }

    /**
     * Get all the filter objects.
     *
     * Do not reorder this array, it is ordered by priority.
     *
     * @return array of filters.
     */
    public function get_all_filters() {
        $cache = cache::make('block_xp', 'filters');
        $key = 'filters_' . $this->get_courseid();
        if (false === ($filters = $cache->get($key))) {
            $filters = $this->get_course_filters();
            $cache->set($key, $filters);
        }
        return $filters;
    }

    /**
     * Returns the course id
     *
     * @return course id
     */
    public function get_courseid() {
        return $this->courseid;
    }


    /**
     * Return the points filtered for this event.
     *
     * @param \core\event\base $event The event.
     * @return int points.
     */
    public function get_points_for_event(\core\event\base $event) {
        foreach ($this->get_all_filters() as $filter) { // get()
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
        return (new block_xp_filterset_static())->get();
    }

    /**
     * Get the array of filters defined for the course.
     *
     * @return array of filter data from the DB, though properties is already json_decoded.
     */
    public function get_course_filters() {
        return $this->get_filterset();
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
        $staticfilters = new block_xp_filterset_static();
        $defaultfilters = new block_xp_filterset_default();
        $defaultfilters->delete_all();
        $defaultfilters->append($staticfilters);
    }

    /**
     * Used when adding block to course
     *
     * @return void */
    public function copy_default_filters() {
        $defaultfilters = new block_xp_filterset_default();
        $this->get_filterset()->append($defaultfilters);
        $this->invalidate_filters_cache();
    }

    /**
     * Append default filters to course.
     *
     * @param int $courseid
     * @return void
     */
    public static function copy_default_filters_to_course(int $courseid) {
        $defaultfilters = new block_xp_filterset_default();
        $coursefilters = new block_xp_filterset_course($courseid);
        $coursefilters->append($defaultfilters);
    }

    /**
     * Append default filters to all courses.
     *
     * @return void
     */
    public static function append_default_filters_to_courses() {
        global $DB;

        $records = $DB->get_records('block_xp_config');
        foreach($records as $record) {
            self::copy_default_filters_to_course($record->courseid);
        }
    }

    /**
     * Invalidate the filters cache.
     *
     */
    public function invalidate_filters_cache() {
        $cache = cache::make('block_xp', 'filters');
        $cache->delete('filters_' . $this->get_courseid());
    }

    /**
     * Get block_xp_filterset subclass depending on courseid.
     * Default filterset has courseid = 0.
     *
     * @return block_xp_filterset_course|block_xp_filterset_default
     */
    public function get_filterset() {
        if (isset($this->filterset)) {
            return $this->filterset;
        }

        // filterset lazy loading
        if ($this->courseid == 0) {
            $this->filterset = new block_xp_filterset_default();
        }
        else {
            $this->filterset = new block_xp_filterset_course($this->courseid);
        }
        return $this->filterset;
    }

    /**
     *  Get ordered array of filters
     *
     * @return block_xp_filterset_course[]|block_xp_filterset_default[]
     */
    public function get() {
        $filtersetdata = array();
        foreach ($this->get_filterset() as $key => $filter) {
            $filtersetdata[$filter->sortorder] = $filter;
        }
        return $filtersetdata;
    }

    /**
     * Delete current course filters and save the ones passed.
     *
     * @param block_xp_filterset_course[]|block_xp_filterset_default[] $filtersetdata
     */
    public function save(array $filtersetdata) {
        $newfilterset = block_xp_filterset::create_from_data($this->courseid, $filtersetdata);
        $this->get_filterset()->import($newfilterset);
        $this->invalidate_filters_cache();
    }

}
