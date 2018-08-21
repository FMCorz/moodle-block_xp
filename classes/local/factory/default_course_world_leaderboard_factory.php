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
 * Default course world leaderboard factory.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\factory;
defined('MOODLE_INTERNAL') || die();

use lang_string;
use moodle_database;
use block_xp\local\course_world;
use block_xp\local\config\config;
use block_xp\local\config\course_world_config;
use block_xp\local\leaderboard\anonymised_leaderboard;
use block_xp\local\leaderboard\course_user_leaderboard;
use block_xp\local\leaderboard\leaderboard;
use block_xp\local\leaderboard\neighboured_leaderboard;
use block_xp\local\leaderboard\null_ranker;
use block_xp\local\leaderboard\ranker;
use block_xp\local\leaderboard\relative_ranker;

/**
 * Default course world leaderboard factory.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_course_world_leaderboard_factory implements course_world_leaderboard_factory {

    /** @var moodle_database The DB. */
    protected $db;

    /**
     * Constructor.
     *
     * @param moodle_database $db The DB.
     */
    public function __construct(moodle_database $db) {
        $this->db = $db;
    }

    /**
     * Get the leaderboard.
     *
     * @param course_world $world The world.
     * @param course_world $groupid The group ID, or 0 for none or all participants.
     * @return block_xp\local\leaderboard\leaderboard
     */
    public function get_course_leaderboard(course_world $world, $groupid = 0) {
        global $USER;

        $config = $world->get_config();

        // How is the rank computed?
        $ranker = $this->get_ranker($world);

        // Find out what columns to use.
        $columns = $this->get_columns($world);

        // Get the leaderboard.
        $leaderboard = $this->get_leaderboard_instance($world, $groupid, $columns, $ranker);

        // Wrap?
        $leaderboard = $this->wrap_leaderboard($world, $leaderboard);

        return $leaderboard;
    }

    /**
     * Get the columns.
     *
     * @param course_world $world The world.
     * @return array
     */
    protected function get_columns(course_world $world) {
        $config = $world->get_config();
        $columns = [];

        if ($config->get('rankmode') != course_world_config::RANK_OFF) {
            if ($config->get('rankmode') == course_world_config::RANK_REL) {
                $columns['level'] = new lang_string('level', 'block_xp');
                $columns['rank'] = new lang_string('difference', 'block_xp');
            } else {
                $columns['rank'] = new lang_string('rank', 'block_xp');
                $columns['level'] = new lang_string('level', 'block_xp');
            }
        } else {
            $columns['level'] = new lang_string('level', 'block_xp');
        }
        $columns['fullname'] = new lang_string('participant', 'block_xp');

        $additionalcols = explode(',', $config->get('laddercols'));
        if (in_array('xp', $additionalcols)) {
            $columns['xp'] = new lang_string('total', 'block_xp');
        }
        if (in_array('progress', $additionalcols)) {
            $columns['progress'] = new lang_string('progress', 'block_xp');
        }

        return $columns;
    }

    /**
     * Get the leaderboard instance.
     *
     * @param course_world $world The world.
     * @param int $groupid The group ID.
     * @param array $columns The columns.
     * @param ranker|null $ranker The ranker.
     * @return leaderboard
     */
    protected function get_leaderboard_instance(course_world $world, $groupid, array $columns, ranker $ranker = null) {
        return new course_user_leaderboard(
            $this->db,
            $world->get_levels_info(),
            $world->get_courseid(),
            $columns,
            $ranker,
            $groupid
        );
    }

    /**
     * Get the ranker.
     *
     * @param course_world $world The world.
     * @return ranker|null
     */
    protected function get_ranker(course_world $world) {
        global $USER;
        $config = $world->get_config();

        $ranker = null;
        if ($config->get('rankmode') == course_world_config::RANK_OFF) {
            $ranker = new null_ranker();
        } else if ($config->get('rankmode') == course_world_config::RANK_REL) {
            $ranker = new relative_ranker($world->get_store()->get_state($USER->id));
        }
        return $ranker;
    }

    /**
     * Wrap the leaderboard if needed.
     *
     * @param course_world $world The world.
     * @param leaderboard $leaderboard The leaderboard.
     * @return leaderboard
     */
    protected function wrap_leaderboard(course_world $world, leaderboard $leaderboard) {
        global $USER;
        $config = $world->get_config();

        // Do we only display the neighbours?
        if ($config->get('neighbours')) {
            $leaderboard = new neighboured_leaderboard($leaderboard, $USER->id, $config->get('neighbours'),
                $world->get_access_permissions()->can_manage());
        }

        // Is the leaderboard anonymous?
        if ($config->get('identitymode') == course_world_config::IDENTITY_OFF) {
            $leaderboard = new anonymised_leaderboard($leaderboard, $world->get_levels_info(), guest_user(), [$USER->id]);
        }

        return $leaderboard;
    }

}
