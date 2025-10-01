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
 * Setup trait.
 *
 * PHP Unit setUp method compatibility between multiple PHP versions.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\tests;

defined('MOODLE_INTERNAL') || die();

// phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses

/**
 * Setup trait.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @deprecated Since XP 19, no longer needed.
 */
trait setup_trait_310_onwards {

    /**
     * PHP Unit setup method.
     */
    public function setUp(): void {
        $this->setup_test();
    }

}

/**
 * Setup trait.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @deprecated Since XP 19, no longer needed.
 */
trait setup_trait_pre_310 {

    /**
     * PHP Unit setup method.
     */
    public function setup() {
        $this->setup_test();
    }

}

/**
 * Setup trait.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @deprecated Since XP 19, no longer needed.
 */
trait setup_trait {
    use setup_trait_310_onwards;

    /**
     * Alias for the standard method.
     */
    protected function setup_test() {
    }

}
