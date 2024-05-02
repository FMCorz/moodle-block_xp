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

namespace block_xp\local\rulefilter;

use block_xp\local\action\tester\bool_tester;

/**
 * Filter.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @deprecated Since XP 20, use block_xp\local\action\tester\bool_tester instead.
 */
class any_tester extends bool_tester {

    /**
     * Constructor.
     */
    public function __construct() {
        debugging('The class rulefilter\any_tester is deprecated, '
            . 'use block_xp\local\action\tester\bool_tester instead.', DEBUG_DEVELOPER);
        parent::__construct(true);
    }

}
