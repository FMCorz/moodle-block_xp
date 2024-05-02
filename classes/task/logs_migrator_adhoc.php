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

namespace block_xp\task;

use block_xp\di;
use text_progress_trace;

/**
 * Logs migrator adhoc task.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class logs_migrator_adhoc extends \core\task\adhoc_task {

    /**
     * Execute the task.
     */
    public function execute() {
        $trace = new text_progress_trace();
        $config = di::get('config');

        $trace->output('See: https://docs.levelup.plus/xp/docs/upgrade-notes/upgrading-to-v20#logs');
        if (!$config->get('logmigrationenabled')) {
            $trace->output('Log migration is disabled, exiting...');
            return;
        }

        $batchsize = max(1, (int) $config->get('logmigrationbatchsize'));
        $limit = (int) $config->get('logmigrationmaxitems');
        $maxruntime = (int) $config->get('logmigrationmaxruntime');

        $qualifiedmigrators = [];
        $migrators = di::get('log_migrators');
        $trace->output('Migration settings:');
        $trace->output('- Batch size: ' . $batchsize, 1);
        $trace->output('- Limit: ' . $limit, 1);
        $trace->output('- Max runtime: ' . $maxruntime . ' seconds', 1);

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
            return;
        }

        $totalmigrated = 0;
        foreach ($qualifiedmigrators as $migrator) {
            $trace->output('Executing migration from ' . get_class($migrator) . '...');
            $migrator->set_trace($trace);
            $migrator->set_batch_size($batchsize);
            $migrator->set_limit($limit);
            $migrator->set_max_runtime($maxruntime);
            $migrated = $migrator->migrate();
            $totalmigrated += $migrated;
        }

        // Auto-schedule the next run if we migrated anything, else we should not insist..
        if ($totalmigrated > 0) {
            $trace->output('Scheduling next run...');
            static::schedule();
        }
    }

    /**
     * Enforce concurrency limit to 1.
     *
     * Just in case the task would be scheduled multiple times, we don't want it to ever run in parallel.
     *
     * @return int
     */
    public function get_default_concurrency_limit(): int {
        return 1;
    }

    /**
     * Schedule the task.
     *
     * @return self
     */
    public static function schedule() {
        $interval = max(1, (int) di::get('config')->get('logmigrationruninterval'));
        $task = new static();
        $task->set_component('block_xp');
        $task->set_next_run_time(di::get('clock')->time() + $interval);
        \core\task\manager::queue_adhoc_task($task);
        return $task;
    }
}
