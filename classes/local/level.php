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
 * Level.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local;
defined('MOODLE_INTERNAL') || die();

/**
 * Level class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class level implements level_interface {

    protected $level;
    protected $xprequired;
    protected $desc;

    public function __construct($level, $xprequired, $desc) {
        $this->level = $level;
        $this->xprequired = $xprequired;
        $this->desc = $desc;
    }

    /**
     * Get the level as a number.
     *
     * @return int
     */
    public function get_level() {
        return $this->level;
    }

    /**
     * Get a human readable description of the level.
     *
     * @return string
     */
    public function get_description() {
        return $this->desc;
    }

    /**
     * Get the amount of experience points required.
     *
     * @return int
     */
    public function get_xp_required() {
        return $this->xprequired;
    }

}
