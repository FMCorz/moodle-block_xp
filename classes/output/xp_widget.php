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
 * Main widget.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\output;
defined('MOODLE_INTERNAL') || die();

use action_link;
use moodle_url;
use renderable;
use block_xp\local\xp\state;
use block_xp\local\activity\activity;

/**
 * Main widget.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class xp_widget implements renderable {

    public $state;
    public $recentactivity;
    public $recentactivityurl;
    public $intro;
    public $actions;

    public function __construct(state $state, array $recentactivity, $intro, array $actions,
            moodle_url $recentactivityurl = null) {

        $this->state = $state;
        $this->intro = $intro;
        $this->recentactivityurl = $recentactivityurl;

        $this->recentactivity = array_filter($recentactivity, function($activity) {
            return $activity instanceof activity;
        });
        $this->actions = array_filter($actions, function($action) {
            return $action instanceof action_link;
        });
    }

}
