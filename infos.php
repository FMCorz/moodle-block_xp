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
require_once($CFG->libdir . '/tablelib.php');

$courseid = required_param('courseid', PARAM_INT);

require_login($courseid);
$manager = block_xp_manager::get($courseid);
$context = $manager->get_context();

if (!$manager->can_view_infos_page()) {
    throw new moodle_exception('nopermissions', '', '', 'view_infos_page');
}

// Some stuff.
$url = new moodle_url('/blocks/xp/infos.php', array('courseid' => $courseid));
$strinfos = get_string('infos', 'block_xp');

// Page info.
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_title($strinfos);
$PAGE->set_heading($COURSE->fullname);
$PAGE->set_url($url);

$renderer = $PAGE->get_renderer('block_xp');

echo $OUTPUT->header();
echo $OUTPUT->heading($strinfos);
echo $renderer->navigation($manager, 'infos');
echo $renderer->notices($manager);

$levels = $manager->get_level_count();
$levelsdata = $manager->get_levels_data();

$table = new flexible_table('xpinfos');
$table->define_baseurl($url);
$table->define_columns(array('level', 'xp', 'desc'));
$table->define_headers(array(get_string('level', 'block_xp'), get_string('xprequired', 'block_xp'),
    get_string('description', 'block_xp')));
$table->setup();

for ($i = 1; $i <= $levels ; $i++) {
    $desc = isset($levelsdata['desc'][$i]) ? $levelsdata['desc'][$i] : '';
    $table->add_data(array($i, $levelsdata['xp'][$i], $desc), 'level-' . $i);
}

$table->finish_output();

if ($manager->can_manage()) {
    echo html_writer::tag('p',
        $OUTPUT->single_button(new moodle_url('/blocks/xp/levels.php', array('courseid' => $courseid)),
            get_string('customizelevels', 'block_xp'), 'get')
    );
}

echo $OUTPUT->footer();
