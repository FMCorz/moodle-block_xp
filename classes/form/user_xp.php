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
 * Block XP user edit form.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\form;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

use moodleform;

/**
 * Block XP user edit form class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user_xp extends moodleform {

    /**
     * Form definintion.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;
        $mform->setDisableShortforms(true);

        $mform->addElement('hidden', 'userid');
        $mform->setType('userid', PARAM_INT);

        $mform->addElement('text', 'level', get_string('level', 'block_xp'));
        $mform->setType('level', PARAM_INT);
        $mform->hardFreeze('level');

        $mform->addElement('text', 'xp', get_string('total', 'block_xp'));
        $mform->setType('xp', PARAM_INT);

        $this->add_action_buttons();
    }

    /**
     * Data validate.
     *
     * @param array $data The data submitted.
     * @param array $files The files submitted.
     * @return array of errors.
     */
    public function validation($data, $files) {
        $errors = array();

        // Validating the XP points.
        $xp = (int) $data['xp'];
        if ($xp < 0) {
            $errors['xp'] = get_string('invalidxp', 'block_xp');
        }

        return $errors;
    }

}
