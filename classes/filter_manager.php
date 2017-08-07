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
 * Empty class kept for compatibility with plugins connecting with it.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @deprecated Since 3.0.0
 */
class block_xp_filter_manager {

    /**
     * Constructor.
     *
     * @param block_xp_manager $manager The XP manager.
     */
    public function __construct(block_xp_manager $manager) {
    }

    /**
     * Get all the filter objects.
     *
     * @return array of fitlers.
     */
    public function get_all_filters() {
        return [];
    }

    /**
     * Return the points filtered for this event.
     *
     * @param \core\event\base $event The event.
     * @return int points.
     */
    public function get_points_for_event(\core\event\base $event) {
        return 0;
    }

    /**
     * Get the default filters.
     *
     * @return array Of filter objects.
     */
    public static function get_static_filters() {
        return [];
    }

    /**
     * Get the filters defined by the user.
     *
     * @return array Of filter data from the DB, though properties is already json_decoded.
     */
    public function get_user_filters() {
        return [];
    }

    /**
     * Invalidate the filters cache.
     *
     * @return void
     */
    public function invalidate_filters_cache() {
    }
}
