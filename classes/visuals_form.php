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
 * Visuals form.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/**
 * Visuals form class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_visuals_form extends moodleform {

    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'badgehdr', get_string('levelbadges', 'block_xp'));

        $mform->addElement('selectyesno', 'enablecustomlevelbadges', get_string('usecustomlevelbadges', 'block_xp'));
        $mform->addHelpButton('enablecustomlevelbadges', 'usecustomlevelbadges', 'block_xp');
        $mform->setDefault('enablecustomlevelbadges', false);

        $mform->addElement('filemanager', 'badges', get_string('levelbadges', 'block_xp'), null, $this->_customdata['fmoptions']);
        $mform->addElement('static', '', '', get_string('levelbadgesformhelp', 'block_xp'));

        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        global $USER;
        $errors = array();

        if ($data['enablecustomlevelbadges']) {
            // Make sure the user has uploaded all the badges.
            $fs = get_file_storage();
            $usercontext = context_user::instance($USER->id);
            $expected = array_flip(range(1, $this->_customdata['manager']->get_level_count()));
            $draftfiles = $fs->get_area_files($usercontext->id, 'user', 'draft', $data['badges'], 'filename', false);
            foreach ($draftfiles as $file) {
                $pathinfo = pathinfo($file->get_filename());
                unset($expected[$pathinfo['filename']]);
            }
            if (count($expected) > 0) {
                $errors['badges'] = get_string('errornotalllevelsbadgesprovided', 'block_xp', implode(', ', array_keys($expected)));
            }
        }

        return $errors;
    }

}
