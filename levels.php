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
 * Block XP level settings.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$courseid = required_param('courseid', PARAM_INT);

require_login($courseid);
$manager = block_xp_manager::get($courseid);
$context = $manager->get_context();

if (!$manager->can_manage()) {
    throw new moodle_exception('nopermissions', '', '', 'can_manage');
}

// Some stuff.
$url = new moodle_url('/blocks/xp/levels.php', array('courseid' => $courseid));
$strlevels = get_string('levels', 'block_xp');

// Page info.
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_title($strlevels);
$PAGE->set_heading($COURSE->fullname);
$PAGE->set_url($url);

$renderer = $PAGE->get_renderer('block_xp');

// Set form and its default (existing) values.
$form = new block_xp_levels_form($url, array('defaultconfig' => block_xp_manager::get_default_config(), 'manager' => $manager));
$form->set_data(array('levels' => $manager->get_level_count(), 'levelsdata' => $manager->get_levels_data()));

if ($data = $form->get_data()) {
    if ($manager->get_level_count() != $data['levels']) {
        // The number of levels have changed, we need to disable the custom badges.
        $data['enablecustomlevelbadges'] = false;
    }
    $manager->update_config($data);
    $manager = block_xp_manager::get($courseid, true);      // Force reload.
    $manager->recalculate_levels();
    redirect(new moodle_url('/blocks/xp/infos.php', array('courseid' => $courseid)), get_string('valuessaved', 'block_xp'));
    die();
} else if ($form->is_cancelled()) {
    redirect(new moodle_url('/blocks/xp/infos.php', array('courseid' => $courseid)));
    die();
}

echo $OUTPUT->header();
echo $OUTPUT->heading($strlevels);
echo $renderer->navigation($manager, 'levels');
echo $renderer->notices($manager);

if ($form->is_submitted() && !$form->is_validated() && !$form->no_submit_button_pressed()) {
    echo $OUTPUT->notification(get_string('errorformvalues', 'block_xp'));
}

echo $form->display();

echo $OUTPUT->footer();
