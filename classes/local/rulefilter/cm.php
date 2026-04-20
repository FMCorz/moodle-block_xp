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
use block_xp\local\action\tester\cm_tester;
use block_xp\local\rulefilter\rulefilter;
use context;
use lang_string;

/**
 * Filter.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cm implements rulefilter {

    public function get_action_tester(context $effectivecontext, object $config): action_tester {
        return new cm_tester($config->cmid ?? 0);
    }

    public function get_display_name(): lang_string {
        return new lang_string('rulefiltercm', 'block_xp');
    }

    public function get_short_description(): lang_string {
        return new lang_string('rulefiltercmdesc', 'block_xp');
    }

    public function get_label_for_config(object $config, ?context $effectivecontext = null): string {
        $cmid = $config->cmid ?? 0;
        $context = \context_module::instance($cmid ?? 0, IGNORE_MISSING);
        if (!$context) {
            return get_string('unknownactivitya', 'block_xp', $cmid);
        }

        $coursecontext = $context->get_course_context(false);
        if (!$coursecontext) {
            return get_string('unknownactivitya', 'block_xp', $cmid);
        }

        try {
            $modinfo = get_fast_modinfo($coursecontext->instanceid);
            $cminfo = $modinfo->get_cm($cmid);
            $modname = $cminfo->get_module_type_name();
            return "{$cminfo->name} ({$modname})";
        } catch (\Exception $e) {
            unset($e);
        }

        return get_string('unknownactivitya', 'block_xp', $cmid);
    }

    public function is_compatible_with_admin(): bool {
        return false;
    }

    public function get_compatible_context_levels(): array {
        return [CONTEXT_COURSE];
    }

    public function is_multiple_allowed(): bool {
        return true;
    }

}
