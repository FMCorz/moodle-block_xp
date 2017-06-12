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
 * Manager factory interface.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local;
defined('MOODLE_INTERNAL') || die();

/**
 * Manager factory interface.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface manager_interface {

    public function can_manage();
    public function can_view();
    public function can_view_infos_page();  // Remove, set in controllers?
    public function can_view_ladder_page(); // Remove, set in controllers?
    public function is_enabled();

    public function get_context();
    public function get_course();
    public function get_courseid();

    public function get_config($name = null);
    public function update_config($data);

    public function reset_data($groupid = 0);
    public function reset_user_xp($userid, $xp = 0);    // Possibly get an object representing the user's XP.

    public function recalculate_levels();   // Separate level config from the rest, so that this can be done internally.
    public function get_levels_info();

    public function get_progress_for_user($userid, \stdclass $record = null);   // Questionable.

    public function capture_event(\core\event\base $event);     // Shouldn't the filter manager do a lot of this?

    public function purge_log();    // Sounds very specific to the default manager, could be replaced with a more generic cron job.

    public function has_levelled_up($userid, $reset = true);    // Obscure.

}
