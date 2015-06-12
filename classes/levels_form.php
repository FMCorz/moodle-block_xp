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
 * Block XP levels form.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/**
 * Block XP levels form class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_levels_form extends moodleform {

    /** @var block_xp_manager The XP manager. */
    protected $manager;

    /**
     * Form definintion.
     *
     * @return void
     */
    public function definition() {
        global $OUTPUT;

        $mform = $this->_form;
        $this->manager = $this->_customdata['manager'];

        $mform->setDisableShortforms(true);
        $mform->addElement('header', 'hdrgen', get_string('general', 'form'));

        $mform->addElement('text', 'levels', get_string('levelcount', 'block_xp'));
        $mform->addRule('levels', get_string('required'), 'required');
        $mform->setType('levels', PARAM_INT);

        if ($this->manager->get_config('enablecustomlevelbadges')) {
            $mform->addElement('static', '', '', get_string('changelevelformhelp', 'block_xp'));
        }

        $mform->addElement('selectyesno', 'usealgo', get_string('usealgo', 'block_xp'));
        $mform->setDefault('usealgo', 1);

        $mform->addElement('text', 'basexp', get_string('basexp', 'block_xp'));
        $mform->setDefault('basexp', block_xp_manager::DEFAULT_BASE);
        $mform->disabledIf('basexp', 'usealgo', 'eq', 0);
        $mform->setType('basexp', PARAM_INT);
        $mform->setAdvanced('basexp', true);

        $mform->addElement('text', 'coefxp', get_string('coefxp', 'block_xp'));
        $mform->setDefault('coefxp', block_xp_manager::DEFAULT_COEF);
        $mform->disabledIf('coefxp', 'usealgo', 'eq', 0);
        $mform->setType('coefxp', PARAM_FLOAT);
        $mform->setAdvanced('coefxp', true);

        $mform->addElement('submit', 'updateandpreview', get_string('updateandpreview', 'block_xp'));
        $mform->registerNoSubmitButton('updateandpreview');

        // First level.
        $mform->addElement('header', 'hdrlevel1', get_string('levelx', 'block_xp', 1));
        $mform->addElement('static', 'lvlxp_1', get_string('xprequired', 'block_xp'), 0);

        $mform->addelement('hidden', 'insertlevelshere');
        $mform->setType('insertlevelshere', PARAM_BOOL);

        $mform->addElement('static', 'warn', '', $OUTPUT->notification(get_string('levelswillbereset', 'block_xp'), 'notifyproblem'));

        $this->add_action_buttons();

    }

    /**
     * Definition after data.
     *
     * @return void
     */
    public function definition_after_data() {
        $mform = $this->_form;

        // Ensure that the values are not wrong, the validation on save will catch those problems.
        $levels = max((int) $mform->exportValue('levels'), 2);
        $base = max((int) $mform->exportValue('basexp'), 1);
        $coef = max((float) $mform->exportValue('coefxp'), 1.001);

        $defaultlevels = block_xp_manager::get_levels_with_algo($levels, $base, $coef);

        // Add the levels.
        for ($i = 2; $i <= $levels; $i++) {
            $el =& $mform->createElement('header', 'hdrlevel' . $i, get_string('levelx', 'block_xp', $i));
            $mform->insertElementBefore($el, 'insertlevelshere');

            $el =& $mform->createElement('text', 'lvlxp_' . $i, get_string('xprequired', 'block_xp'));
            $mform->insertElementBefore($el, 'insertlevelshere');
            $mform->setType('lvlxp_' . $i, PARAM_INT);
            $mform->disabledIf('lvlxp_' . $i, 'usealgo', 'eq', 1);
            if ($mform->exportValue('usealgo') == 1) {
                // Force the constant value when the algorightm is used.
                $mform->setConstant('lvlxp_' . $i, $defaultlevels[$i]);
            }

            $el =& $mform->createElement('text', 'lvldesc_' . $i, get_string('leveldesc', 'block_xp'));
            $mform->insertElementBefore($el, 'insertlevelshere');
            $mform->addRule('lvldesc_' . $i, get_string('maximumchars', '', 255), 'maxlength', 255);
            $mform->setType('lvldesc_' . $i, PARAM_NOTAGS);
        }
    }

    /**
     * Get the submitted data.
     *
     * @return Array with levels and levelsdata.
     */
    public function get_data() {
        $mform =& $this->_form;
        $data = parent::get_data();
        if (!$data) {
            return $data;
        }

        // Rearranging the information.
        $newdata = array(
            'levels' => $data->levels,
            'levelsdata' => null
        );

        $newdata['levelsdata'] = array(
            'usealgo' => $data->usealgo,
            'base' => $data->basexp,
            'coef' => $data->coefxp,
            'xp' => array(
                '1' => 0
            ),
            'desc' => array(
                '1' => ''
            )
        );
        for ($i = 2; $i <= $data->levels; $i++) {
            $newdata['levelsdata']['xp'][$i] = $data->{'lvlxp_' . $i};
            $newdata['levelsdata']['desc'][$i] = $data->{'lvldesc_' . $i};
        }

        return $newdata;
    }

    /**
     * Set the default values.
     *
     * This translates the data from the format returned by get_data().
     *
     * @param array $data In the format returned by get_data().
     */
    public function set_data($data) {
        $levels = $data['levels'];
        $levelsdata = $data['levelsdata'];
        if ($levelsdata) {
            $data['usealgo'] = $levelsdata['usealgo'];
            $data['basexp'] = $levelsdata['base'];
            $data['coefxp'] = $levelsdata['coef'];
            for ($i = 2; $i <= $levels; $i++) {
                $data['lvlxp_' . $i] = $levelsdata['xp'][$i];
                $data['lvldesc_' . $i] = isset($levelsdata['desc'][$i]) ? $levelsdata['desc'][$i] : '';
            }
        }
        unset($data['levelsdata']);
        parent::set_data($data);
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
        if ($data['levels'] < 2) {
            $errors['levels'] = get_string('errorlevelsincorrect', 'block_xp');
        }

        // Validating the XP points.
        if (!isset($errors['levels'])) {
            $lastxp = 0;
            for ($i = 2; $i <= $data['levels']; $i++) {
                $key = 'lvlxp_' . $i;
                $xp = isset($data[$key]) ? (int) $data[$key] : -1;
                if ($xp <= 0) {
                    $errors['lvlxp_' . $i] = get_string('invalidxp', 'block_xp');
                } else if ($lastxp >= $xp) {
                    $errors['lvlxp_' . $i] = get_string('errorxprequiredlowerthanpreviouslevel', 'block_xp');
                }
                $lastxp = $xp;
            }
        }

        return $errors;
    }

}
