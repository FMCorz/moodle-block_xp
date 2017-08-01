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
 * Admin levels controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

use block_xp\local\config\config;

/**
 * Admin levels controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_levels_controller extends admin_route_controller {

    /** @var config The config. */
    protected $config;
    /** @var moodleform The form. */
    protected $form;
    /** @var string Admin section name. */
    protected $sectionname = 'block_xp_default_levels';

    protected function post_login() {
        parent::post_login();
        $this->config = \block_xp\di::get('config');
    }

    protected function pre_content() {
        $data = json_decode($this->config->get('levelsdata'), true);
        if (!$data) {
            $levelsinfo = \block_xp\local\xp\algo_levels_info::make_from_defaults();
        } else {
            $levelsinfo = new \block_xp\local\xp\algo_levels_info($data);
        }

        $form = $this->get_form();
        $form->set_data_from_levels($levelsinfo);

        if ($newlevelsinfo = $form->get_levels_from_data()) {
            $this->config->set('levelsdata', json_encode($newlevelsinfo->jsonSerialize()));
            $this->redirect(null, get_string('valuessaved', 'block_xp'));

        } else if ($form->is_cancelled()) {

            $this->redirect();
        }
    }

    protected function get_form() {
        if (!$this->form) {
            $this->form = new \block_xp\form\levels_with_algo($this->pageurl->out(false));
        }
        return $this->form;
    }

    protected function content() {
        $form = $this->get_form();
        $output = $this->get_renderer();

        echo $output->heading(get_string('defaultlevels', 'block_xp'));

        if ($form->is_submitted() && !$form->is_validated() && !$form->no_submit_button_pressed()) {
            echo $output->notification(get_string('errorformvalues', 'block_xp'));
        }

        $form->display();
    }

}
