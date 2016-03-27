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
 * Block XP report.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/grouplib.php');

$courseid = required_param('courseid', PARAM_INT);
$userid = optional_param('userid', null, PARAM_INT);
$resetdata = optional_param('resetdata', 0, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHA);
$confirm = optional_param('confirm', 0, PARAM_INT);

require_login($courseid);
$manager = block_xp_manager::get($courseid);
$context = $manager->get_context();

if (!$manager->can_manage()) {
    throw new moodle_exception('nopermissions', '', '', 'can_manage');
}

// Some stuff.
$url = new moodle_url('/blocks/xp/report.php', array('courseid' => $courseid));
if ($action) {
    $url->param('action', $action);
}
$strcoursereport = get_string('coursereport', 'block_xp');

// Page info.
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_title($strcoursereport);
$PAGE->set_heading($COURSE->fullname);
$PAGE->set_url($url);

// Some other stuff.
$renderer = $PAGE->get_renderer('block_xp');
$group = groups_get_course_group($manager->get_course(), true);

// Reset all the data.
if ($resetdata && confirm_sesskey()) {
    if ($confirm) {
        $manager->reset_data($group);
        // Redirect to put the course ID back in the URL, otherwise refresh won't work.
        redirect($url);
    } else {
        // Argh... I hate duplicating code!
        echo $OUTPUT->header();
        echo $OUTPUT->heading($strcoursereport);
        $resetstr = empty($group) ? get_string('reallyresetdata', 'block_xp') : get_string('reallyresetgroupdata', 'block_xp');
        echo $OUTPUT->confirm($resetstr,
            new moodle_url($url, array('resetdata' => 1, 'confirm' => 1, 'sesskey' => sesskey(), 'group' => $group)),
            $url);
        echo $OUTPUT->footer();
        die();
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading($strcoursereport);

echo $renderer->navigation($manager, 'report');
echo $renderer->notices($manager);

// Editing a user.
if ($action == 'edit' && !empty($userid)) {
    $user = core_user::get_user($userid);
    echo $OUTPUT->heading(fullname($user), 3);

    $progress = $manager->get_progress_for_user($userid);
    $form = new block_xp_user_edit_form($url->out(false));
    $form->set_data(array('userid' => $userid, 'level' => $progress->level, 'xp' => $progress->xp));

    if ($data = $form->get_data()) {
        $manager->reset_user_xp($userid, $data->xp);
    } else if (!$form->is_cancelled()) {
        $form->display();
    }
}

groups_print_course_menu($manager->get_course(), $url);

// Displaying the report.
$table = new block_xp_report_table('block_xp_report', $courseid, $group);
$table->define_baseurl($url);

echo $table->out(20, true);

if (empty($group)) {
    $strreset = get_string('resetcoursedata', 'block_xp');
} else {
    $strreset = get_string('resetgroupdata', 'block_xp');
}
echo html_writer::tag('p',
    $OUTPUT->single_button(new moodle_url($url, array('resetdata' => 1, 'sesskey' => sesskey(), 'group' => $group)),
        $strreset, 'get')
);

echo $OUTPUT->footer();
