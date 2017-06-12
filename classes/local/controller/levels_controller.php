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
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

/**
 * Levels controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class levels_controller extends page_controller {

    /** @var string The route name. */
    protected $routename = 'levels';

    /** @var moodleform The form. */
    protected $form;

    protected function pre_content() {
        $manager = $this->manager;
        $courseid = $this->manager->get_courseid();

        $this->form = new \block_xp\form\levels($this->pageurl->out(false), ['manager' => $manager]);
        $form = $this->form;

        $levelsinfo = $manager->get_levels_info();
        $form->set_data_from_levels($levelsinfo);

        if ($newlevelsinfo = $form->get_levels_from_data()) {
            $data = [];
            if ($levelsinfo->get_count() != $newlevelsinfo->get_count()) {
                // The number of levels have changed, we need to disable the custom badges.
                $data['enablecustomlevelbadges'] = false;
            }
            // This is ugly, but we basically convert the object to an array.
            $data['levelsdata'] = $newlevelsinfo->jsonSerialize();
            $manager->update_config($data);
            $manager->recalculate_levels();
            $this->redirect(
                $this->urlresolver->reverse('infos', ['courseid' => $courseid]),
                get_string('valuessaved', 'block_xp')
            );
        } else if ($form->is_cancelled()) {
            $this->redirect($this->urlresolver->reverse('infos', ['courseid' => $courseid]));
        }
    }

    protected function get_page_html_head_title() {
        return get_string('levels', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('levels', 'block_xp');
    }

    protected function page_content() {
        $form = $this->form;
        if ($form->is_submitted() && !$form->is_validated() && !$form->no_submit_button_pressed()) {
            echo $this->get_renderer()->notification(get_string('errorformvalues', 'block_xp'));
        }
        $form->display();
    }

}
