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

namespace block_xp\local\world;

use block_xp\local\world;

/**
 * World with features.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface world_with_features extends world {
    /** Team leaderboard. */
    const FEATURE_TEAM_LEADERBOARD = 'team-leaderboard';
    /** Adding, setting, deleting, resetting, importing local user points, or local reward mechanisms. */
    const FEATURE_LOCAL_POINTS_MANAGEMENT = 'local-points-management';
    /** Recent activity in block. */
    const FEATURE_RECENT_ACTIVITY = 'recent-activity';

    /**
     * Whether a feature is supported by the world.
     *
     * @param string $feature A FEAT_* constant.
     * @return bool|null True if supported, false if unsupported, or null if unspecified.
     */
    public function supports(string $feature): ?bool;
}
