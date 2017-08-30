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
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

use context_course;
use block_xp\local\config\block_config;

/**
 * Config controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config_controller extends page_controller {

    /** @var string The route name. */
    protected $routename = 'config';
    /** @var moodleform The form. */
    private $form;
    /** @var config The block config. */
    private $blockconfig;

    /**
     * Define the form.
     *
     * @param bool $withblockconfig With block config?
     * @return moodleform
     */
    protected function define_form($withblockconfig = false) {
        return new \block_xp\form\config($this->pageurl->out(false), [
            'showblockconfig' => $withblockconfig
        ]);
    }

    /**
     * Get the form.
     *
     * Private so that we do not override this one.
     *
     * @return moodleform
     */
    private function get_form() {
        if (!$this->form) {
            $this->form = $this->define_form(!empty($this->blockconfig));
        }
        return $this->form;
    }

    protected function pre_content() {
        // Try to find an instance of our block, and thus block configuration.
        $bifinder = \block_xp\di::get('course_world_block_instance_finder');
        $bi = $bifinder->get_instance_in_context('xp', $this->world->get_context());
        if (!empty($bi)) {
            $this->blockconfig = new block_config($bi);
        }

        // Load raw config data.
        $config = $this->world->get_config();
        $rawconfig = $config->get_all();
        if ($this->blockconfig) {
            foreach ($this->blockconfig->get_all() as $key => $value) {
                $rawconfig['block_' . $key] = $value;
            }
        }

        $form = $this->get_form();
        $form->set_data($rawconfig);
        if ($data = $form->get_data()) {
            $data = (array) $data;

            // Extract the config meant for the block.
            $blockdata = [];
            foreach ($data as $key => $value) {
                if (preg_match('/^block_/', $key)) {
                    $blockdata[substr($key, 6)] = $value;
                    unset($data[$key]);
                }
            }

            // Save the config.
            $config->set_many($data);
            if ($this->blockconfig) {
                $this->blockconfig->set_many($blockdata);
            }

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
        $form = $this->get_form();
        $form->display();
    }

}
