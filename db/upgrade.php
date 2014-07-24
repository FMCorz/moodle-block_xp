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
 * Block XP upgrade.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block XP upgrade function.
 *
 * @param int $oldversion Old version.
 * @return true
 */
function xmldb_block_xp_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2014031500) {

        // Define field enabled to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('enabled', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'courseid');

        // Conditionally launch add field enabled.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2014031500, 'xp');
    }

    if ($oldversion < 2014072301) {

        // Define field enableladder to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('enableladder', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'lastlogpurge');

        // Conditionally launch add field enableladder.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2014072301, 'xp');
    }

    if ($oldversion < 2014072401) {

        // Define field levelsdata to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('levelsdata', XMLDB_TYPE_TEXT, null, null, null, null, null, 'enableladder');

        // Conditionally launch add field levelsdata.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2014072401, 'xp');
    }

    if ($oldversion < 2014072402) {

        // Define field enableinfos to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('enableinfos', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'enableladder');

        // Conditionally launch add field enableinfos.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2014072402, 'xp');
    }

    return true;

}
