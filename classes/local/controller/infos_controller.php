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
 * Infos controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

use flexible_table;
use html_writer;
use moodle_exception;
use block_xp\form\instructions;
use block_xp\local\routing\url;

/**
 * Infos controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class infos_controller extends page_controller {

    protected $requiremanage = false;
    protected $routename = 'infos';
    protected $form;

    protected function permissions_checks() {
        parent::permissions_checks();
        if (!$this->world->get_config()->get('enableinfos')) {
            throw new moodle_exception('nopermissions', '', '', 'view_infos_page');
        }
    }

    protected function define_optional_params() {
        return [
            ['edit', false, PARAM_BOOL, true]
        ];
    }

    protected function pre_content() {
        if ($this->world->get_access_permissions()->can_manage()) {
            $redirecturl = new url($this->pageurl, ['edit' => false]);
            $form = $this->get_form();
            if ($data = $form->get_data()) {
                $this->world->get_config()->set('instructions', $data->instructions['text']);
                $this->world->get_config()->set('instructions_format', $data->instructions['format']);
                $this->redirect($redirecturl);
            } else if ($form->is_cancelled()) {
                $this->redirect($redirecturl);
            }
        }
    }

    protected function get_form() {
        if (!$this->form) {
            $this->form = new instructions($this->pageurl->out(false));
        }
        return $this->form;
    }

    protected function get_page_html_head_title() {
        return get_string('infos', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('infos', 'block_xp');
    }

    protected function page_content() {
        $output = $this->get_renderer();
        $levelsinfo = $this->world->get_levels_info();
        $canmanage = $this->world->get_access_permissions()->can_manage();
        $config = $this->world->get_config();

        $instructions = $config->get('instructions');
        $instructionsformat = $config->get('instructions_format');
        $cleanedinstructions = trim(strip_tags($instructions));
        $hasinstructions = !empty($cleanedinstructions);
        $isediting = $this->get_param('edit') && $canmanage;

        if ($isediting) {
            $form = $this->get_form();
            $form->set_data((object) ['instructions' => [
                'text' => $instructions,
                'format' => $instructionsformat
            ]]);
            $form->display();

        } else if ($hasinstructions) {
            // Display the instructions when not editing.
            echo html_writer::div(format_text($instructions, $instructionsformat), 'block_xp-instructions');
        }

        if (!$isediting && $canmanage) {
            $editurl = new url($this->pageurl, ['edit' => true]);
            echo html_writer::tag('p',
                $output->single_button(
                    $editurl->get_compatible_url(),
                    get_string($hasinstructions ? 'editinstructions' : 'addinstructions', 'block_xp'),
                    'get'
                )
            );
        }

        echo $output->levels_grid($levelsinfo->get_levels());

        if ($canmanage) {
            $levelsurl = $this->urlresolver->reverse('levels', ['courseid' => $this->courseid]);
            echo html_writer::tag('p',
                $output->single_button(
                    $levelsurl->get_compatible_url(),
                    get_string('customizelevels', 'block_xp'),
                    'get'
                )
            );
        }
    }

}
