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
 * Visuals controller.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

use context_course;
use context_system;
use html_writer;
use stdClass;
use block_xp\local\config\course_world_config;

/**
 * Visuals controller class.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class visuals_controller extends page_controller {

    /** @var string The route name. */
    protected $routename = 'visuals';

    /** @var moodleform The form. */
    protected $form;

    protected function pre_content() {
        $context = $this->world->get_context();
        $levelsinfo = $this->world->get_levels_info();
        $config = $this->world->get_config();

        // Some of the logic is tied to the course_world class.
        $fmoptions = ['subdirs' => 0, 'accepted_types' => array('.jpg', '.png')];
        $draftitemid = file_get_submitted_draft_itemid('badges');

        // If the badges are missing, we copy them now.
        if ($config->get('enablecustomlevelbadges') == course_world_config::CUSTOM_BADGES_MISSING) {
            file_prepare_draft_area($draftitemid, context_system::instance()->id, 'block_xp', 'defaultbadges', 0, $fmoptions);
        } else {
            file_prepare_draft_area($draftitemid, $context->id, 'block_xp', 'badges', 0, $fmoptions);
        }

        $form = new \block_xp\form\visuals($this->pageurl->out(false), ['fmoptions' => $fmoptions]);
        $form->set_data((object) ['badges' => $draftitemid]);

        if ($data = $form->get_data()) {
            // Save the area.
            file_save_draft_area_files($data->badges, $context->id, 'block_xp', 'badges', 0, $fmoptions);

            // When we save, we mark the flag as noop because either we copied the default badges,
            // when we loaded the draft area, or the user saved the page as they were in a legacy state,
            // and we want to take them out of it.
            $config->set('enablecustomlevelbadges', course_world_config::CUSTOM_BADGES_NOOP);

            // TODO Add a confirmation message.
            $this->redirect();

        } else if ($form->is_cancelled()) {
            $this->redirect();
        }

        $this->form = $form;
    }

    protected function get_page_html_head_title() {
        return get_string('coursevisuals', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('coursevisuals', 'block_xp');
    }

    protected function page_content() {
        echo html_writer::tag('p', get_string('visualsintro', 'block_xp'));

        $this->form->display();

        $levelsinfo = $this->world->get_levels_info();
        echo $this->get_renderer()->heading(get_string('preview'), 3);
        echo $this->get_renderer()->levels_preview($levelsinfo->get_levels());
    }

}
