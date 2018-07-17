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
 * Anonymised leaderboard.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\leaderboard;
defined('MOODLE_INTERNAL') || die();

use block_xp\local\iterator\map_iterator;
use block_xp\local\sql\limit;
use block_xp\local\xp\levels_info;
use block_xp\local\xp\rank;
use block_xp\local\xp\state_rank;
use block_xp\local\xp\user_state;

/**
 * Anonymised leaderboard.
 *
 * This currenly only handles user_state.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class anonymised_leaderboard implements leaderboard {

    /** @var leaderboard The leaderboard to anonymise. */
    protected $leaderboard;
    /** @var levels_info The levels info. */
    protected $levelsinfo;
    /** @var int[] The object IDs not to anonymise. */
    protected $exceptids;
    /** @var stdClass The user to replace with. */
    protected $anonymous;

    /**
     * Constructor.
     *
     * @param leaderboard $leaderboard The leaderboard.
     * @param levels_info $levelsinfo The levels info.
     * @param object $anonymous The anonymous object to use instead.
     * @param array $exceptids The IDs not to anonymise.
     */
    public function __construct(leaderboard $leaderboard, levels_info $levelsinfo, $anonymous, $exceptids = []) {
        $this->leaderboard = $leaderboard;
        $this->anonymous = $anonymous;
        $this->exceptids = $exceptids;
        $this->levelsinfo = $levelsinfo;
    }

    /**
     * Anonymise the state rank.
     *
     * @param rank $staterank The state rank.
     * @return rank
     */
    protected function anonymise_rank(rank $rank) {
        $state = $rank->get_state();
        if ($state instanceof user_state && !in_array($state->get_id(), $this->exceptids)) {
            $rank = new state_rank(
                $rank->get_rank(),
                new user_state($this->anonymous, $state->get_xp(), $this->levelsinfo)
            );
        }
        return $rank;
    }

    /**
     * Get the leaderboard columns.
     *
     * @return array Where keys are column identifiers and values are lang_string objects.
     */
    public function get_columns() {
        return $this->leaderboard->get_columns();
    }

    /**
     * Get the number of rows in the leaderboard.
     *
     * @return int
     */
    public function get_count() {
        return $this->leaderboard->get_count();
    }

    /**
     * Get the number of rows in the leaderboard.
     *
     * @param int $id The object ID.
     * @return int
     */
    public function get_position($id) {
        return $this->leaderboard->get_position($id);
    }


    /**
     * Get the rank of an object.
     *
     * @param int $id The object ID.
     * @return rank|null
     */
    public function get_rank($id) {
        $staterank = $this->leaderboard->get_rank($id);
        return $staterank === null ? null : $this->anonymise_rank($staterank);
    }


    /**
     * Get the ranking.
     *
     * @param limit $limit The limit.
     * @return Traversable
     */
    public function get_ranking(limit $limit) {
        $ranking = $this->leaderboard->get_ranking($limit);
        return new map_iterator($ranking, function($state) {
            return $this->anonymise_rank($state);
        });
    }

}
