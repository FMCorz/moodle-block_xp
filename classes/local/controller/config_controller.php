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
 * Config controller.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

/**
 * Config controller class.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config_controller extends page_controller {

    /** @var string The route name. */
    protected $routename = 'config';

    /** @var moodleform The form. */
    protected $form;

    protected function pre_content() {
        $config = $this->world->get_config();
        $this->form = new \block_xp\form\config($this->pageurl->out(false));
        $this->form->set_data($config->get_all());
        if ($data = $this->form->get_data()) {
            $config->set_many((array) $data);
            // TODO Display a message.
            $this->redirect();
        }
    }

    protected function get_page_html_head_title() {
        return get_string('coursesettings', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('coursesettings', 'block_xp');
    }

    protected function page_content() {
        $this->form->display();
    }

}
