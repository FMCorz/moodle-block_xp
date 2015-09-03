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
 * Block XP admin settings.
 *
 * @package    block_xp
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    // Context in which the block is enabled.
    $settings->add(new admin_setting_configselect(
        'block_xp_context',
        get_string('wherearexpused', 'block_xp'),
        get_string('wherearexpused_desc', 'block_xp'),
        CONTEXT_COURSE,
        array(
            CONTEXT_COURSE => get_string('incourses', 'block_xp'),
            CONTEXT_SYSTEM => get_string('forthewholesite', 'block_xp')
        )
    ));

}

