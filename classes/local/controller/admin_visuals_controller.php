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
 * Admin visuals controller.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

use context_system;
use html_writer;

/**
 * Admin visuals controller class.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_visuals_controller extends admin_route_controller {

    /** @var string The section name. */
    protected $sectionname = 'block_xp_default_visuals';
    /** @var moodleform The form. */
    protected $form;

    protected function pre_content() {
        $options = [
            'subdirs' => 0,
            'accepted_types' => array('.jpg', '.png'),
        ];
        $context = context_system::instance();
        $fs = get_file_storage();

        // Load draft area.
        $draftitemid = file_get_submitted_draft_itemid('badges');
        file_prepare_draft_area($draftitemid, $context->id, 'block_xp', 'defaultbadges', 0, $options);

        // Capture form submission.
        $form = new \block_xp\form\visuals($this->pageurl->out(false), ['fmoptions' => $options]);
        $form->set_data((object) ['badges' => $draftitemid]);
        if ($data = $form->get_data()) {
            // Save the area.
            file_save_draft_area_files($data->badges, $context->id, 'block_xp', 'defaultbadges', 0, $options);
            // TODO Add confirmation message.
            $this->redirect();
        }

        $this->form = $form;
    }

    /**
     * Echo the content.
     *
     * @return void
     */
    protected function content() {
        $output = $this->get_renderer();
        $config = \block_xp\di::get('config');

        echo $output->heading(get_string('defaultvisuals', 'block_xp'));
        echo html_writer::tag('p', get_string('admindefaultvisualsintro', 'block_xp'));

        $this->form->display();

        // Preview.
        // TODO We should get the levels info from somewhere else.
        $context = context_system::instance();
        $resolver = new \block_xp\local\xp\file_storage_badge_url_resolver($context, 'block_xp', 'defaultbadges', 0);
        $data = json_decode($config->get('levelsdata'), true);
        if (!$data) {
            $levelsinfo = \block_xp\local\xp\algo_levels_info::make_from_defaults($resolver);
        } else {
            $levelsinfo = new \block_xp\local\xp\algo_levels_info($data, $resolver);
        }

        echo $output->heading(get_string('preview'), 3);
        echo $output->levels_preview($levelsinfo->get_levels());
    }

}
