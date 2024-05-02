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
// https://levelup.plus

namespace block_xp\local\ruletype;

/**
 * Limit specification.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class limit_spec {

    /** Scope none. */
    const SCOPE_NONE = 0;
    /** Scope env. */
    const SCOPE_ENV = 2;
    /** Scope parent. */
    const SCOPE_PARENT = 4;
    /** Scope object. */
    const SCOPE_OBJECT = 8;

    /** No window. */
    const WINDOW_NONE = 0;
    /** A 1h rolling window. */
    const WINDOW_1H = 1;
    /** Reset at midnight. */
    const WINDOW_DAILY = 2;
    /** Reset at the start of the week. */
    const WINDOW_WEEKLY = 3;
    /** Reset at the start of the month. */
    const WINDOW_MONTHLY = 4;
    /** Reset at the start of the hour. */
    const WINDOW_HOURLY = 5;

    /** @var int The time window. */
    protected int $timewindow;
    /** @var int The max. */
    protected int $max;
    /** @var int The scope. */
    protected int $scope;

    /**
     * Constructor.
     *
     * @param int $max The max.
     * @param int $timewindow The time window.
     * @param int $scope The scope.
     */
    public function __construct(int $max = 0, int $timewindow = self::WINDOW_NONE, int $scope = self::SCOPE_NONE) {
        $this->max = max(0, $max);
        $this->timewindow = $timewindow;
        $this->scope = $scope;
    }

    /**
     * Get the max.
     *
     * @return int Zero means unlimited.
     */
    public function get_max(): int {
        return $this->max;
    }

    /**
     * Get the time window.
     *
     * @return int
     */
    public function get_time_window(): int {
        return $this->timewindow;
    }

    /**
     * Get the uniqueness scope.
     *
     * @return int
     */
    public function get_scope(): int {
        return $this->scope;
    }
}
