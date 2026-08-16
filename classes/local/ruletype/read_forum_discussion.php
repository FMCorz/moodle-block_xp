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
// See <https://levelup.plus>.

namespace block_xp\local\ruletype;

use block_xp\local\action\action;
use block_xp\local\action\discussion_viewed;
use block_xp\local\icon\fa_icon;
use block_xp\local\icon\icon;
use block_xp\local\icon\with_iconography;
use block_xp\local\reason\forum_discussion_read_reason;
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
class read_forum_discussion implements ruletype, ruletype_with_goal, ruletype_with_limit, ruletype_with_profile, with_iconography {
    use ruletype_deprecation_filler_trait;

    /**
     * Get default limit.
     *
     * @return limit_spec
     */
    public function get_default_limit(): limit_spec {
        return new limit_spec(0, limit_spec::WINDOW_HOURLY);
    }

    /**
     * Get default repeat limit.
     *
     * @return limit_spec
     */
    public function get_default_repeat_limit(): limit_spec {
        return new limit_spec(1, limit_spec::WINDOW_HOURLY, limit_spec::SCOPE_ENV | limit_spec::SCOPE_OBJECT);
    }

    /**
     * Get display name.
     *
     * @return lang_string
     */
    public function get_display_name(): lang_string {
        return new lang_string('ruletypereadforumdiscussion', 'block_xp');
    }

    /**
     * Get education goal.
     *
     * @return string
     */
    public function get_education_goal(): string {
        return self::GOAL_COMMS;
    }

    /**
     * Get icon.
     *
     * @return icon|null
     */
    public function get_icon(): ?icon {
        return new fa_icon('comments');
    }

    /**
     * Get repeat limit options.
     *
     * @return repeat_option[]
     */
    public function get_repeat_limit_options(): array {
        return [
            (new repeat_option(limit_spec::SCOPE_ENV))
                ->set_once_label(new lang_string('onceperforum', 'block_xp'))
                ->set_incompatible_inside_cm(),
            (new repeat_option(limit_spec::SCOPE_ENV | limit_spec::SCOPE_OBJECT))
                ->set_once_label(new lang_string('onceperdiscussion', 'block_xp')),
        ];
    }

    /**
     * Get profile.
     *
     * @return profile
     */
    public function get_profile(): profile {
        return new cm_profile('forum');
    }

    /**
     * Get short description.
     *
     * @return lang_string
     */
    public function get_short_description(): lang_string {
        return new lang_string('ruletypereadforumdiscussiondesc', 'block_xp');
    }

    /**
     * Check action compatibility.
     *
     * @param action $action The action.
     * @return bool
     */
    public function is_action_compatible(action $action): bool {
        return $action instanceof discussion_viewed;
    }

    /**
     * Check action requirements.
     *
     * @param action $action The action.
     * @return bool
     */
    public function is_action_satisfying_requirements(action $action): bool {
        return true;
    }

    /**
     * Make reason.
     *
     * @param action $action The action.
     * @return reason
     */
    public function make_reason(action $action): reason {
        if (!$action instanceof discussion_viewed) {
            throw new \coding_exception('unexpectedaction');
        }
        $reason = new forum_discussion_read_reason();
        $reason->set_env_id((int) $action->get_context()->id);
        $reason->set_object_id($action->get_discussion_id());
        return $reason;
    }
}
