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
 * Block XP event rules.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$courseid = required_param('courseid', PARAM_INT);

require_login($courseid);
$context = context_course::instance($courseid);

// We need to be able to add this block to edit the course properties.
require_capability('block/xp:addinstance', $context);

// Some stuff.
$url = new moodle_url('/blocks/xp/rules.php', array('courseid' => $courseid));
$strcourserules = get_string('courserules', 'block_xp');

// Page info.
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_title($strcourserules);
$PAGE->set_heading($COURSE->fullname);
$PAGE->set_url($url);

$renderer = $PAGE->get_renderer('block_xp');
$manager = block_xp_manager::get($courseid);
$filters = $manager->get_filter_manager()->get_user_filters();

$form = new block_xp_rules_form($url, array('filters' => $filters,
    'staticfilters' => block_xp_filter_manager::get_static_filters()));

if ($data = $form->get_data()) {

    // New rules.
    foreach ($data->newfilter as $key => $values) {
        if (empty($values['value'])) {
            continue;
        }

        $rule = new block_xp_rule_property($values['compare'], $values['value'], $values['property']);
        $filter = block_xp_filter::load_from_data(array(
            'rule' => $rule,
            'courseid' => $manager->get_courseid(),
            'points' => $values['f_points'],
            'sortorder' => 0,
        ));
        $filter->save();
    }

    // Existing rules.
    if (isset($data->filter)) {
        foreach ($data->filter as $id => $values) {
            if ($id < 1 || !isset($filters[$id])) {
                continue;
            }
            $filter = $filters[$id];

            if (empty($values['value']) && !is_numeric($values['value'])) {
                $filter->delete();
            } else {
                $rule = new block_xp_rule_property($values['compare'], $values['value'], $values['property']);
                $filter->set_rule($rule);
                $filter->set_points($values['f_points']);
                $filter->set_sortorder($values['f_sortorder']);
                $filter->save();
            }
        }
    }

    redirect($url);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($strcourserules);

echo $renderer->navigation($manager, 'rules');

$a = new stdClass();
$a->list = (new moodle_url('/report/eventlist/index.php'))->out();
$a->log = (new moodle_url('/blocks/xp/log.php', array('courseid' => $courseid)))->out();
$a->doc = (new moodle_url('https://docs.moodle.org/dev/Event_2'))->out();
echo get_string('rulesformhelp', 'block_xp', $a);

echo $form->display();

echo $OUTPUT->footer();
