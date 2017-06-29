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
 * Course user event collection log purge task.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\task;
defined('MOODLE_INTERNAL') || die();

use DateTime;

/**
 * Course user event collection log purge task class.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_user_event_collection_log_purge extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('taskcourseusereventcollectionlogpurge', 'block_xp');
    }

    public function execute() {
        $db = \block_xp\di::get('db');
        $factory = \block_xp\di::get('course_world_factory');

        // Check each course.
        $courseids = \block_xp\local\logger\course_user_event_collection_logger::get_courses_with_logs($db);
        foreach ($courseids as $courseid) {
            $world = $factory->get_world($courseid);
            $keeplogs = $world->get_config()->get('keeplogs');

            if (!$keeplogs) {
                // Keep forever.
                return;
            }

            $dt = new DateTime();
            $dt->setTimestamp(time() - ($keeplogs * DAYSECS));

            $logger = $world->get_user_event_collection_logger();
            $logger->delete_older_than($dt);
        }
    }

}
