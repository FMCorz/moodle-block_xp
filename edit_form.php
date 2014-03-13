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
 * Block XP edit form.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block XP edit form class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_edit_form extends block_edit_form {

    /**
     * Form definition.
     *
     * @param moodleform $mform Moodle form.
     * @return void
     */
    protected function specific_definition($mform) {
        $mform->addElement('header', 'confighdr', get_string('configheader', 'block_xp'));
        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_xp'));
        $mform->setDefault('config_title', get_string('levelup', 'block_xp'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('textarea', 'config_description', get_string('configdescription', 'block_xp'));
        $mform->setDefault('config_description', get_string('participatetolevelup', 'block_xp'));
        $mform->setType('config_description', PARAM_TEXT);
    }

}
