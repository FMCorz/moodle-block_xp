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
 * Unavailability reason.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\availability;

/**
 * Unavailability reason.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class unavailability implements unavailability_reason {

    /** @var string */
    protected $code;
    /** @var string|\lang_string */
    protected $description;

    /**
     * Constructor.
     *
     * @param string $code The machine readable code.
     * @param string|\lang_string $description The description of the reason.
     */
    public function __construct($code, $description) {
        $this->code = $code;
        $this->description = $description;
    }

    /**
     * A machine readable code.
     *
     * @return string
     */
    public function get_code(): string {
        return $this->code;
    }

    /**
     * A description of the reason.
     *
     * @return string|\lang_string
     */
    public function get_description() {
        return $this->description;
    }

}
