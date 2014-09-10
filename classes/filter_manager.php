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
     * Get all the filter objects.
     *
     * Positive indexes are user filters, negatives are static ones.
     * Do not reorder this array, it is ordered by priority.
     *
     * @return array of fitlers.
     */
    public function get_all_filters() {
        $filters = $this->get_user_filters();
        $i = -1;
        foreach (self::get_static_filters() as $filter) {
            $filters[$i--] = $filter;
        }
        return $filters;
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
        $d = new block_xp_rule_property(block_xp_rule_base::EQ, 'd', 'crud');
        $c = new block_xp_rule_property(block_xp_rule_base::EQ, 'c', 'crud');
        $r = new block_xp_rule_property(block_xp_rule_base::EQ, 'r', 'crud');
        $u = new block_xp_rule_property(block_xp_rule_base::EQ, 'u', 'crud');
        $data = array('rule' => $c, 'points' => 45, 'editable' => false);
        $fc = block_xp_filter::load_from_data($data);
        $data = array('rule' => $r, 'points' => 9, 'editable' => false);
        $fr = block_xp_filter::load_from_data($data);
        $data = array('rule' => $u, 'points' => 3, 'editable' => false);
        $fu = block_xp_filter::load_from_data($data);
        $data = array('rule' => $d, 'points' => 0, 'editable' => false);
        $fd = block_xp_filter::load_from_data($data);
        return array($fc, $fr, $fu, $fd);
    }

    /**
     * Get the filters defined by the user.
     *
     * @return array Of filter data from the DB, though properties is already json_decoded.
     */
    public function get_user_filters() {
        global $DB;
        $results = $DB->get_recordset('block_xp_filters', array('courseid' => $this->manager->get_courseid()),
            'sortorder ASC, id ASC');
        $filters = array();
        foreach ($results as $key => $filter) {
            $filters[$filter->id] = block_xp_filter::load_from_data($filter);
        }
        $results->close();
        return $filters;
    }
}
