<?php
// This file is part of Level Up XP.
//
// Level Up XP is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Level Up XP is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Level Up XP.  If not, see <https://www.gnu.org/licenses/>.
//
// See <https://levelup.plus>.

namespace block_xp\local\notification;

/**
 * Level up notification service.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface level_up_notification_service {
    /**
     * Get the levels to notify for.
     *
     * @param int $userid The user ID.
     * @return int[]
     */
    public function get_levels($userid);

    /**
     * Flag the user as having been notified.
     *
     * @param int $userid The user ID.
     * @param int $level The level. Optional for legacy reasons, always pass a valid level.
     * @return void
     */
    public function mark_as_notified($userid, $level = 0);

    /**
     * Notify a user.
     *
     * @param int $userid The user ID.
     * @param int $level The level. Optional for legacy reasons, always pass a valid level.
     * @return void
     */
    public function notify($userid, $level = 0);

    /**
     * Whether the user should be notified.
     *
     * @param int $userid The user ID.
     * @return bool
     */
    public function should_be_notified($userid);
}
