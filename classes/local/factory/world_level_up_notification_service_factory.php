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

namespace block_xp\local\factory;

use block_xp\local\course_world;
use block_xp\local\notification\course_level_up_notification_service;
use block_xp\local\notification\level_up_notification_service;
use block_xp\local\notification\prefs_level_up_notification_service;
use block_xp\local\world;

/**
 * World level up notification service factory.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class world_level_up_notification_service_factory {
    /**
     * Get for a world.
     *
     * @param world $world The world.
     * @return level_up_notification_service
     */
    public function get_for_world(world $world): level_up_notification_service {
        if ($world instanceof course_world) {
            return new course_level_up_notification_service($world->get_courseid());
        }
        return new prefs_level_up_notification_service('block_xp_notify_ctx_level_up_' . $world->get_context()->id);
    }
}
