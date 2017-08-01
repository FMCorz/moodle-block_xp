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
 * Course level up notification service.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\notification;
defined('MOODLE_INTERNAL') || die();

/**
 * Course level up notification service.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_level_up_notification_service {

    /** User preference prefix. */
    const USERPREF_NOTIFY = 'block_xp_notify_level_up_';

    /**
     * Constructor.
     *
     * @param int $courseid The course ID.
     */
    public function __construct($courseid) {
        $this->key = self::USERPREF_NOTIFY . $courseid;
    }

    /**
     * Flag the user as having been notified.
     *
     * @param int $userid The user ID.
     */
    public function mark_as_notified($userid) {
        unset_user_preference($this->key, $userid);
    }

    /**
     * Notify a user.
     *
     * @param int $userid The user ID.
     * @return void
     */
    public function notify($userid) {
        set_user_preference($this->key, 1, $userid);
    }

    /**
     * Whether the user should be notified.
     *
     * @param int $userid The user ID.
     * @return bool
     */
    public function should_be_notified($userid) {
        return (bool) get_user_preferences($this->key, false, $userid);
    }

}
