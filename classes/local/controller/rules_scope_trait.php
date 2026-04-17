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
 * Rules scope trait.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;

use block_xp\local\routing\url;
use context;
use help_icon;
use moodle_url;

/**
 * Rules scope trait.
 *
 * This trait is to be used within a compatible controller.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait rules_scope_trait {

    /**
     * Whether having a child context is allowed.
     *
     * @return bool
     */
    protected function can_have_child_context() {
        $worldcontext = $this->world->get_context();
        return $worldcontext->contextlevel == CONTEXT_SYSTEM;
    }

    /**
     * Get the child context.
     *
     * @return context|null
     */
    protected function get_child_context() {
        if (!$this->can_have_child_context()) {
            return null;
        }

        $childcontextid = $this->get_param('childcontextid');
        if (!$childcontextid) {
            return null;
        }

        // Confirm is a valid context.
        $context = context::instance_by_id($childcontextid, IGNORE_MISSING);
        $worldcontext = $this->world->get_context();
        if (!$context || $context->contextlevel != CONTEXT_COURSE ||
                !$worldcontext->is_parent_of($context, false)
        ) {
            return null;
        }

        // Validate access to the course by the current user.
        $modinfo = get_fast_modinfo($context->instanceid);
        if (!can_access_course($modinfo->get_course(), null, '', true)) {
            return null;
        }

        return $context;
    }

    /**
     * Get the current context.
     *
     * This is the context that is active and authorised.
     *
     * @return context
     */
    protected function get_current_context() {
        $childcontext = $this->get_child_context();
        return $childcontext ?? $this->world->get_context();
    }

    /**
     * Output the scope switcher.
     *
     * @return void
     */
    protected function page_scope_switcher() {
        if (!$this->can_have_child_context()) {
            return;
        }

        $output = $this->get_renderer();
        $childcontext = $this->get_child_context();
        $childctxhelp = new help_icon('rulesscope', 'block_xp');

        $scopeurl = null;
        $sitewideurl = new url($this->pageurl);
        $sitewideurl->remove_params(['childcontextid']);
        $courseurltemplate = new url($this->pageurl);
        $courseurltemplate->param('childcontextid', "CONTEXTID");

        if ($childcontext) {
            $scopeurl = new moodle_url('/course/view.php', ['id' => $childcontext->instanceid]);
            $scopename = get_string('coursea', 'block_xp', $childcontext->get_context_name(false, true));
        } else {
            $scopename = get_string('sitewide', 'block_xp');
        }

        echo $output->render_from_template('block_xp/rules-scope-switcher', [
            'isincourse' => (bool) $childcontext,
            'sitewideurl' => $sitewideurl->out(false),
            'contexturl' => $courseurltemplate->out(false),
            'courseurltemplate' => $courseurltemplate->out(false),
            'scopename' => $scopename,
            'scopeurl' => $scopeurl ? $scopeurl->out(false) : null,
            'helpicon' => $childctxhelp->export_for_template($output),
        ]);
    }

}
