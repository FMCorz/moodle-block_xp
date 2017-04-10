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
 * Block XP set default rules page.
 *
 * @package    block_xp
 * @copyright  2017 Rubén Cancho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->libdir . '/adminlib.php');

$PAGE->set_url(new moodle_url('/blocks/xp/settings_rules.php'));
$PAGE->set_context(context_system::instance());
$PAGE->navbar->add(get_string('administrationsite'));
$PAGE->navbar->add(get_string('plugins', 'admin'));
$PAGE->navbar->add(get_string('blocks'));
$PAGE->navbar->add(get_string('pluginname', 'block_xp'), new moodle_url('/admin/settings.php?section=blocksettingxp'));

// TODO: add string...
$PAGE->navbar->add('Set default rules');
$PAGE->set_heading('Set default rules');
require_login();
require_capability('moodle/site:config', context_system::instance());

$renderer = $PAGE->get_renderer('block_xp');

$filtermanager = new block_xp_filter_manager();

$url = new moodle_url('/blocks/xp/settings_rules.php', array('courseid' => 0));

// Saving the data.
if (!empty($_POST['save'])) {
    require_sesskey();

    $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
    $filtermanager->save($filters);

    redirect($url, get_string('changessaved'));
}

// Reset default filters with static filters
if(!empty($_POST['reset'])) {
    require_sesskey();

    $filtermanager->save_default_filters();

    redirect($url, get_string('changessaved'));
}

$defaultfilters = $filtermanager->get_default_filters(true);

// Preparing form.
$dummyruleset = new block_xp_ruleset();
$dummyfilter = block_xp_filter::create_from_data(array('rule' => $dummyruleset));
$templatefilter = $renderer->render($dummyfilter);

// Templates of rules.
$typeruleproperty = new block_xp_rule_property();
$typerulecm = new block_xp_rule_cm(0);
$typeruleevent = new block_xp_rule_event();
$typeruleset = new block_xp_ruleset();
$templatetypes = array(
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

echo html_writer::start_div('block-xp-filters-wrapper');
echo html_writer::start_tag('form', array('method' => 'POST', 'class' => 'block-xp-filters'));
echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()));

echo $OUTPUT->heading(get_string('defaultrules', 'block_xp'), 3);
echo html_writer::tag('p', get_string('defaultrulesformhelp', 'block_xp'));

echo $renderer->render($defaultfilters);

echo html_writer::start_tag('p');
echo html_writer::empty_tag('input', array('value' => get_string('savechanges'), 'type' => 'submit', 'name' => 'save'));
echo ' ';

// TODO: add a javascript warning before reseting to static filters.
echo html_writer::empty_tag('input', array('value' => get_string('reset'), 'type' => 'submit', 'name' => 'reset'));
echo ' ';
echo html_writer::empty_tag('input', array('value' => get_string('cancel'), 'type' => 'submit', 'name' => 'cancel'));
echo html_writer::end_tag('p');
echo html_writer::end_tag('form');

echo html_writer::end_div();

echo $OUTPUT->footer();