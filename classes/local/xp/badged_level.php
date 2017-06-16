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
 * Level with badge & description.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\xp;
defined('MOODLE_INTERNAL') || die();

use context;
use moodle_url;

/**
 * Level with badge & description.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class badged_level extends described_level implements level_with_badge {

    /** @var context Badge context. */
    protected $badgecontext;

    /**
     * Constructor.
     *
     * @param int $level The level.
     * @param int $xprequired The XP required.
     * @param string $desc The description.
     * @param context $badgecontext The badget context.
     */
    public function __construct($level, $xprequired, $desc, context $badgecontext) {
        parent::__construct($level, $xprequired, $desc);
        $this->badgecontext = $badgecontext;
    }

    /**
     * Get the badge URL.
     *
     * @return moodle_url
     */
    public function get_badge_url() {
        return moodle_url::make_pluginfile_url($this->badgecontext->id, 'block_xp', 'badges', 0, '/', $this->level);
    }

}
