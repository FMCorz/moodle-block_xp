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

namespace block_xp\local\utils;

use block_xp\local\world;
use block_xp\local\world\world_with_features;
/**
 * Utils.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class world_utils {
    /**
     * Whether the world supports local points management.
     *
     * @param world $world
     * @return bool
     */
    public static function supports_local_points_management(world $world): bool {
        return !$world instanceof world_with_features
            || $world->supports($world::FEATURE_LOCAL_POINTS_MANAGEMENT) !== false;
    }

    /**
     * Whether the world supports recent activity.
     *
     * @param world $world
     * @return bool
     */
    public static function supports_recent_activity(world $world): bool {
        return !$world instanceof world_with_features
            || $world->supports($world::FEATURE_RECENT_ACTIVITY) !== false;
    }

    /**
     * Whether the world supports team leaderboards.
     *
     * @param world $world
     * @return bool
     */
    public static function supports_team_leaderboard(world $world): bool {
        return !$world instanceof world_with_features
            || $world->supports($world::FEATURE_TEAM_LEADERBOARD) !== false;
    }
}
