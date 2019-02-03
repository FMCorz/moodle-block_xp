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
 * Infos controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

use flexible_table;
use html_writer;
use moodle_exception;

/**
 * Infos controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class infos_controller extends page_controller {

    protected $requiremanage = false;
    protected $routename = 'infos';

    protected function permissions_checks() {
        parent::permissions_checks();
        if (!$this->world->get_config()->get('enableinfos')) {
            throw new moodle_exception('nopermissions', '', '', 'view_infos_page');
        }
    }

    protected function get_page_html_head_title() {
        return get_string('infos', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('infos', 'block_xp');
    }

    protected function page_content() {
        $output = $this->get_renderer();
        $levelsinfo = $this->world->get_levels_info();

        echo $output->levels_grid($levelsinfo->get_levels());

        if ($this->world->get_access_permissions()->can_manage()) {
            $levelsurl = $this->urlresolver->reverse('levels', ['courseid' => $this->courseid]);
            echo html_writer::tag('p',
                $output->single_button(
                    $levelsurl->get_compatible_url(),
                    get_string('customizelevels', 'block_xp'),
                    'get'
                )
            );
        }
    }

}
