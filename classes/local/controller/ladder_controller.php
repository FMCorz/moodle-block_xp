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
 * Ladder controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

use moodle_exception;

/**
 * Ladder controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ladder_controller extends page_controller {

    protected $requiremanage = false;
    protected $supportsgroups = true;
    protected $routename = 'ladder';

    protected function permissions_checks() {
        parent::permissions_checks();
        if (!$this->world->get_config()->get('enableladder')) {
            throw new moodle_exception('nopermissions', '', '', 'view_ladder_page');
        }
    }

    protected function page_setup() {
        global $PAGE;
        parent::page_setup();
        $PAGE->add_body_class('block_xp-ladder');
    }

    protected function get_table() {
        $courseid = $this->courseid;
        $table = new \block_xp\output\ladder_table(
            $this->world,
            $this->get_renderer(),
            $this->world->get_store(),
            $this->get_groupid(),
            [
                'identitymode' => $this->world->get_config()->get('identitymode'),
                'rankmode' => $this->world->get_config()->get('rankmode'),
                'neighboursonly' => $this->world->get_config()->get('neighbours') > 0,
                'neighboursabove' => $this->world->get_config()->get('neighbours'),
                'neighboursbelow' => $this->world->get_config()->get('neighbours'),
                'additionalcols' => explode(',', $this->world->get_config()->get('laddercols')),
            ]
        );
        $table->define_baseurl($this->pageurl);
        return $table;
    }

    protected function get_page_html_head_title() {
        return get_string('ladder', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('ladder', 'block_xp');
    }

    protected function page_content() {
        $this->print_group_menu();
        echo $this->get_table()->out(20, false);
    }

}
