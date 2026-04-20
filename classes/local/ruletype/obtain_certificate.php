<?php
// This file is part of Level Up XP+.
//
// Level Up XP+ is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Level Up XP+ is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Level Up XP+.  If not, see <https://www.gnu.org/licenses/>.
//
// https://levelup.plus

namespace block_xp\local\ruletype;

use block_xp\local\action\action;
use block_xp\local\availability\availability_info;
use block_xp\local\availability\has_availability_info;
use block_xp\local\availability\static_info;
use block_xp\local\availability\unavailability;
use block_xp\local\icon\fa_icon;
use block_xp\local\icon\icon;
use block_xp\local\icon\with_iconography;
use block_xp\local\reason\reason;
use block_xp\local\reason\unknown_reason;
use lang_string;

/**
 * Obtain certificate rule type.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class obtain_certificate implements has_availability_info, ruletype, ruletype_with_goal, with_iconography {
    use ruletype_deprecation_filler_trait;

    public function get_availability_info(): availability_info {
        return new static_info(false, [new unavailability('xpplusrequired', new \lang_string('xpplusrequired', 'block_xp'))]);
    }

    public function get_display_name(): lang_string {
        return new lang_string('ruletypeobtaincertificate', 'block_xp');
    }

    public function get_education_goal(): string {
        return self::GOAL_ASSESS;
    }

    public function get_icon(): ?icon {
        return new fa_icon('certificate');
    }

    public function get_short_description(): lang_string {
        return new lang_string('ruletypeobtaincertificatedesc', 'block_xp');
    }

    public function is_compatible_with_admin(): bool {
        return true;
    }

    public function is_action_compatible(action $action): bool {
        return false;
    }

    public function is_action_satisfying_requirements(action $action): bool {
        return false;
    }

    public function make_reason(action $action): reason {
        return new unknown_reason();
    }
}
