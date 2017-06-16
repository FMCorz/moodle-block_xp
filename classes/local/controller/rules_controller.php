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
 * @copyright  2017 Branch Up Pty Ltd
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

/**
 * Rules controller class.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
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

    protected function post_login() {
        parent::post_login();
        $this->filtermanager = $this->world->get_filter_manager();
        $this->userfilters = $this->filtermanager->get_user_filters();
    }

    protected function pre_content() {
        global $PAGE;

        // Saving the data.
        if (!empty($_POST['save'])) {
            require_sesskey();
            $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
            $this->save_filters($filters);
            $this->redirect(null, get_string('changessaved'));
        }

        // Preparing form.
        $renderer = $this->get_renderer();
        $dummyruleset = new \block_xp_ruleset();
        $dummyfilter = \block_xp_filter::load_from_data(array('rule' => $dummyruleset));
        $templatefilter = $renderer->render($dummyfilter);

        // Templates of rules.
        $typeruleproperty = new \block_xp_rule_property();
        $typerulecm = new \block_xp_rule_cm($this->courseid);
        $typeruleevent = new \block_xp_rule_event();
        $typeruleset = new \block_xp_ruleset();
        $templatetypes = array(
            'block_xp_rule_cm' => array(
                'name' => get_string('rulecm', 'block_xp'),
                'template' => $renderer->render($typerulecm, array('iseditable' => true, 'basename' => 'XXXXX')),
            ),
            'block_xp_rule_event' => array(
                'name' => get_string('ruleevent', 'block_xp'),
                'template' => $renderer->render($typeruleevent, array('iseditable' => true, 'basename' => 'XXXXX')),
            ),
            'block_xp_rule_property' => array(
                'name' => get_string('ruleproperty', 'block_xp'),
                'template' => $renderer->render($typeruleproperty, array('iseditable' => true, 'basename' => 'XXXXX')),
            ),
            'block_xp_ruleset' => array(
                'name' => get_string('ruleset', 'block_xp'),
                'template' => $renderer->render($typeruleset, array('iseditable' => true, 'basename' => 'XXXXX')),
            ),
        );

        $PAGE->requires->yui_module('moodle-block_xp-filters', 'Y.M.block_xp.Filters.init', array(array(
            'filter' => $templatefilter,
            'rules' => $templatetypes
        )));
        $PAGE->requires->strings_for_js(array('pickaconditiontype'), 'block_xp');

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

    protected function page_content() {
        $output = $this->get_renderer();

        $logurl = $this->urlresolver->reverse('log', ['courseid' => $this->courseid]);
        $a = new stdClass();
        $a->list = (new moodle_url('/report/eventlist/index.php'))->out();
        $a->log = $logurl->out();
        $a->doc = (new moodle_url('https://docs.moodle.org/dev/Event_2'))->out();
        echo get_string('rulesformhelp', 'block_xp', $a);

        echo html_writer::start_div('block-xp-filters-wrapper');
        echo html_writer::start_tag('form', array('method' => 'POST', 'class' => 'block-xp-filters'));
        echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()));

        $addlink = html_writer::start_tag('li', array('class' => 'filter-add'));
        $addlink .= $output->action_link('#', get_string('addarule', 'block_xp'), null, null,
            new pix_icon('t/add', '', '', array('class' => 'iconsmall')));
        $addlink .= html_writer::end_tag('li');

        echo $output->heading(get_string('yourownrules', 'block_xp'), 3);

        echo html_writer::start_tag('ul', array('class' => 'filters-list filters-editable'));
        echo $addlink;
        foreach ($this->userfilters as $filter) {
            echo $output->render($filter);
            echo $addlink;
        }
        echo html_writer::end_tag('ul');

        echo html_writer::start_tag('p');
        echo html_writer::empty_tag('input', array('value' => get_string('savechanges'), 'type' => 'submit', 'name' => 'save'));
        echo ' ';
        echo html_writer::empty_tag('input', array('value' => get_string('cancel'), 'type' => 'submit', 'name' => 'cancel'));
        echo html_writer::end_tag('p');
        echo html_writer::end_tag('form');

        echo $output->heading(get_string('defaultrules', 'block_xp'), 3);
        echo html_writer::tag('p', get_string('defaultrulesformhelp', 'block_xp'));

        echo html_writer::start_tag('ul', array('class' => 'filters-list filters-readonly'));
        foreach ($this->filtermanager->get_static_filters() as $filter) {
            echo $output->render($filter);

        }
        echo html_writer::end_tag('ul');

        echo html_writer::end_div();
    }

}
