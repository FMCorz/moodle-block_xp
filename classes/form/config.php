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
        global $PAGE;
        $showblockconfig = !empty($this->_customdata['showblockconfig']);

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
            2 => get_string('displaynneighbours', 'block_xp', '2'),
            3 => get_string('displaynneighbours', 'block_xp', '3'),
            4 => get_string('displaynneighbours', 'block_xp', '4'),
            5 => get_string('displaynneighbours', 'block_xp', '5'),
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
            'xp' => get_string('total', 'block_xp'),
            'progress' => get_string('progress', 'block_xp'),
        ], ['style' => 'height: 4em;']);
        $el->setMultiple(true);
        $mform->addHelpButton('laddercols', 'ladderadditionalcols', 'block_xp');

        $mform->addElement('hidden', '__generalend');
        $mform->setType('__generalend', PARAM_BOOL);

        $mform->addElement('header', 'hdrcheating', get_string('cheatguard', 'block_xp'));

        $mform->addElement('selectyesno', 'enablecheatguard', get_string('enablecheatguard', 'block_xp'));
        $mform->addHelpButton('enablecheatguard', 'enablecheatguard', 'block_xp');

        $mform->addElement('block_xp_form_itemspertime', 'maxactionspertime', get_string('maxactionspertime', 'block_xp'), [
            'maxunit' => 60,
            'itemlabel' => get_string('actions', 'block_xp')
        ]);
        $mform->addHelpButton('maxactionspertime', 'maxactionspertime', 'block_xp');
        $mform->disabledIf('maxactionspertime', 'enablecheatguard', 'eq', 0);

        $mform->addElement('block_xp_form_duration', 'timebetweensameactions', get_string('timebetweensameactions', 'block_xp'), [
            'maxunit' => 60,
            'optional' => false,        // We must set this...
        ]);
        $mform->addHelpButton('timebetweensameactions', 'timebetweensameactions', 'block_xp');
        $mform->disabledIf('timebetweensameactions', 'enablecheatguard', 'eq', 0);

        $mform->addElement('hidden', '__cheatguardend');
        $mform->setType('__cheatguardend', PARAM_BOOL);

        $mform->addElement('header', 'hdrblockconfig', get_string('blockappearance', 'block_xp'));

        if ($showblockconfig) {
            // This is a direct duplicate from the form to edit the block, however we
            // renamed the arguments to start with 'block_', so remember to update both
            // this and the block form when adding new "block appearance" settings.
            $config = \block_xp\di::get('config');

            $mform->addElement('text', 'block_title', get_string('configtitle', 'block_xp'));
            $mform->setDefault('block_title', $config->get('blocktitle'));
            $mform->addHelpButton('block_title', 'configtitle', 'block_xp');
            $mform->setType('block_title', PARAM_TEXT);

            $mform->addElement('textarea', 'block_description', get_string('configdescription', 'block_xp'));
            $mform->setDefault('block_description', $config->get('blockdescription'));
            $mform->addHelpButton('block_description', 'configdescription', 'block_xp');
            $mform->setType('block_description', PARAM_TEXT);

            $mform->addElement('select', 'block_recentactivity', get_string('configrecentactivity', 'block_xp'), [
                0 => get_string('no'),
                3 => get_string('yes'),
            ]);
            $mform->setDefault('block_recentactivity', $config->get('blockrecentactivity'));
            $mform->addHelpButton('block_recentactivity', 'configrecentactivity', 'block_xp');
            $mform->setType('block_recentactivity', PARAM_INT);

        } else {
            // Advise that we could not find the block.
            if ($PAGE->course->id == SITEID) {
                $fp = new \moodle_url('/', ['redirect' => 0]);
                $mysys = new \moodle_url('/my/indexsys.php');
                $params = [
                    'fp' => $fp->out(false),
                    'mysys' => $mysys->out(false)
                ];
                $str = 'cannotshowblockconfigsys';
            } else {
                $url = new \moodle_url('/course/view.php', ['id' => $PAGE->course->id]);
                $str = 'cannotshowblockconfig';
                $params = $url->out(false);
            }
            $mform->addElement('static', 'missingblock', get_string('whoops', 'block_xp'),
                markdown_to_html(get_string($str, 'block_xp', $params)));
        }

        $mform->addElement('hidden', '__blockappearanceend');
        $mform->setType('__blockappearanceend', PARAM_BOOL);

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

        unset($data->__generalend);
        unset($data->__cheatguardend);
        unset($data->__blockappearanceend);
        unset($data->__loggingend);

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
