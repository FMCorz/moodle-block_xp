<?php
// This file is part of Level Up XP.
//
// Level Up XP is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Level Up XP is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Level Up XP.  If not, see <https://www.gnu.org/licenses/>.
//
// See <https://levelup.plus>.

namespace block_xp\local\xp;

use block_xp\di;
use block_xp\local\config\config;
use block_xp\local\xp\badge_url_resolver;
use moodle_url;

/**
 * Badge URL resolver.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class stock_badge_url_resolver implements badge_url_resolver {
    /** @var config The admin config. */
    protected $config = null;
    /** @var ?bool Whether to use the legacy badges. */
    protected $uselegacy = null;

    /**
     * Constructor.
     *
     * @param config $config The admin config
     */
    public function __construct(config $config) {
        $this->config = $config;
    }

    /**
     * Get badge URL for level.
     *
     * @param int $level The level, as an integer.
     * @return ?moodle_url
     */
    public function get_url_for_level($level) {
        $this->uselegacy ??= $this->config->has('uselegacylevelbadges') && (bool) $this->config->get('uselegacylevelbadges');
        if ($this->uselegacy || $level <= 0 || $level > levels_info_writer::MAX_LEVEL) {
            return null;
        }
        $fn = sprintf('%03d', $level);
        return di::get('renderer')->image_url("l/{$fn}", 'block_xp');
    }
}
