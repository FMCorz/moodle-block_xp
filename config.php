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
 * Block XP settings.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$resetdata = optional_param('resetdata', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);

require_login($courseid);
$manager = block_xp_manager::get($courseid);
$context = $manager->get_context();

if (!$manager->can_manage()) {
    throw new moodle_exception('nopermissions', '', '', 'can_manage');
}

// Some stuff.
$url = new moodle_url('/blocks/xp/config.php', array('courseid' => $courseid));
$strcoursesettings = get_string('coursesettings', 'block_xp');

// Page info.
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_title($strcoursesettings);
$PAGE->set_heading($COURSE->fullname);
$PAGE->set_url($url);

echo $OUTPUT->header();
echo $OUTPUT->heading($strcoursesettings);

$renderer = $PAGE->get_renderer('block_xp');

$form = new block_xp_settings_form($url->out(false), array('defaultconfig' => block_xp_manager::get_default_config()));
if ($data = $form->get_data()) {
    $manager->update_config($data);
} else {
    $form->set_data((array) $manager->get_config());
}

echo $renderer->navigation($manager, 'config');
echo $renderer->notices($manager);

echo $form->display();

echo $OUTPUT->footer();
