<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Rules controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

use html_writer;
use moodle_exception;
use moodle_url;
use pix_icon;
use stdClass;
use block_xp\local\routing\url;

/**
 * Rules controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rules_controller extends page_controller {

    /** @var string The route name. */
    protected $routename = 'rules';

    /** @var moodleform The form. */
    protected $form;

    /** @var \block_xp\local\course_filter_manager The filter manager. */
    protected $filtermanager;

    /** @var array User filters. */
    protected $userfilters;

    protected function define_optional_params() {
        return [
            ['reset', false, PARAM_BOOL, false],
            ['confirm', false, PARAM_BOOL, false]
        ];
    }

    protected function post_login() {
        parent::post_login();
        $this->filtermanager = $this->world->get_filter_manager();
        $this->userfilters = $this->filtermanager->get_user_filters();
    }

    protected function pre_content() {
        global $PAGE;

        // Reset course rules to defaults.
        if ($this->get_param('reset') && confirm_sesskey()) {
            if ($this->get_param('confirm')) {
                $this->world->reset_filters_to_defaults();
                $this->redirect(new url($this->pageurl));
            }
        }

        // Saving the data.
        if (!empty($_POST['save'])) {
            require_sesskey();
            $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
            $this->save_filters($filters);
            $this->redirect(null, get_string('changessaved'));

        } else if (!empty($_POST['cancel'])) {
            $this->redirect();
        }
    }

    protected function save_filters($filters) {
        $filterids = array();
        foreach ($filters as $filterdata) {
            $data = $filterdata;
            $data['ruledata'] = json_encode($data['rule'], true);
            unset($data['rule']);
            $data['courseid'] = $this->courseid;

            if (!\block_xp_filter::validate_data($data)) {
                throw new moodle_exception('Data could not be validated');
            }

            $filter = \block_xp_filter::load_from_data($data);
            if ($filter->get_id() && !array_key_exists($filter->get_id(), $this->userfilters)) {
                throw new moodle_exception('Invalid filter ID');
            }

            $filter->save();
            $filterids[$filter->get_id()] = true;
        }

        // Check for filters to be deleted.
        foreach ($this->userfilters as $filterid => $filter) {
            if (!array_key_exists($filterid, $filterids)) {
                $filter->delete();
            }
            unset($this->userfilters[$filterid]);
        }

        $this->filtermanager->invalidate_filters_cache();
    }

    protected function get_page_html_head_title() {
        return get_string('courserules', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('courserules', 'block_xp');
    }

    /**
     * Get available rules.
     *
     * @return array
     */
    protected function get_available_rules() {
        return [
            (object) [
                'name' => get_string('ruleevent', 'block_xp'),
                'rule' => new \block_xp_rule_event(),
            ],
            (object) [
                'name' => get_string('rulecm', 'block_xp'),
                'rule' => new \block_xp_rule_cm($this->courseid),
            ],
            (object) [
                'name' => get_string('ruleproperty', 'block_xp'),
                'rule' => new \block_xp_rule_property(),
            ],
            (object) [
                'name' => get_string('ruleset', 'block_xp'),
                'rule' => new \block_xp_ruleset(),
            ]
        ];
    }

    /**
     * Get default filters.
     *
     * @return block_xp_filter
     */
    protected function get_default_filter() {
        return \block_xp_filter::load_from_data(['rule' => new \block_xp_ruleset()]);
    }

    /**
     * Get widget.
     *
     * @return renderable
     */
    protected function get_widget() {
        return new \block_xp\output\filters_widget(
            $this->get_default_filter(),
            $this->get_available_rules(),
            $this->userfilters
        );
    }

    protected function page_content() {
        $output = $this->get_renderer();

        if ($this->get_param('reset')) {
            echo $output->confirm(
                get_string('reallyresetcourserulestodefaults', 'block_xp'),
                new url($this->pageurl->get_compatible_url(), ['reset' => 1, 'confirm' => 1, 'sesskey' => sesskey()]),
                new url($this->pageurl->get_compatible_url())
            );
            return;
        }

        $logurl = $this->urlresolver->reverse('log', ['courseid' => $this->courseid]);
        $a = new stdClass();
        $a->list = (new moodle_url('/report/eventlist/index.php'))->out();
        $a->log = $logurl->out();
        $a->doc = (new moodle_url('https://docs.moodle.org/dev/Event_2'))->out();
        echo get_string('rulesformhelp', 'block_xp', $a);

        echo $output->render($this->get_widget());

        $this->page_danger_zone_content();

        // TODO Change the introduction.
        // TODO Decide whether we want to separate the "default" rules from the rest.
        // TODO Decide whether we want to be able to "unlock" the default rules.

    }

    protected function page_danger_zone_content() {
        $forwholesite = \block_xp\di::get('config')->get('context') == CONTEXT_SYSTEM;
        $output = $this->get_renderer();

        echo html_writer::tag('div', $output->heading(get_string('dangerzone', 'block_xp'), 3),
            ['style' => 'margin-top: 2em']);

        $url = new url($this->pageurl, ['reset' => 1, 'sesskey' => sesskey()]);
        echo html_writer::tag('p',
            $output->single_button(
                $url->get_compatible_url(),
                get_string('resetcourserulestodefaults', 'block_xp'),
                'get'
            )
        );
    }

}
