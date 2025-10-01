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
// https://levelup.plus

/**
 * Level.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\xp;

/**
 * Level.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class static_level implements level, level_with_badge, level_with_description, level_with_name {

    /** @var int The level. */
    protected $level;
    /** @var int The XP required. */
    protected $xprequired;

    /** @var string The level description. */
    protected $desc;
    /** @var string The level name. */
    protected $name;

    /** @var badge_url_resolver Badge URL resolver. */
    protected $badgeurlresolver;

    public function __construct($level, $xprequired, $badgeurlresolver = null, $metadata = []) {
        $this->level = (int) $level;
        $this->xprequired = (int) $xprequired;
        $this->badgeurlresolver = $badgeurlresolver;

        $keys = ['name', 'desc'];
        foreach ($keys as $key) {
            if (!empty($metadata[$key])) {
                $this->{$key} = $metadata[$key];
            }
        }
    }

    public function get_level() {
        return $this->level;
    }

    public function get_xp_required() {
        return $this->xprequired;
    }

    public function get_badge_url() {
        return $this->badgeurlresolver ? $this->badgeurlresolver->get_url_for_level($this->level) : null;
    }

    public function get_description() {
        return $this->desc ?? '';
    }

    public function get_name() {
        return $this->name ?? '';
    }

}
