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

use context_course;
use context_system;
use stdClass;

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

        // The logic here is shared with the class badged_level.
        $fmoptions = ['subdirs' => 0, 'accepted_types' => array('.jpg', '.png')];
        $draftitemid = file_get_submitted_draft_itemid('badges');
        file_prepare_draft_area($draftitemid, $context->id, 'block_xp', 'badges', 0, $fmoptions);

        $data = new stdClass();
        $data->badges = $draftitemid;
        $data->enablecustomlevelbadges = $this->world->get_config()->get('enablecustomlevelbadges');

        $levelscount = $levelsinfo->get_count();
        $form = new \block_xp\form\visuals($this->pageurl->out(false), ['levelscount' => $levelscount,
            'fmoptions' => $fmoptions]);
        $form->set_data($data);

        if ($data = $form->get_data()) {
            file_save_draft_area_files($data->badges, $context->id, 'block_xp', 'badges', 0, $fmoptions);
            $this->world->get_config()->set_many(['enablecustomlevelbadges' => $data->enablecustomlevelbadges]);
            // TODO Add a confirmation message.
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
        $this->form->display();
    }

}
