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

$courseid = required_param('courseid', PARAM_INT);

require_login($courseid);
$manager = block_xp_manager::get($courseid);
$context = $manager->get_context();

if (!$manager->can_manage()) {
    throw new moodle_exception('nopermissions', '', '', 'can_manage');
}

// Some stuff.
$url = new moodle_url('/blocks/xp/log.php', array('courseid' => $courseid));
$strcoursereport = get_string('courselog', 'block_xp');

// Page info.
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_title($strcoursereport);
$PAGE->set_heading($COURSE->fullname);
$PAGE->set_url($url);

// Some other stuff.
$renderer = $PAGE->get_renderer('block_xp');
$group = groups_get_course_group($manager->get_course(), true);

$table = new block_xp_log_table('block_xp_log', $courseid, $group);
$table->define_baseurl($url);

echo $OUTPUT->header();
echo $OUTPUT->heading($strcoursereport);
echo $renderer->navigation($manager, 'log');
echo $renderer->notices($manager);

groups_print_course_menu($manager->get_course(), $url);

echo $table->out(50, true);

echo $OUTPUT->footer();
