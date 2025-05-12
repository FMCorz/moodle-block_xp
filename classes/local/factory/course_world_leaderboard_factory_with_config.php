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
 * Course world leaderboard factory interface.
 *
 * @package    block_xp
 * @copyright  2022 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\factory;

use block_xp\local\config\config;
use block_xp\local\course_world;

/**
 * Course world leaderboard factory interface.
 *
 * @package    block_xp
 * @copyright  2022 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @deprecated Since XP 3.17, use leaderboard_factory_maker instead.
 */
interface course_world_leaderboard_factory_with_config extends course_world_leaderboard_factory {

    /**
     * Get the leaderboard.
     *
     * This is an advanced method, the configuration should be matching what a course config
     * matches to generate the leaderboard from. When used, the factory should avoid referencing
     * the config object from the world, in favour of the one passed. The goal of this is
     * to be able to generate a leaderboard using a different configuration.
     *
     * @param course_world $world The world.
     * @param config $config The config.
     * @param course_world $groupid The group ID, or 0 for none or all participants.
     * @return \block_xp\local\leaderboard\leaderboard
     */
    public function get_course_leaderboard_with_config(course_world $world, config $config, $groupid = 0);

}
