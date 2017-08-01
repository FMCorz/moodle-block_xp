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
 * Levels controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

/**
 * Levels controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class levels_controller extends page_controller {

    /** @var string The route name. */
    protected $routename = 'levels';

    /** @var moodleform The form. */
    protected $form;

    protected function pre_content() {
        $levelsinfo = $this->world->get_levels_info();

        $redirectto = null;
        if ($this->world->get_config()->get('enableinfos')) {
            // When the infos page is enabled, redirect to it.
            $redirectto = $this->urlresolver->reverse('infos', ['courseid' => $this->courseid]);
        }

        $form = $this->get_form();
        $form->set_data_from_levels($levelsinfo);
        if ($newlevelsinfo = $form->get_levels_from_data()) {
            $data = [];

            // Serialise and encode within the config object?
            // Or better if the levels info can save itself?
            $data['levelsdata'] = json_encode($newlevelsinfo->jsonSerialize());
            $this->world->get_config()->set_many($data);

            // Reset the levels in the store, this is very specific to that store.
            // We probably could write that better in a different manner...
            $store = $this->world->get_store();
            if ($store instanceof \block_xp\local\xp\course_user_state_store) {
                $store->recalculate_levels();
            }

            $this->redirect($redirectto, get_string('valuessaved', 'block_xp'));
        } else if ($form->is_cancelled()) {
            $this->redirect($redirectto);
        }
    }

    protected function get_form() {
        if (!$this->form) {
            $this->form = new \block_xp\form\levels_with_algo($this->pageurl->out(false), [
                'config' => $this->world->get_config()
            ]);
        }
        return $this->form;
    }

    protected function get_page_html_head_title() {
        return get_string('levels', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('levels', 'block_xp');
    }

    protected function page_content() {
        $form = $this->get_form();
        if ($form->is_submitted() && !$form->is_validated() && !$form->no_submit_button_pressed()) {
            echo $this->get_renderer()->notification(get_string('errorformvalues', 'block_xp'));
        }
        $form->display();
    }

}
