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

    if ($oldversion < 2014072403) {

        // Define index courseid (unique) to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $index = new xmldb_index('courseid', XMLDB_INDEX_UNIQUE, array('courseid'));

        // Conditionally launch add index courseid.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2014072403, 'xp');
    }

    if ($oldversion < 2014090800) {

        // Define field enablelevelupnotif to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('enablelevelupnotif', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'levelsdata');

        // Conditionally launch add field enablelevelupnotif.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2014090800, 'xp');
    }

    if ($oldversion < 2014090900) {

        // Define table block_xp_filters to be created.
        $table = new xmldb_table('block_xp_filters');

        // Adding fields to table block_xp_filters.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('ruledata', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('points', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_xp_filters.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table block_xp_filters.
        $table->add_index('courseid', XMLDB_INDEX_NOTUNIQUE, array('courseid'));

        // Conditionally launch create table for block_xp_filters.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2014090900, 'xp');
    }

    if ($oldversion < 2014091200) {

        // Define field enablecustomlevelbadges to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('enablecustomlevelbadges', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'enablelevelupnotif');

        // Conditionally launch add field enablecustomlevelbadges.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2014091200, 'xp');
    }

    if ($oldversion < 2015030901) {

        // Define field maxactionspertime to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('maxactionspertime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '10', 'enablecustomlevelbadges');

        // Conditionally launch add field maxactionspertime.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2015030901, 'xp');
    }

    if ($oldversion < 2015030902) {

        // Define field timeformaxactions to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('timeformaxactions', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '60', 'maxactionspertime');

        // Conditionally launch add field timeformaxactions.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2015030902, 'xp');
    }

    if ($oldversion < 2015030903) {

        // Define field timebetweensameactions to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('timebetweensameactions', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '180', 'timeformaxactions');

        // Conditionally launch add field timebetweensameactions.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2015030903, 'xp');
    }

    if ($oldversion < 2016021500) {

        // We changed the way the "Level up" notifications are triggered, so we'll remove the old flags from the database.
        $DB->delete_records('user_preferences', array('name' => 'block_xp_notify_level_up'));

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2016021500, 'xp');
    }

    if ($oldversion < 2016021501) {

        // Define field identitymode to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('identitymode', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '1', 'timebetweensameactions');

        // Conditionally launch add field identitymode.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2016021501, 'xp');
    }

    if ($oldversion < 2016021502) {

        // Define field rankmode to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('rankmode', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '1', 'identitymode');

        // Conditionally launch add field rankmode.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2016021502, 'xp');
    }

    if ($oldversion < 2016021503) {

        // Define field neighbours to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('neighbours', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '0', 'rankmode');

        // Conditionally launch add field neighbours.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2016021503, 'xp');
    }

    if ($oldversion < 2016022401) {

        // Define field identitymode to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('identitymode', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '1', 'timebetweensameactions');

        // Conditionally launch add field identitymode.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2016022401, 'xp');
    }

    if ($oldversion < 2016022402) {

        // Define field rankmode to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('rankmode', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '1', 'identitymode');

        // Conditionally launch add field rankmode.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2016022402, 'xp');
    }

    if ($oldversion < 2016022403) {

        // Define field neighbours to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('neighbours', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '0', 'rankmode');

        // Conditionally launch add field neighbours.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2016022403, 'xp');
    }

    if ($oldversion < 2017021401) {

        // Define field enablecheatguard to be added to block_xp_config.
        $table = new xmldb_table('block_xp_config');
        $field = new xmldb_field('enablecheatguard', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'lastlogpurge');

        // Conditionally launch add field enablecheatguard.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Xp savepoint reached.
        upgrade_block_savepoint(true, 2017021401, 'xp');
    }

    return true;

}
