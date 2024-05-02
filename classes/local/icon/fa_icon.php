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

namespace block_xp\local\icon;

/**
 * File.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class fa_icon implements icon {

    /** @var string The name. */
    protected $name;

    /**
     * Constructor.
     *
     * @param string $name The name without the fa- part.
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * Get the type.
     *
     * @return string
     */
    public function get_type(): string {
        return 'fa';
    }

    /**
     * Get the value.
     *
     * @return string
     */
    public function get_value(): string {
        return $this->name;
    }

}
