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
 * Visuals.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/filelib.php');

$courseid = required_param('courseid', PARAM_INT);

require_login($courseid);
$manager = block_xp_manager::get($courseid);
$context = $manager->get_context();

if (!$manager->can_manage()) {
    throw new moodle_exception('nopermissions', '', '', 'can_manage');
}

// Some stuff.
$url = new moodle_url('/blocks/xp/visuals.php', array('courseid' => $courseid));
$strcoursevisuals = get_string('coursevisuals', 'block_xp');

// Page info.
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_title($strcoursevisuals);
$PAGE->set_heading($COURSE->fullname);
$PAGE->set_url($url);

$renderer = $PAGE->get_renderer('block_xp');

$fmoptions = array('subdirs' => 0, 'accepted_types' => array('.jpg', '.png'));
$draftitemid = file_get_submitted_draft_itemid('badges');
file_prepare_draft_area($draftitemid, $context->id, 'block_xp', 'badges', 0, $fmoptions);

$data = new stdClass();
$data->badges = $draftitemid;
$data->enablecustomlevelbadges = $manager->get_config('enablecustomlevelbadges');

$form = new block_xp_visuals_form($url->out(false), array('manager' => $manager, 'fmoptions' => $fmoptions));
$form->set_data($data);

if ($data = $form->get_data()) {
    file_save_draft_area_files($data->badges, $context->id, 'block_xp', 'badges', 0, $fmoptions);
    $manager->update_config(array('enablecustomlevelbadges' => $data->enablecustomlevelbadges));
    redirect($url);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($strcoursevisuals);

echo $renderer->navigation($manager, 'visuals');
echo $renderer->notices($manager);

echo $form->display();

echo $OUTPUT->footer();
