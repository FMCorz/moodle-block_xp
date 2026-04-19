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
 * CLI script to migrate legacy logs into the new logs table.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir . '/clilib.php');

use block_xp\di;

$help = "Migrates legacy logs into the new logs table.

Options:
--batchsize=N          Number of records to insert per batch (default: 5000)
--execute              Execute migrations (default is check-only mode)
-h, --help             Print out this help

Example:
\$ php public/blocks/xp/cli/migrate_logs.php
\$ php public/blocks/xp/cli/migrate_logs.php --execute --batchsize=10000
";

[$options, $unrecognized] = cli_get_params(
    [
        'help' => false,
        'batchsize' => 5000,
        'execute' => false,
    ],
    [
        'h' => 'help',
    ]
);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if (!empty($options['help'])) {
    cli_writeln($help);
    exit(0);
}

$batchsize = (int) $options['batchsize'];
if ($batchsize < 1) {
    cli_error('Invalid --batchsize value. Must be a positive integer.');
}

$trace = new text_progress_trace();
$migrators = di::get('log_migrators');
$qualifiedmigrators = [];

$trace->output('See: https://docs.levelup.plus/xp/docs/upgrade-notes/upgrading-to-v20#logs');
$trace->output('Migration settings:');
$trace->output('- Batch size: ' . $batchsize, 1);
$trace->output('- Limit: none', 1);
$trace->output('- Max runtime: none', 1);

$trace->output('Identified ' . count($migrators) . ' log migrator(s).');
foreach ($migrators as $migrator) {
    $remainingmigrations = $migrator->get_remaining_migrations();
    $trace->output('- ' . $remainingmigrations . ' migration(s) pending from ' . get_class($migrator));
    if ($remainingmigrations > 0) {
        $qualifiedmigrators[] = $migrator;
    }
}

if (empty($qualifiedmigrators)) {
    $trace->output('No migrations to perform, exiting...');
    exit(0);
}

if (empty($options['execute'])) {
    $trace->output('Check-only mode. Re-run with --execute to perform migrations.');
    exit(0);
}

$totalmigrated = 0;
foreach ($qualifiedmigrators as $migrator) {
    $trace->output('Executing migration from ' . get_class($migrator) . '...');
    $migrator->set_trace($trace);
    $migrator->set_batch_size($batchsize);
    $migrated = $migrator->migrate();
    $totalmigrated += $migrated;
}

$trace->output('Done. Total migrated: ' . $totalmigrated . ' record(s).');
