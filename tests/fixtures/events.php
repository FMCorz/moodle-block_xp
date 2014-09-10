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
 * Block XP test event fixtures.
 *
 * @package    core
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\event;
defined('MOODLE_INTERNAL') || die();

class something_happened extends \core\event\base {
    public function init() {
        $this->context = \context_system::instance();
    }
    public static function mock($properties) {
        $event = static::create(array());
        foreach ($properties as $key => $value) {
            $event->data[$key] = $value;
        }
        return $event;
    }
}

