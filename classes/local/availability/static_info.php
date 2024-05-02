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

/**
 * Static info.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\availability;

/**
 * Static info.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class static_info implements availability_info {

    /** @var bool Whether available. */
    protected $isavailable;
    /** @var unavailability_reason[] The reasons. */
    protected $reasons;

    /**
     * Constructor.
     *
     * @param bool $isavailable Whether available.
     * @param unavailability_reason[] $reasons The reasons.
     */
    public function __construct(bool $isavailable, array $reasons = []) {
        $this->isavailable = $isavailable;
        $this->reasons = $reasons;
    }

    public function is_available(): bool {
        return $this->isavailable;
    }

    public function get_reasons(): array {
        return $this->reasons;
    }

}
