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

use block_xp\local\action\action;
use block_xp\local\icon\fa_icon;
use block_xp\local\icon\icon;
use block_xp\local\icon\with_iconography;
use block_xp\local\reason\database_entry_created_reason;
use block_xp\local\reason\reason;
use block_xp\local\ruletype\profile\cm_profile;
use block_xp\local\ruletype\profile\profile;
use block_xp\local\ruletype\ruletype;
use lang_string;

/**
 * Type.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class create_database_entry implements ruletype, ruletype_with_goal, ruletype_with_limit, ruletype_with_profile, with_iconography {
    use ruletype_deprecation_filler_trait;

    public function get_default_limit(): limit_spec {
        return new limit_spec(5, limit_spec::WINDOW_HOURLY);
    }

    public function get_default_repeat_limit(): limit_spec {
        return new limit_spec(0);
    }

    public function get_display_name(): lang_string {
        return new lang_string('ruletypecreatedatabaseentry', 'block_xp');
    }

    public function get_education_goal(): string {
        return self::GOAL_CONTRIB;
    }

    public function get_icon(): ?icon {
        return new fa_icon('database');
    }

    public function get_repeat_limit_options(): array {
        return [
            (new repeat_option(limit_spec::SCOPE_ENV))
                ->set_once_label(new lang_string('onceperactivity', 'block_xp'))
                ->set_incompatible_inside_cm(),
        ];
    }

    public function get_profile(): profile {
        return new cm_profile('data');
    }

    public function get_short_description(): lang_string {
        return new lang_string('ruletypecreatedatabaseentrydesc', 'block_xp');
    }

    public function is_action_compatible(action $action): bool {
        return $action->get_type() === 'database_entry_created';
    }

    public function is_action_satisfying_requirements(action $action): bool {
        return true;
    }

    public function make_reason(action $action): reason {
        $reason = new database_entry_created_reason();
        $reason->set_env_id((int) $action->get_context()->id);
        $reason->set_object_id($action->get_object_id());
        return $reason;
    }

}
