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
 * Block XP lib.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * File serving.
 *
 * @param stdClass $course The course object.
 * @param stdClass $bi Block instance record.
 * @param context $context The context object.
 * @param string $filearea The file area.
 * @param array $args List of arguments.
 * @param bool $forcedownload Whether or not to force the download of the file.
 * @param array $options Array of options.
 * @return void|false
 */
function block_xp_pluginfile($course, $bi, $context, $filearea, $args, $forcedownload, array $options = array()) {
    global $CFG;

    if ($CFG->block_xp_context == CONTEXT_SYSTEM && $context->contextlevel !== CONTEXT_SYSTEM) {
        return false;
    } else if ($CFG->block_xp_context != CONTEXT_SYSTEM && $context->contextlevel !== CONTEXT_COURSE) {
        return false;
    }

    $fs = get_file_storage();
    $file = null;

    if ($filearea == 'badges') {
        // For performance reason, and very low risk, we do not restrict the access to the level badges
        // to the participant of the course, nor do we check if they have the required level, etc...
        $itemid = array_shift($args);
        $filename = array_shift($args);
        $filepath = '/';
        $file = $fs->get_file($context->id, 'block_xp', $filearea, $itemid, $filepath, $filename . '.png');
        if (!$file) {
            $file = $fs->get_file($context->id, 'block_xp', $filearea, $itemid, $filepath, $filename . '.jpg');
        }
    }

    if (!$file) {
        return false;
    }

    send_stored_file($file);
}
