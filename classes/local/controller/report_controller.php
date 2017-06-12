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
 * Report controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

use core_user;
use html_writer;
use moodle_url;

/**
 * Report controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class report_controller extends page_controller {

    /** @var string The route name. */
    protected $routename = 'report';

    /** @var int The group ID, if any. */
    protected $groupid;

    /** @var moodleform The form. */
    protected $form;

    /** @var flexible_table The table. */
    protected $table;

    protected function define_optional_params() {
        return [
            ['userid', null, PARAM_INT],
            ['resetdata', 0, PARAM_INT],
            ['action', null, PARAM_ALPHA],
            ['confirm', 0, PARAM_INT],
            ['page', 0, PARAM_INT],     // To keep the table page in URL.
        ];
    }

    protected function post_login() {
        parent::post_login();
        $this->groupid = groups_get_course_group($this->manager->get_course(), true);
    }

    protected function pre_content() {
        // Reset data.
        if ($this->get_param('resetdata') && confirm_sesskey()) {
            if ($this->get_param('confirm')) {
                $this->manager->reset_data($this->groupid);
                $this->redirect();
            }
        }

        // Use edit form.
        $userid = $this->get_param('userid');
        $action = $this->get_param('action');
        if ($action === 'edit' && !empty($userid)) {
            $form = $this->get_form($userid);
            $nexturl = new moodle_url($this->pageurl, ['userid' => null]);
            if ($data = $form->get_data()) {
                $this->manager->reset_user_xp($userid, $data->xp);
                $this->redirect($nexturl);
            } else if ($form->is_cancelled()) {
                $this->redirect($nexturl);
            }
        }
    }

    protected function get_page_html_head_title() {
        return get_string('coursereport', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('coursereport', 'block_xp');
    }

    protected function get_form($userid) {
        if (!$this->form) {
            $progress = $this->manager->get_progress_for_user($userid);
            $form = new \block_xp\form\user_xp($this->pageurl->out(false));
            $form->set_data(['userid' => $userid, 'level' => $progress->level, 'xp' => $progress->xp]);
            $this->form = $form;
        }
        return $form;
    }

    protected function get_table() {
        if (!$this->table) {
            $this->table = new \block_xp\output\report_table('block_xp_report', $this->manager->get_courseid(), $this->groupid);
            $this->table->define_baseurl($this->pageurl);
        }
        return $this->table;
    }

    protected function page_content() {
        $output = $this->get_renderer();
        $groupid = $this->groupid;

        // Confirming reset data.
        if ($this->get_param('resetdata')) {
            $this->get_renderer()->confirm(
                empty($groupid) ? get_string('reallyresetdata', 'block_xp') : get_string('reallyresetgroupdata', 'block_xp'),
                new moodle_url($this->pageurl, ['resetdata' => 1, 'confirm' => 1, 'sesskey' => sesskey(), 'group' => $groupid]),
                $this->pageurl
            );
            return;
        }

        // Use edit form.
        if (!empty($this->form)) {
            $user = core_user::get_user($this->get_param('userid'));
            echo $output->heading(fullname($user), 3);
            $this->form->display();
        }

        // Displaying the report.
        groups_print_course_menu($this->manager->get_course(), $this->pageurl);
        echo $this->get_table()->out(20, true);

        if (empty($this->groupid)) {
            $strreset = get_string('resetcoursedata', 'block_xp');
        } else {
            $strreset = get_string('resetgroupdata', 'block_xp');
        }
        // TODO Fix the single button bug.
        echo html_writer::tag('p',
            $output->single_button(
                new moodle_url($this->pageurl, [
                    'resetdata' => 1,
                    'sesskey' => sesskey(),
                    'group' => $this->groupid
                ]),
                $strreset,
                'get'
            )
        );
    }

}
