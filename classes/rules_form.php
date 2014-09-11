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
 * Rules form.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/**
 * Rules form.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_rules_form extends moodleform {

    /**
     * Add a group element for the filter.
     *
     * This helper got messy...
     *
     * @param block_xp_filter $filter The filter to use, or null to add a filter.
     * @param block_xp_filter $newfilter The filter to use to populate the new filter.
     * @return void
     */
    protected function create_group($filter = null, $newfilter = null) {
        static $noneditableid = -1;
        static $newfilterid = 1;

        $mform = $this->_form;
        $static = false;
        $points = $newfilter ? $newfilter->get_points() : 0;
        $sortorder = $newfilter ? $newfilter->get_sortorder() : 0;
        $ruledata = array(
            'property' => '',
            'compare' => 'eq',
            'value' => ''
        );
        $name = 'newfilter[' . $newfilterid++ . ']';

        if (!$filter && $newfilter) {
            $ruledata = $newfilter->get_rule()->export();
        }

        if ($filter) {
            $static = !$filter->is_editable();
            $id = $static ? $noneditableid-- : $filter->get_id();
            $name = 'filter[' . $id . ']';
            $points = $filter->get_points();
            $sortorder = $filter->get_sortorder();
            $rule = $filter->get_rule();

            if (!$rule instanceof block_xp_rule_property) {
                throw new coding_exception('Only property rules are currently supported.');
            }

            $ruledata = $rule->export();
        }

        $el1 = $mform->createElement('select', 'property', get_string('eventproperty', 'block_xp'), array(
            'eventname' => get_string('property:eventname', 'block_xp'),
            'component' => get_string('property:component', 'block_xp'),
            'action' => get_string('property:action', 'block_xp'),
            'target' => get_string('property:target', 'block_xp'),
            'crud' => get_string('property:crud', 'block_xp'),
        ));
        $el2 = $mform->createElement('select', 'compare', get_string('comparisonrule', 'block_xp'), array(
            block_xp_rule_base::CT => get_string('rule:' . block_xp_rule_base::CT, 'block_xp'),
            block_xp_rule_base::EQ => get_string('rule:' . block_xp_rule_base::EQ, 'block_xp'),
        ));
        $el3 = $mform->createElement('text', 'value', get_string('value', 'block_xp'));
        $el4 = $mform->createElement('text', 'f_points', get_string('xp', 'block_xp'), array('size' => '3', 'class' => 'pts'));
        $el5 = $mform->createElement('text', 'f_sortorder', 'sortorder', array('size' => '2', 'class' => 'sortorder'));

        $elgroup = array($el1, $el2, $el3, $el4);
        if (!$static && $filter) {
            array_unshift($elgroup, $el5);
        }

        $group = $mform->addElement('group', $name, '', $elgroup);
        $mform->setDefault($name . '[property]', $ruledata['property']);
        $mform->setDefault($name . '[compare]', $ruledata['compare']);
        $mform->setDefault($name . '[value]', $ruledata['value']);
        $mform->setDefault($name . '[f_points]', $points);
        $mform->setDefault($name . '[f_sortorder]', $sortorder);
        $mform->setType($name . '[value]', PARAM_RAW);
        $mform->setType($name . '[f_points]', PARAM_INT);
        $mform->setType($name . '[f_sortorder]', PARAM_INT);

        if ($static) {
            $mform->hardFreeze($name);
        }

    }

    /**
     * Definintion.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        $filters = $this->_customdata['filters'];
        $staticfilters = $this->_customdata['staticfilters'];

        // Add empty rule.
        $mform->addElement('header', 'newhdr', 'Add new rules');
        $mform->addElement('static', '', '', get_string('addrulesformhelp', 'block_xp'));

        if ($this->_customdata['add']) {
            $filteradd = block_xp_filter::load_from_data(array(
                'rule' => new block_xp_rule_property(block_xp_rule_base::EQ, $this->_customdata['add'], 'eventname')
            ));
            $this->create_group(null, $filteradd);
        } else {
            $this->create_group();
        }
        $this->create_group();
        $this->create_group();

        // Add existing filters.
        if ($filters) {
            $mform->addElement('header', 'existhdr', 'Existing rules');
            $mform->setExpanded('existhdr', true);
            $mform->addElement('static', '', '', get_string('existingruleformhelp', 'block_xp'));
            foreach ($filters as $filter) {
                $this->create_group($filter);
            }
        }

        $mform->addElement('header', 'defaulthdr', 'Default rules');
        $mform->addElement('static', '', '', get_string('defaultrulesformhelp', 'block_xp'));
        foreach ($staticfilters as $filter) {
            $this->create_group($filter);
        }

        $this->add_action_buttons();
    }

}
