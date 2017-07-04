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

namespace block_xp\form;
defined('MOODLE_INTERNAL') || die();

use block_edit_form;

/**
 * Block XP edit form class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class edit_form extends block_edit_form {

    /**
     * Form definition.
     *
     * @param moodleform $mform Moodle form.
     * @return void
     */
    protected function specific_definition($mform) {
        // Not nice to use DI here, but we do not control when this instance is created.
        $config = \block_xp\di::get('config');

        $mform->addElement('header', 'confighdr', get_string('configheader', 'block_xp'));
        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_xp'));
        $mform->setDefault('config_title', $config->get('blocktitle'));
        $mform->addHelpButton('config_title', 'configtitle', 'block_xp');
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('textarea', 'config_description', get_string('configdescription', 'block_xp'));
        $mform->setDefault('config_description', $config->get('blockdescription'));
        $mform->addHelpButton('config_description', 'configdescription', 'block_xp');
        $mform->setType('config_description', PARAM_TEXT);

        $mform->addElement('select', 'config_recentactivity', get_string('configrecentactivity', 'block_xp'), [
            0 => get_string('no'),
            3 => get_string('yes'),
        ]);
        $mform->setDefault('config_recentactivity', $config->get('blockrecentactivity'));
        $mform->addHelpButton('config_recentactivity', 'configrecentactivity', 'block_xp');
        $mform->setType('config_recentactivity', PARAM_INT);
    }

}
