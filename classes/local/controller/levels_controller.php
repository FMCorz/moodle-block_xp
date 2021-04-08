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

use block_xp\local\xp\algo_levels_info;
use block_xp\local\xp\level_with_badge;
use block_xp\local\xp\level_with_description;
use block_xp\local\xp\level_with_name;
use coding_exception;

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
        global $PAGE;

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

    protected function get_react_module() {
        $world = $this->world;
        $courseid = $world->get_courseid();

        $levelsinfo = $world->get_levels_info();
        if (!$levelsinfo instanceof algo_levels_info) {
            throw new coding_exception("Expecting algo_levels_info class");
        }
        $levelsinfoserialized = [
            'count' => $levelsinfo->get_count(),
            'levels' => array_values(array_map(function($level) {
                $url = $level instanceof level_with_badge ? $level->get_badge_url() : null;
                return [
                    'level' => $level->get_level(),
                    'xprequired' => $level->get_xp_required(),
                    'badgeurl' => $url ? $this->urlnormalizer->normalize($url) : $url,
                    'name' => $level instanceof level_with_name ? $level->get_name() : null,
                    'description' => $level instanceof level_with_description ? $level->get_description() : null
                ];
            }, $levelsinfo->get_levels())),
            'algo' => $levelsinfo instanceof algo_levels_info ? [
                'enabled' => $levelsinfo->get_use_algo(),
                'base' => $levelsinfo->get_base(),
                'coef' => $levelsinfo->get_coef()
            ] : null
        ];

        return [
            'block_xp/ui-levels-lazy',
            [
                'courseId' => $courseid,
                'levelsInfo' => $levelsinfoserialized,
                // 'draftItemId' => $draftitemid,
                // 'levelsWithFile' => $levelswithfile,
                // 'maxBytes' => $maxbytes
            ]
        ];
    }

    protected function page_content() {
        $output = $this->get_renderer();
        list($module, $props) = $this->get_react_module();
        echo $output->react_module($module, $props);
    }

}
