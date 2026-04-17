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

use block_xp\local\action\tester\action_tester;
use block_xp\local\action\tester\bool_tester;
use block_xp\local\availability\availability_info;
use block_xp\local\availability\has_availability_info;
use block_xp\local\availability\static_info;
use block_xp\local\availability\unavailability;
use context;

/**
 * None.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class xpplusrequired implements has_availability_info, rulefilter {

    final public function get_availability_info(): availability_info {
        return new static_info(false, [new unavailability('xpplusrequired', new \lang_string('xpplusrequired', 'block_xp'))]);
    }

    final public function get_action_tester(context $effectivecontext, object $config): action_tester {
        return new bool_tester(false);
    }

    final public function get_label_for_config(object $config, ?context $effectivecontext = null): string {
        return '?';
    }

    final public function is_multiple_allowed(): bool {
        return false;
    }

}
