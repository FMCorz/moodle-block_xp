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
 * Admin levels controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;

use block_xp;
use block_xp\local\config\config;
use block_xp\local\serializer\level_serializer;
use block_xp\local\serializer\levels_info_serializer;
use block_xp\local\serializer\url_serializer;

defined('MOODLE_INTERNAL') || die();

/**
 * Admin levels controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_levels_controller extends admin_route_controller {

    /** @var config The config. */
    protected $config;
    /** @var moodleform The form. */
    protected $form;
    /** @var string Admin section name. */
    protected $sectionname = 'block_xp_default_levels';

    protected function post_login() {
        parent::post_login();
        $this->config = \block_xp\di::get('config');
    }

    protected function content() {
        $output = $this->get_renderer();
        echo $output->heading(get_string('defaultlevels', 'block_xp'));
        list($module, $props) = $this->get_react_module();
        echo $output->react_module($module, $props);
    }

    protected function get_react_module() {
        $config = block_xp\di::get('config');

        $data = json_decode($config->get('levelsdata'), true);
        $resolver = \block_xp\di::get('badge_url_resolver');
        if (!$data) {
            $levelsinfo = \block_xp\local\xp\algo_levels_info::make_from_defaults($resolver);
        } else {
            $levelsinfo = new \block_xp\local\xp\algo_levels_info($data, $resolver);
        }

        $serializer = new levels_info_serializer(new level_serializer(new url_serializer()));
        return [
            'block_xp/ui-levels-lazy',
            [
                'courseId' => 0,
                'levelsInfo' => $serializer->serialize($levelsinfo),
            ]
        ];
    }
}
