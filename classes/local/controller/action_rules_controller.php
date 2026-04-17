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
use help_icon;

/**
 * Rules controller.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class action_rules_controller extends page_controller {
    use rules_scope_trait;

    /** @var string The nav name. */
    protected $navname = 'rules';
    /** @var string The route name. */
    protected $routename = 'actionrules';

    protected function define_optional_params() {
        return [
            ['childcontextid', null, PARAM_INT],
            ['reset', false, PARAM_BOOL, false],
            ['confirm', false, PARAM_BOOL, false],
        ];
    }

    protected function pre_content() {
        if ($this->get_param('reset') && confirm_sesskey()) {
            if ($this->get_param('confirm')) {
                di::get('world_rule_manager_factory')->get_rule_manager($this->world)->reset_to_defaults();
                $this->redirect(new url($this->pageurl));
            }
        }

        $manager = di::get('world_rule_manager_factory')->get_rule_manager($this->world);
        $manager->seed_for_editing();
    }

    protected function get_page_html_head_title() {
        return get_string('actionrules', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('actionrules', 'block_xp');
    }

    protected function page_content() {
        $output = $this->get_renderer();

        if ($this->get_param('reset')) {
            echo $output->confirm_reset(
                get_string('resettodefaults', 'block_xp'),
                get_string('reallyresetcourserulestodefaults', 'block_xp'),
                new url($this->pageurl->get_compatible_url(), ['reset' => 1, 'confirm' => 1, 'sesskey' => sesskey()]),
                new url($this->pageurl->get_compatible_url())
            );
            return;
        }

        $reseturl = new url($this->pageurl, ['reset' => 1, 'sesskey' => sesskey()]);
        echo $output->advanced_heading(get_string('actionrules', 'block_xp'), [
            'intro' => new \lang_string('actionrulesintro', 'block_xp'),
            'help' => new help_icon('actionrules', 'block_xp'),
            'menu' => [
                [
                    'label' => get_string('resettodefaults', 'block_xp'),
                    'danger' => true,
                    'href' => $reseturl,
                ],
            ],
        ]);

        $this->page_scope_switcher();

        echo $output->react_module('block_xp/ui-action-rules-lazy', $this->get_react_props(), $this->world);
    }

    /**
     * Get the React props.
     *
     * @return array
     */
    protected function get_react_props(): array {
        $childcontext = $this->get_child_context();
        $currentcontext = $this->get_current_context();

        $childcontextdata = null;
        if ($childcontext) {
            $childcontextdata = [
                "id" => (int) $childcontext->id,
                'contextlevel' => (int) $childcontext->contextlevel,
                'instanceid' => (int) $childcontext->instanceid,
            ];
        }

        $typeserializer = di::get('serializer_factory')->get_ruletype_serializer();
        $ruletypes = array_values(array_map(function ($type) use ($typeserializer) {
            return $typeserializer->serialize($type);
        }, $this->get_rule_types()));

        $filterhandler = di::get('rule_filter_handler');
        $filterserializer = di::get('serializer_factory')->get_rulefilter_serializer();
        $filters = array_values(array_map(function ($filter) use ($filterserializer) {
            return $filterserializer->serialize($filter);
        }, array_filter($filterhandler->get_filters(), function (rulefilter $filter) use ($currentcontext) {
            return in_array((int) $currentcontext->contextlevel, $filter->get_compatible_context_levels());
        })));

        return [
            'childcontext' => $childcontextdata,
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
        $typeresolver = di::get('rule_type_resolver');
        return $typeresolver->get_types();
    }
}
