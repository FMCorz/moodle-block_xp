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

namespace block_xp\form;

use block_xp\di;
use block_xp\local\ruletype\limit_spec;
use block_xp\local\ruletype\ruletype;
use block_xp\local\ruletype\ruletype_with_limit;
use block_xp\local\world;
use context;
use context_system;
use core_form\dynamic_form;
use moodle_url;

/**
 * Form.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule extends dynamic_form {

    /** @var object The rule record. */
    protected $rule;
    /** @var ruletype The rule type. */
    protected $ruletype;
    /** @var world The world. */
    protected $world;

    /**
     * Get the rule.
     *
     * @return object The rule record.
     */
    protected function get_rule() {
        if (!isset($this->rule)) {
            $ruleid = $this->optional_param('id', 0, PARAM_INT);
            $this->rule = di::get('db')->get_record('block_xp_rule', ['id' => $ruleid], '*', MUST_EXIST);
        }
        return $this->rule;
    }

    /**
     * Get the rule type.
     *
     * @return ruletype The rule type.
     */
    protected function get_ruletype() {
        if (!isset($this->ruletype)) {
            $this->ruletype = di::get('rule_type_resolver')->get_type($this->get_rule()->type);
            if (!$this->ruletype instanceof ruletype) {
                throw new \moodle_exception('notfound');
            }
        }
        return $this->ruletype;
    }

    protected function get_context_for_dynamic_submission(): context {
        if ($this->is_default_rule()) {
            return context_system::instance();
        }
        return context::instance_by_id($this->get_rule()->contextid);
    }

    /**
     * Get the default data.
     *
     * @return array
     */
    protected function get_default_data(): array {
        $limit = null;
        $ruletype = $this->get_ruletype();
        $repeatscope = limit_spec::SCOPE_NONE;
        $repeatwindow = limit_spec::WINDOW_NONE;

        if ($ruletype instanceof ruletype_with_limit) {
            $limit = $ruletype->get_default_limit();
            $repeatlimit = $ruletype->get_default_repeat_limit();
            $isunlimited = $repeatlimit->get_max() === 0;
            $repeatscope = $isunlimited ? limit_spec::SCOPE_NONE : $repeatlimit->get_scope();
            $repeatwindow = $isunlimited ? $limit->get_time_window() : $repeatlimit->get_time_window();
        }

        return [
            'id' => $this->get_rule()->id,
            'points' => $this->get_rule()->points,
            'limitmax' => $limit ? $limit->get_max() : 0,
            'limitwindow' => $limit ? $limit->get_time_window() : limit_spec::WINDOW_NONE,
            'repeatscope' => $repeatscope,
            'repeatwindow' => $repeatwindow,
        ];
    }

    /**
     * Get the world.
     *
     * @return world
     */
    protected function get_world(): world {
        if (!isset($this->world)) {
            $worldfactory = di::get('context_world_factory');
            $this->world = $worldfactory->get_world_from_context(\context::instance_by_id($this->get_rule()->contextid));
        }
        return $this->world;
    }

    final protected function check_access_for_dynamic_submission(): void {
        if ($this->is_default_rule()) {
            require_capability('moodle/site:config', \context_system::instance());
            return;
        }

        $perms = $this->get_world()->get_access_permissions();
        $perms->require_manage();
    }

    /**
     * Whether we're changing a default rule.
     *
     * @return bool
     */
    final protected function is_default_rule(): bool {
        return empty($this->get_rule()->contextid);
    }

    final public function process_dynamic_submission() {
        $data = $this->get_data();
        if (!$data) {
            return;
        }

        $tx = di::get('db')->start_delegated_transaction();
        $this->save_data($data);
        $tx->allow_commit();
    }

    /**
     * Save the data.
     *
     * @param \stdClass $data The data.
     */
    protected function save_data(\stdClass $data): void {
        $rule = $this->get_rule();
        $rule->points = $data->points;
        di::get('db')->update_record('block_xp_rule', $rule);
    }

    final public function set_data_for_dynamic_submission(): void {
        $this->set_data($this->get_default_data());
        if (!$this->is_default_rule()) {
            di::get('world_rule_manager_factory')->get_rule_manager($this->get_world())->detach();
        }
    }

    protected function get_page_url_for_dynamic_submission(): moodle_url {
        $urlresolver = di::get('url_resolver');
        if ($this->is_default_rule()) {
            return $urlresolver->reverse('admin/actionrules')->get_compatible_url();
        }

        $rule = $this->get_rule();
        $world = $this->get_world();

        $anchorname = '';
        $urlname = 'actionrules';
        if (substr($rule->type, -11) === '_completion') {
            $urlname = 'completionrules';
            $anchorname = $rule->type;
        }

        $url = $urlresolver->reverse($urlname, ['courseid' => $world->get_courseid()]);
        $url->set_anchor($anchorname);

        return $url;
    }

    /**
     * The definition.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setConstant('id', $this->get_rule()->id);

        $mform->addElement('text', 'points', get_string('pointstoaward', 'block_xp'), ['size' => 5]);
        $mform->setType('points', PARAM_INT);
        $mform->addHelpButton('points', 'pointstoaward', 'block_xp');

        $this->add_limit_options();
    }

    /**
     * Add the limit options.
     *
     * @return void
     */
    protected function add_limit_options(): void {
        $mform = $this->_form;
        if (!$this->supports_limits()) {
            return;
        }

        $labelsuffix = '';
        if (!di::get('addon')->is_activated() && di::get('config')->get('enablepromoincourses')) {
            $labelsuffix = ' ' . di::get('renderer')->render_from_template('block_xp/addon-tag', []);
        }

        $timeopts = [
            limit_spec::WINDOW_HOURLY => get_string('perhour', 'block_xp'),
            limit_spec::WINDOW_DAILY => get_string('perday', 'block_xp'),
            limit_spec::WINDOW_WEEKLY => get_string('perweek', 'block_xp'),
            limit_spec::WINDOW_MONTHLY => get_string('permonth', 'block_xp'),
        ];

        $maxopts = array_reduce([2, 3, 5, 7, 10, 12, 15, 20, 50, 100], function ($acc, $value) {
            $acc[$value] = get_string('ntimes', 'block_xp', $value);
            return $acc;
        }, [
            0 => get_string('nolimit', 'block_xp'),
            1 => get_string('once', 'block_xp'),
        ]);

        $mform->addElement('group', 'limitgroup', get_string('overalllimit', 'block_xp') . $labelsuffix, [
            $mform->createElement('select', 'limitmax', get_string('timesallowed', 'block_xp'), $maxopts),
            $mform->createElement('select', 'limitwindow', get_string('timeframe', 'block_xp'), $timeopts + [
                limit_spec::WINDOW_NONE => get_string('intotal', 'block_xp'),
            ]),
        ], ' ', false);
        $mform->addHelpButton('limitgroup', 'overalllimit', 'block_xp');
        $mform->hideIf('limitgroup', 'points', 'eq', 0);
        $mform->hideIf('limitwindow', 'limitmax', 'eq', 0);

        $repeatoptions = $this->get_repeat_limit_options();
        if (count($repeatoptions) > 0) {
            $selectopts = array_reduce($repeatoptions, function ($acc, $option) {
                $acc[$option->get_value()] = $option->get_once_label();
                return $acc;
            }, [limit_spec::SCOPE_NONE => get_string('unlimitedrepeats', 'block_xp')]);
            $mform->addElement('group', 'repeatgroup', get_string('repetitionlimit', 'block_xp') . $labelsuffix, [
                $mform->createElement('select', 'repeatscope', get_string('repeatsallowed', 'block_xp'), $selectopts),
                $mform->createElement('select', 'repeatwindow', get_string('repetitiontimeframe', 'block_xp'), $timeopts + [
                    limit_spec::WINDOW_NONE => get_string('forever', 'block_xp'),
                ]),
            ], ' ', false);
            $mform->addHelpButton('repeatgroup', 'repetitionlimit', 'block_xp');
            $mform->hideIf('repeatgroup', 'points', 'eq', 0);
            $mform->hideIf('repeatwindow', 'repeatscope', 'eq', limit_spec::SCOPE_NONE);
        }

        if (!di::get('addon')->is_activated()) {
            if (!di::get('config')->get('enablepromoincourses')) {
                $mform->removeElement('limitgroup');
                $mform->removeElement('repeatgroup');
            } else {
                $mform->addElement(html::name(), 'jssink', function () use ($mform) {
                    global $PAGE;
                    $formid = (string) $mform->getAttribute('id');
                    $PAGE->requires->js_amd_inline(<<<EOT
                        require([], function() {
                            const root = document.getElementById('{$formid}');
                            if (!root) {
                                return;
                            }
                            root
                            .querySelectorAll('[name=limitmax],[name=limitwindow],[name=repeatscope],[name=repeatwindow]')
                            .forEach(function(el) {
                                el.querySelectorAll('option').forEach(function(option) {
                                    if (option.matches(':checked')) {
                                        return;
                                    }
                                    option.setAttribute('disabled', 'disabled');
                                });
                            });
                        });
                    EOT);
                });
            }
        }

        $mform->addElement(html::name(), 'jsrepeatgroup', function () use ($mform) {
            global $PAGE;
            $formid = (string) $mform->getAttribute('id');
            $windownone = (int) limit_spec::WINDOW_NONE;
            $PAGE->requires->js_amd_inline(<<<EOT
                require([], function() {
                    var formEl = document.getElementById('{$formid}');
                    var WINDOW_NONE = '{$windownone}';
                    function syncRepeatGroupVisibility(formRoot) {
                        if (!formRoot) {
                            return;
                        }
                        var limitmax = formRoot.querySelector('[name="limitmax"]');
                        var limitwindow = formRoot.querySelector('[name="limitwindow"]');
                        var repeatGroup = formRoot.querySelector('[data-groupname="repeatgroup"]');
                        if (!limitmax || !limitwindow || !repeatGroup) {
                            return;
                        }
                        var hide = limitmax.value === '1' && limitwindow.value === String(WINDOW_NONE);
                        repeatGroup.classList.toggle('xp-hidden', hide);
                    }
                    if (!formEl) {
                        return;
                    }
                    formEl.addEventListener('change', function(e) {
                        var t = e.target;
                        if (!t || !t.name) {
                            return;
                        }
                        if (t.name !== 'limitmax' && t.name !== 'limitwindow') {
                            return;
                        }
                        syncRepeatGroupVisibility(formEl);
                    });
                    syncRepeatGroupVisibility(formEl);
                });
            EOT);
        });
    }

    /**
     * Get the repeat limit options.
     *
     * @return array
     */
    protected function get_repeat_limit_options(): array {
        $ruletype = $this->get_ruletype();
        if (!$ruletype instanceof ruletype_with_limit) {
            return [];
        }
        return array_values(array_filter($ruletype->get_repeat_limit_options(), function ($option) {
            return $option->is_compatible_inside_cm() || $this->get_rule()->filter !== 'cm';
        }));
    }

    /**
     * Whether the rule type supports limits.
     *
     * @return bool
     */
    protected function supports_limits(): bool {
        return $this->get_ruletype() instanceof ruletype_with_limit;
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if ($data['points'] < 0 || $data['points'] > 9999999) {
            $errors['points'] = get_string('invaliddata', 'core_error');
        }

        return $errors;
    }

}
