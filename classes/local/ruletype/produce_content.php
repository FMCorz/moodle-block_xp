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
use block_xp\local\action\crud;
use block_xp\local\icon\fa_icon;
use block_xp\local\icon\icon;
use block_xp\local\icon\with_iconography;
use block_xp\local\reason\event_reason;
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
class produce_content implements ruletype, ruletype_with_goal, ruletype_with_limit, ruletype_with_profile, with_iconography {
    use ruletype_deprecation_filler_trait;

    /** Ignored components. */
    const IGNORED_COMPONENTS = [
        'tool_usertours',
        'quizaccess_seb',
        'block_stash',
    ];

    /** Ignored events. */
    const IGNORED_EVENTS = [
        'core\\event\\blog_external_added',
        'core\\event\\prediction_action_started',
        'mod_glossary\\event\\category_created',
        'mod_quiz\\event\\attempt_started',
    ];

    /** Ignored fragments. */
    const IGNORED_FRAGMENTS = [
        'assessable_',
        '_duplicated',
        'subscription_',
    ];

    public function get_default_limit(): limit_spec {
        return new limit_spec(10, limit_spec::WINDOW_HOURLY);
    }

    public function get_default_repeat_limit(): limit_spec {
        return new limit_spec(0);
    }

    public function get_display_name(): lang_string {
        return new lang_string('ruletypeviewproducecontent', 'block_xp');
    }

    public function get_education_goal(): string {
        return self::GOAL_CONTRIB;
    }

    public function get_icon(): ?icon {
        return new fa_icon('edit');
    }

    public function get_repeat_limit_options(): array {
        return [
            (new repeat_option(limit_spec::SCOPE_ENV))
                ->set_once_label(new lang_string('onceperactivity', 'block_xp'))
                ->set_incompatible_inside_cm(),
        ];
    }

    public function get_profile(): profile {
        return new cm_profile();
    }

    public function get_short_description(): lang_string {
        return new lang_string('ruletypeviewproducecontentdesc', 'block_xp');
    }

    public function is_action_compatible(action $action): bool {
        return $action instanceof crud;
    }

    public function is_action_satisfying_requirements(action $action): bool {
        if (!$action instanceof crud || !$action->is_create()) {
            return false;
        }

        $eventname = trim($action->get_event_name(), '\\');
        $parts = explode('\\event\\', $eventname, 2);
        $component = $parts[0] ?? '';
        $shortname = $parts[1] ?? $eventname;

        if (in_array($eventname, self::IGNORED_EVENTS)) {
            return false;
        } else if (in_array($component, self::IGNORED_COMPONENTS)) {
            return false;
        }

        foreach (self::IGNORED_FRAGMENTS as $fragment) {
            if (strpos($shortname, $fragment) !== false) {
                return false;
            }
        }

        return true;
    }

    public function make_reason(action $action): reason {
        if (!$action instanceof crud) {
            throw new \coding_exception('Incompatible action.');
        }
        return event_reason::from_crud($action);
    }

}
