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
$add = optional_param('add', null, PARAM_RAW);

require_login($courseid);
$manager = block_xp_manager::get($courseid);
$context = $manager->get_context();

if (!$manager->can_manage()) {
    throw new moodle_exception('nopermissions', '', '', 'can_manage');
}

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
$filtermanager = $manager->get_filter_manager();
$coursefilters = $filtermanager->get_filters();

// Default filters are not editable.
$defaultfilters = block_xp_filter_manager::get_default_filters(false);

// Saving the data.
if (!empty($_POST['save'])) {
    require_sesskey();

    $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
    $filtermanager->save($filters);

    redirect($url, get_string('changessaved'));
}

// Preparing form.
$dummyruleset = new block_xp_ruleset();
$dummyfilter = block_xp_filter::create_from_data(array('rule' => $dummyruleset));
$templatefilter = $renderer->render($dummyfilter);

// Templates of rules.
$typeruleproperty = new block_xp_rule_property();
$typerulecm = new block_xp_rule_cm($manager->get_courseid());
$typeruleevent = new block_xp_rule_event();
$typeruleset = new block_xp_ruleset();
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

echo $OUTPUT->header();
echo $OUTPUT->heading($strcourserules);

echo $renderer->navigation($manager, 'rules');
echo $renderer->notices($manager);

$a = new stdClass();
$a->list = (new moodle_url('/report/eventlist/index.php'))->out();
$a->log = (new moodle_url('/blocks/xp/log.php', array('courseid' => $courseid)))->out();
$a->doc = (new moodle_url('https://docs.moodle.org/dev/Event_2'))->out();
echo get_string('rulesformhelp', 'block_xp', $a);

echo html_writer::start_div('block-xp-filters-wrapper');
echo html_writer::start_tag('form', array('method' => 'POST', 'class' => 'block-xp-filters'));
echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()));

echo $OUTPUT->heading(get_string('yourownrules', 'block_xp'), 3);

echo $renderer->render($coursefilters);

echo html_writer::start_tag('p');
echo html_writer::empty_tag('input', array('value' => get_string('savechanges'), 'type' => 'submit', 'name' => 'save'));
echo ' ';
echo html_writer::empty_tag('input', array('value' => get_string('cancel'), 'type' => 'submit', 'name' => 'cancel'));
echo html_writer::end_tag('p');
echo html_writer::end_tag('form');

echo $OUTPUT->heading(get_string('defaultrules', 'block_xp'), 3);
echo html_writer::tag('p', get_string('defaultrulesformhelp', 'block_xp'));

echo $renderer->render($defaultfilters);

echo html_writer::end_div();

echo $OUTPUT->footer();
