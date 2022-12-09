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
 * Neighboured leaderboard.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\leaderboard;
defined('MOODLE_INTERNAL') || die();

use block_xp\local\sql\limit;

/**
 * Course user neighbours leaderboard.
 *
 * @package    block_xp
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class neighboured_leaderboard implements leaderboard {

    /** @var leaderboard The leaderboard. */
    protected $leaderboard;
    /** @var int The neighbours. */
    protected $neighbours;
    /** @var int The object relative to this. */
    protected $objectid;
    /** @var bool Whether to display results even when not found. */
    protected $fallbackontop;

    /**
     * Constructor.
     *
     * @param leaderboard $leaderboard The leaderboard.
     * @param int $objectid The object to be relative to.
     * @param int $groupid The group ID.
     * @param bool $fallbackontop When true, the ranking will display some results from the top when the
     *                            objectid is not found in the ranking. You probably want to use this
     *                            when the ranking is viewed by a manager.
     */
    public function __construct(leaderboard $leaderboard, $objectid, $neighbours, $fallbackontop = false) {
        $this->leaderboard = $leaderboard;
        $this->objectid = $objectid;
        $this->neighbours = $neighbours;
        $this->fallbackontop = $fallbackontop;
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
        // Always assumes there are as many neighbours as possible.
        return $this->neighbours * 2 + 1;
    }

    /**
     * Get limit and count.
     *
     * @return array
     */
    private function get_limit_and_count() {
        $neighbours = $this->neighbours;
        $pos = $this->leaderboard->get_position($this->objectid);
        if ($pos === null) {
            return $this->fallbackontop ? [new limit($this->neighbours, 0), $this->neighbours, 0] : [new limit(0, 0), 0, 0];
        }
        $total = $this->leaderboard->get_count();

        $count = $neighbours * 2 + 1;
        $missingleft = 0;
        $missingright = 0;

        // The are less people in front of us than the number of neighbours.
        if ($pos < $neighbours) {
            $missingleft = $neighbours - $pos;
            $count = $count - $missingleft;
        }

        // There are less people after us than the number of neighbours.
        if ($pos > $total - $neighbours) {
            $missingright = ($pos - ($total - $neighbours));
            $count = $count - $missingright;
        }

        $offset = max(0, $pos - $neighbours);
        $limit = new limit($count, $offset);
        $total = $count;

        return [$limit, $total, $missingleft];
    }

    /**
     * Return the position of the object.
     *
     * @param int $id The object ID.
     * @return int Indexed from 0, null when not ranked.
     */
    public function get_position($id) {
        // Only report that our position is in the middle, whether true or not.
        return $id == $this->objectid ? $this->neighbours : null;
    }

    /**
     * Get the rank of an object.
     *
     * @param int $id The object ID.
     * @return rank|null
     */
    public function get_rank($id) {
        // Only report on our rank, not to potentially disclose the rank of someone outside the neighbours.
        return $id == $this->objectid ? $this->leaderboard->get_rank($id) : null;
    }

    /**
     * Get the ranking.
     *
     * The custom limit is relative to the neighboured position, so in leaderboard configured
     * for 2 neighbours (2 left, 1 center, 2 right), the offset 0 is the far left of the ranking.
     * The offset 2 will be the center, and the offset 4 would be the far right entry.
     *
     * Any value of 0 in the custom limit is ignored.
     *
     * While this method has been made compatible with the absolute positioning reported by
     * the {@link self::get_position} method, it is not compatible with {@see self::get_count}
     * which always reports that there are as many entries as there could be.
     *
     * @param limit $customlimit The limit.
     * @return Traversable
     */
    public function get_ranking(limit $customlimit) {
        list($limit, $total, $missingleft) = $this->get_limit_and_count();

        $count = $limit->get_count();
        $offset = $limit->get_offset();
        $maxcount = $this->neighbours * 2 + 1;

        // This should not happen.
        if ($count <= 0) {
            return [];
        }

        // If we have a custom limit, we need to apply it respectively to the position.
        if ($customlimit) {
            $hascustomcount = $customlimit->get_count() > 0;
            $hascustomoffset = $customlimit->get_offset() > 0;
            $customcount = max(0, min($maxcount, $customlimit->get_count()));
            $customoffset = max(0, min($maxcount, $customlimit->get_offset()));

            if ($hascustomoffset && $hascustomcount) {
                $offsetdiff = $customoffset - $missingleft;
                $offset = max($offset, $offset + $offsetdiff);
                $count = min($count, $offsetdiff < 0 ? $customcount + $offsetdiff : $customcount);

            } else if ($hascustomcount) {
                $count = min($count, $customcount);

            } else if ($hascustomoffset) {
                $offsetdiff = $customoffset - $missingleft;
                $offset = max($offset, $offset + $offsetdiff);
                $count = $count - abs($offsetdiff);
            }

            $limit = new limit($count, $offset);
        }

        // With a custom limit, we can end up in a situation where the count is 0,
        // in which case the leaderboard should be empty.
        if ($limit->get_count() <= 0) {
            return [];
        }

        return $this->leaderboard->get_ranking($limit);
    }

}
