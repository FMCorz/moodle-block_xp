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

namespace block_xp\local\xp;
defined('MOODLE_INTERNAL') || die();

use cache;
use coding_exception;
use moodle_database;

/**
 * Filter manager class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_filter_manager {

    /** @var int The course ID. */
    protected $courseid;
    /** @var moodle_database The DB. */
    protected $db;

    /**
     * Constructor.
     *
     * @param moodle_database $db The DB.
     * @param int $courseid The course ID.
     */
    public function __construct(moodle_database $db, $courseid) {
        $this->db = $db;
        $this->courseid = $courseid;
    }

    /**
     * Take the static filters and conver them.
     *
     * This should not be used any more. It is only used when converting
     * the static filters to regular filters when dealing with legacy
     * code.
     *
     * @return void
     */
    public function convert_static_filters_to_regular() {
        $filters = $this->get_static_filters();
        $this->import_filters($filters);
    }

    /**
     * Get all the filter objects.
     *
     * Positive indexes are user filters, negatives are static ones.
     * Do not reorder this array, it is ordered by priority.
     *
     * @return array of filters.
     * @deprecated Since 3.0.0
     */
    public function get_all_filters() {
        debugging('The method get_all_filters() is deprecated, use get_filters() instead.', DEBUG_DEVELOPER);
        return $this->get_filters();
    }

    /**
     * Get the filters.
     *
     * @return array of filters.
     */
    public function get_filters() {
        $cache = cache::make('block_xp', 'filters');
        $key = 'filters_' . $this->courseid;
        if (false === ($filters = $cache->get($key))) {
            // TODO Caching is unsafe, we should not be serializing the object when
            // we have a mechanism for exporting them...
            $filters = $this->get_user_filters();
            $cache->set($key, $filters);
        }
        return $filters;
    }

    /**
     * Return the points filtered for this event.
     *
     * @param \core\event\base $event The event.
     * @return int Points, or null.
     */
    public function get_points_for_event(\core\event\base $event) {
        foreach ($this->get_filters() as $filter) {
            if ($filter->match($event)) {
                return $filter->get_points();
            }
        }
        return null;
    }

    /**
     * Get the static filters.
     *
     * This is a legacy method, it contains a list of static filters which were
     * used in version prior to 3.0. Those static filters were not editable and
     * ensured that events were always matched with something. From 3.0, there are
     * no longer static filters, but there are default filters, typically defined
     * by the adminstrator.
     *
     * This method should only be used whenever the static filters are converted
     * to standard filters to maintain backwards compatibility while allowing the
     * users to edit them.
     *
     * @return array Of filter objects.
     */
    public function get_static_filters() {
        $d = new \block_xp_rule_property(\block_xp_rule_base::EQ, 'd', 'crud');
        $c = new \block_xp_rule_property(\block_xp_rule_base::EQ, 'c', 'crud');
        $r = new \block_xp_rule_property(\block_xp_rule_base::EQ, 'r', 'crud');
        $u = new \block_xp_rule_property(\block_xp_rule_base::EQ, 'u', 'crud');

        // Skip those as they duplicate other more low level actions.
        $bcmv = new \block_xp_rule_event('\mod_book\event\course_module_viewed');
        $dsc = new \block_xp_rule_event('\mod_forum\event\discussion_subscription_created');
        $sc = new \block_xp_rule_event('\mod_forum\event\subscription_created');
        $as = new \block_xp_rule_property(\block_xp_rule_base::CT, 'assessable_submitted', 'eventname');
        $au = new \block_xp_rule_property(\block_xp_rule_base::CT, 'assessable_uploaded', 'eventname');

        $list = [];

        $ruleset = new \block_xp_ruleset([$bcmv, $dsc, $sc, $as, $au], \block_xp_ruleset::ANY);
        $data = ['rule' => $ruleset, 'points' => 0, 'editable' => false];
        $list[] = \block_xp_filter::load_from_data($data);

        $data = ['rule' => $c, 'points' => 45, 'editable' => false];
        $list[] = \block_xp_filter::load_from_data($data);

        $data = ['rule' => $r, 'points' => 9, 'editable' => false];
        $list[] = \block_xp_filter::load_from_data($data);

        $data = ['rule' => $u, 'points' => 3, 'editable' => false];
        $list[] = \block_xp_filter::load_from_data($data);

        $data = ['rule' => $d, 'points' => 0, 'editable' => false];
        $list[] = \block_xp_filter::load_from_data($data);

        return $list;
    }

    /**
     * Get the filters defined by the user.
     *
     * @return array Of filter data from the DB, though properties is already json_decoded.
     */
    public function get_user_filters() {
        $results = $this->db->get_recordset('block_xp_filters', array('courseid' => $this->courseid),
            'sortorder ASC, id ASC');
        $filters = array();
        foreach ($results as $key => $filter) {
            $filters[$filter->id] = \block_xp_filter::load_from_data($filter);
        }
        $results->close();
        return $filters;
    }

    /**
     * Import filters by appending them.
     *
     * @param array $filters An array of filters.
     * @return void
     */
    protected function import_filters(array $filters) {
        $sortorder = (int) $this->db->get_field('block_xp_filters', 'COALESCE(MAX(sortorder), -1) + 1',
            ['courseid' => $this->courseid]);

        foreach ($filters as $filter) {
            $record = $filter->export();
            $record->courseid = $this->courseid;
            $record->sortorder = $sortorder++;
            $newfilter = \block_xp_filter::load_from_data($record);
            $newfilter->save();
        }

        $this->invalidate_filters_cache();
    }

    /**
     * Import the default filters.
     *
     * @return void
     */
    public function import_default_filters() {
        $fm = new admin_filter_manager($this->db);
        $this->import_filters($fm->get_filters());
    }

    /**
     * Invalidate the filters cache.
     *
     * @return void
     */
    public function invalidate_filters_cache() {
        $cache = cache::make('block_xp', 'filters');
        $cache->delete('filters_' . $this->courseid);
    }

    /**
     * Removes all filters.
     *
     * @return void
     */
    public function purge() {
        $this->db->delete_records('block_xp_filters', ['courseid' => $this->courseid]);
        $this->invalidate_filters_cache();
    }

}
