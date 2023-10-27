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
 * Log controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;

use block_xp\local\routing\url;
use core_user;
use html_writer;

/**
 * Log controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class log_controller extends page_controller {

    /** @var string The nav name. */
    protected $navname = 'report';
    /** @var string The route name. */
    protected $routename = 'log';
    /** @var bool Whether is wide. */
    protected $iswideview = true;
    /** @var bool Whether supports groups. */
    protected $supportsgroups = true;

    /** @var int|null The user ID to filter the logs for. Use {@see self::get_user_id} to obtain. */
    protected $userid = null;

    protected function permissions_checks() {
        $accessperms = $this->world->get_access_permissions();
        if (!($accessperms instanceof \block_xp\local\permission\access_logs_permissions)) {
            throw new \coding_exception('Access permissions object requires logs permissions.');
        }
        $accessperms->require_access_logs();
    }

    protected function define_optional_params() {
        return [
            ['userid', null, PARAM_INT],
        ];
    }

    protected function get_table() {
        $table = new \block_xp\output\log_table(
            $this->world,
            $this->get_groupid(),
            $this->get_user_id()
        );
        $table->define_baseurl($this->pageurl);
        return $table;
    }

    protected function get_page_html_head_title() {
        return get_string('courselog', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('courselog', 'block_xp');
    }

    /**
     * Get the user ID to display the logs for.
     *
     * @return int When falsy, no users or the user is invalid.
     */
    protected function get_user_id() {
        if ($this->userid === null) {
            $userid = $this->get_param('userid');
            if (!$userid || $userid <= 0 || isguestuser($userid)) {
                $userid = 0;
            }
            $this->userid = $userid;
        }
        return $this->userid;
    }

    protected function page_content() {
        $userid = $this->get_user_id();
        $singleuser = (bool) $userid;

        if (!$singleuser) {
            $this->print_group_menu();
        } else {
            $user = core_user::get_user($userid, '*', MUST_EXIST);
            $allusers = new url($this->pageurl);
            $allusers->remove_params('userid');
            echo html_writer::tag('p', get_string('resultsfilteredforn', 'block_xp', fullname($user))
                . ' ' . html_writer::link($allusers, get_string('removefilter', 'block_xp')));
        }

        echo $this->get_table()->out(50, !$singleuser);
    }

}
