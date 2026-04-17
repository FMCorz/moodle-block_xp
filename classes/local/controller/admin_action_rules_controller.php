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

namespace block_xp\local\controller;

use block_xp\di;
use block_xp\local\routing\url;
use block_xp\local\rulefilter\rulefilter;
use block_xp\local\ruletype\ruletype;
use html_writer;

/**
 * Admin action rules controller.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_action_rules_controller extends admin_route_controller {

    /** @var string The section name. */
    protected $sectionname = 'block_xp_default_action_rules';

    protected function define_optional_params() {
        return [
            ['reset', false, PARAM_BOOL, false],
            ['confirm', false, PARAM_BOOL, false],
        ];
    }

    protected function pre_content() {
        if ($this->get_param('reset') && confirm_sesskey()) {
            if ($this->get_param('confirm')) {
                di::get('admin_rule_manager')->reset_all_worlds_to_defaults();
                $this->redirect(new url($this->pageurl), get_string('allcoursesreset', 'block_xp'));
            }
        }
    }

    /**
     * Echo the content.
     *
     * @return void
     */
    protected function content() {
        $output = $this->get_renderer();

        if ($this->get_param('reset')) {
            echo $output->confirm_reset(
                get_string('resetallcoursestodefaults', 'block_xp'),
                get_string('reallyresetallcoursestodefaults', 'block_xp'),
                new url($this->pageurl->get_compatible_url(), ['reset' => 1, 'confirm' => 1, 'sesskey' => sesskey()]),
                new url($this->pageurl->get_compatible_url())
            );
            return;
        }

        echo $output->heading(get_string('defaultactionrules', 'block_xp'));

        $this->page_warning_editing_defaults('actionrules');
        echo html_writer::tag('p', get_string('admindefaultactionrulesintro', 'block_xp'));

        echo $output->react_module('block_xp/ui-action-rules-lazy', $this->get_react_props());

        $forwholesite = di::get('config')->get('context') == CONTEXT_SYSTEM;
        if (!$forwholesite) {
            echo $output->heading_with_divider(get_string('dangerzone', 'block_xp'));
            echo html_writer::tag('p', markdown_to_html(get_string('resetallcoursestodefaultsintro', 'block_xp')));
            $url = new url($this->pageurl, ['reset' => 1, 'sesskey' => sesskey()]);
            echo html_writer::tag('p',
                $output->render($output->make_single_button(
                    $url->get_compatible_url(),
                    get_string('resetallcoursestodefaults', 'block_xp'),
                    ['danger' => true]
                ))
            );
        }
    }

    /**
     * Get the React props.
     *
     * @return array
     */
    protected function get_react_props(): array {
        $typeserializer = di::get('serializer_factory')->get_ruletype_serializer();
        $ruletypes = array_values(array_map(function ($type) use ($typeserializer) {
            return $typeserializer->serialize($type);
        }, $this->get_rule_types()));

        $filterhandler = di::get('rule_filter_handler');
        $filterserializer = di::get('serializer_factory')->get_rulefilter_serializer();
        $filters = array_values(array_map(function ($filter) use ($filterserializer) {
            return $filterserializer->serialize($filter);
        }, array_filter($filterhandler->get_filters(), function (rulefilter $filter) {
            return $filter->is_compatible_with_admin();
        })));

        return [
            'childcontext' => null,
            'ruleformclass' => di::get('rule_form_class'),
            'ruletypes' => $ruletypes,
            'rulefilters' => $filters,
        ];
    }

    /**
     * Get the rule types.
     *
     * @return ruletype[]
     */
    protected function get_rule_types() {
        $completiontypes = ['cm_completion', 'course_completion', 'section_completion'];
        $typeresolver = di::get('rule_type_resolver');
        return array_values(array_filter(
            $typeresolver->get_types(),
            function (ruletype $type) use ($completiontypes, $typeresolver) {
                return !in_array($typeresolver->get_type_name($type), $completiontypes);
            }
        ));
    }
}
