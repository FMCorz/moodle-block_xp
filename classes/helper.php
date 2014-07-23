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
 * Block XP manager.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block XP manager class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_helper {

    /**
     * Observe the events, and dispatch them if necessary.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    public static function observer(\core\event\base $event) {

        if ($event->component === 'block_xp') {
            // Skip own events.
        } else if (!$event->userid || isguestuser($event->userid)) {
            // Skip non-logged in users and guests.
        } else if ($event->contextlevel !== CONTEXT_COURSE && $event->contextlevel !== CONTEXT_MODULE) {
            // Ignore events outside a course.
        } else if ($event->edulevel !== \core\event\base::LEVEL_PARTICIPATING) {
            // Ignore events that are not participating.
        } else if ($event->crud === 'd') {
            // Ignore events that delete content.
        } else if ($event->target === 'assessable' && in_array($event->action, array('submitted', 'uploaded'))) {
            // Skip those as they duplicate other more low level actions.
        } else {
            // Keep the event, and proceed.
            $manager = block_xp_manager::get($event->courseid);
            $manager->capture_event($event);
        }

    }

}
