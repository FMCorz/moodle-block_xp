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
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\output;
defined('MOODLE_INTERNAL') || die();

use action_link;
use lang_string;
use moodle_url;
use renderable;
use block_xp\local\xp\state;
use block_xp\local\activity\activity;

/**
 * Main widget.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class xp_widget implements renderable {

    /** @var state The user's state. */
    public $state;
    /** @var activity[] The activity objects. */
    public $recentactivity;
    /** @var moodle_url The URL to see more. */
    public $recentactivityurl;
    /** @var bool Whether to force showing the recent activity. */
    public $forcerecentactivity = false;
    /** @var renderable The introduction text. */
    public $intro;
    /** @var action_link[] The navigation links.*/
    public $actions;
    /** @var lang_string[] Manager notices. */
    public $managernotices = [];

    public function __construct(state $state, array $recentactivity, renderable $intro = null, array $actions,
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

    public function add_manager_notice(lang_string $managernotice) {
        $this->managernotices[] = $managernotice;
    }

    public function set_force_recent_activity($value) {
        $this->forcerecentactivity = (bool) $value;
    }

}
