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

use block_xp\di;
use block_xp\local\routing\url;
use block_xp\local\serializer\level_serializer;
use block_xp\local\serializer\levels_info_serializer;
use block_xp\local\serializer\url_serializer;
use block_xp\local\xp\algo_levels_info;
use coding_exception;
use html_writer;

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

    protected function define_optional_params() {
        return [
            ['reset', false, PARAM_BOOL, false],
            ['confirm', false, PARAM_BOOL, false]
        ];
    }

    protected function pre_content() {
        parent::pre_content();

        // Reset levels to defaults.
        if ($this->get_param('reset') && confirm_sesskey()) {
            if ($this->get_param('confirm')) {
                $this->world->get_config()->set('levelsdata', di::get('config')->get('levelsdata'));
                $this->redirect(new url($this->pageurl));
            }
        }

    }

    protected function get_page_html_head_title() {
        return get_string('levels', 'block_xp');
    }

    protected function get_page_heading() {
        return get_string('levels', 'block_xp');
    }

    protected function get_react_module() {
        $world = $this->world;
        $courseid = $world->get_courseid();

        $levelsinfo = $world->get_levels_info();
        if (!$levelsinfo instanceof algo_levels_info) {
            throw new coding_exception("Expecting algo_levels_info class");
        }

        $serializer = new levels_info_serializer(new level_serializer(new url_serializer()));
        return [
            'block_xp/ui-levels-lazy',
            [
                'courseId' => $courseid,
                'levelsInfo' => $serializer->serialize($levelsinfo)
            ]
        ];
    }

    protected function page_content() {
        $output = $this->get_renderer();

        if ($this->get_param('reset')) {
            echo $output->confirm(
                get_string('reallyresetcourselevelstodefaults', 'block_xp'),
                new url($this->pageurl->get_compatible_url(), ['reset' => 1, 'confirm' => 1, 'sesskey' => sesskey()]),
                new url($this->pageurl->get_compatible_url())
            );
            return;
        }

        list($module, $props) = $this->get_react_module();
        echo $output->react_module($module, $props);

        $this->page_danger_zone_content();
    }

    protected function page_danger_zone_content() {
        $output = $this->get_renderer();

        echo $output->heading_with_divider(get_string('dangerzone', 'block_xp'));

        $url = new url($this->pageurl, ['reset' => 1, 'sesskey' => sesskey()]);
        echo html_writer::tag('div',
            $output->single_button(
                $url->get_compatible_url(),
                get_string('resetlevelstodefaults', 'block_xp'),
                'get'
            )
        );
    }
}
