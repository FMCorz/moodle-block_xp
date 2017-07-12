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
 * Block XP config form.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\form;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');
require_once(__DIR__ . '/itemspertime.php');
require_once(__DIR__ . '/duration.php');

use block_xp\local\config\course_world_config;
use moodleform;

/**
 * Block XP config form class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config extends moodleform {

    /**
     * Form definition.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;
        $mform->setDisableShortforms(true);

        $mform->addElement('header', 'hdrgeneral', get_string('general'));

        $mform->addElement('selectyesno', 'enabled', get_string('enablexpgain', 'block_xp'));
        $mform->addHelpButton('enabled', 'enablexpgain', 'block_xp');

        $mform->addElement('selectyesno', 'enableinfos', get_string('enableinfos', 'block_xp'));
        $mform->addHelpButton('enableinfos', 'enableinfos', 'block_xp');

        $mform->addElement('selectyesno', 'enablelevelupnotif', get_string('enablelevelupnotif', 'block_xp'));
        $mform->addHelpButton('enablelevelupnotif', 'enablelevelupnotif', 'block_xp');

        $mform->addElement('header', 'hdrladder', get_string('ladder', 'block_xp'));

        $mform->addElement('selectyesno', 'enableladder', get_string('enableladder', 'block_xp'));
        $mform->addHelpButton('enableladder', 'enableladder', 'block_xp');

        $mform->addElement('select', 'identitymode', get_string('anonymity', 'block_xp'), array(
            course_world_config::IDENTITY_OFF => get_string('hideparticipantsidentity', 'block_xp'),
            course_world_config::IDENTITY_ON => get_string('displayparticipantsidentity', 'block_xp'),
        ));
        $mform->addHelpButton('identitymode', 'anonymity', 'block_xp');
        $mform->disabledIf('identitymode', 'enableladder', 'eq', 0);

        $mform->addElement('select', 'neighbours', get_string('limitparticipants', 'block_xp'), array(
            0 => get_string('displayeveryone', 'block_xp'),
            1 => get_string('displayoneneigbour', 'block_xp'),
            2 => get_string('displaynneighbours', 'block_xp', 'two'),
            3 => get_string('displaynneighbours', 'block_xp', 'three'),
            4 => get_string('displaynneighbours', 'block_xp', 'four'),
            5 => get_string('displaynneighbours', 'block_xp', 'five'),
        ));
        $mform->addHelpButton('neighbours', 'limitparticipants', 'block_xp');
        $mform->disabledIf('neighbours', 'enableladder', 'eq', 0);

        $mform->addElement('select', 'rankmode', get_string('ranking', 'block_xp'), array(
            course_world_config::RANK_OFF => get_string('hiderank', 'block_xp'),
            course_world_config::RANK_ON => get_string('displayrank', 'block_xp'),
            course_world_config::RANK_REL => get_string('displayrelativerank', 'block_xp'),
        ));
        $mform->addHelpButton('rankmode', 'ranking', 'block_xp');
        $mform->disabledIf('rankmode', 'enableladder', 'eq', 0);

        $el = $mform->addElement('select', 'laddercols', get_string('ladderadditionalcols', 'block_xp'), [
            'xp' => get_string('xp', 'block_xp'),
            'progress' => get_string('progress', 'block_xp'),
        ], ['style' => 'height: 4em;']);
        $el->setMultiple(true);
        $mform->addHelpButton('laddercols', 'ladderadditionalcols', 'block_xp');

        $mform->addElement('header', 'hdrcheating', get_string('cheatguard', 'block_xp'));

        $mform->addElement('selectyesno', 'enablecheatguard', get_string('enablecheatguard', 'block_xp'));

        $mform->addElement('block_xp_form_itemspertime', 'maxactionspertime', get_string('maxactionspertime', 'block_xp'), [
            'maxunit' => HOURSECS,
            'itemlabel' => get_string('actions', 'block_xp')
        ]);
        $mform->addHelpButton('maxactionspertime', 'maxactionspertime', 'block_xp');
        $mform->setType('maxactionspertime', PARAM_INT);
        $mform->disabledIf('maxactionspertime', 'enablecheatguard', 'eq', 0);

        $mform->addElement('block_xp_form_duration', 'timebetweensameactions', get_string('timebetweensameactions', 'block_xp'), [
            'maxunit' => 60,
            'optional' => false,        // We must set this...
        ]);
        $mform->addHelpButton('timebetweensameactions', 'timebetweensameactions', 'block_xp');
        $mform->setType('timebetweensameactions', PARAM_INT);
        $mform->disabledIf('timebetweensameactions', 'enablecheatguard', 'eq', 0);

        $mform->addElement('header', 'hdrloggin', get_string('logging', 'block_xp'));

        $mform->addElement('advcheckbox', 'enablelog', get_string('enablelogging', 'block_xp'));

        $options = array(
            '0' => get_string('forever', 'block_xp'),
            '1' => get_string('for1day', 'block_xp'),
            '3' => get_string('for3days', 'block_xp'),
            '7' => get_string('for1week', 'block_xp'),
            '30' => get_string('for1month', 'block_xp'),
        );
        $mform->addElement('select', 'keeplogs', get_string('keeplogs', 'block_xp'), $options);
        $mform->disabledIf('keeplogs', 'enablelog', 'eq', 0);

        $this->add_action_buttons();
    }

    /**
     * Get the data.
     *
     * @return stdClass
     */
    public function get_data() {
        $data = parent::get_data();
        if (!$data) {
            return $data;
        }

        // Convert back from itemspertime.
        if (!isset($data->maxactionspertime) || !is_array($data->maxactionspertime)) {
            $data->maxactionspertime = 0;
            $data->timeformaxactions = 0;
        } else {
            $data->timeformaxactions = (int) $data->maxactionspertime['time'];
            $data->maxactionspertime = (int) $data->maxactionspertime['points'];
        }

        // When not selecting any, the data is not sent.
        if (!isset($data->laddercols)) {
            $data->laddercols = [];
        }
        $data->laddercols = implode(',', $data->laddercols);

        // When the cheat guard is disabled, we remove the config fields so that
        // we can keep the defaults and the data previously submitted by the user.
        if (empty($data->enablecheatguard)) {
            unset($data->maxactionspertime);
            unset($data->timeformaxactions);
            unset($data->timebetweensameactions);
        }

        unset($data->submitbutton);
        return $data;
    }

    /**
     * Set the data.
     */
    public function set_data($data) {
        $data = (array) $data;
        if (isset($data['laddercols'])) {
            $data['laddercols'] = explode(',', $data['laddercols']);
        }

        // Convert to itemspertime.
        if (isset($data['maxactionspertime']) && isset($data['timeformaxactions'])) {
            $data['maxactionspertime'] = [
                'points' => (int) $data['maxactionspertime'],
                'time' => (int) $data['timeformaxactions']
            ];
            unset($data['timeformaxactions']);
        }

        parent::set_data($data);
    }

}
