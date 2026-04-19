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

namespace block_xp\local\logger\migration;

/**
 * Log migrator.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface log_migrator {

    /**
     * Migrate logs.
     *
     * @return int Number of logs migrated.
     */
    public function migrate(): int;

    /**
     * Get the number of logs still needing migration.
     *
     * @return int Number of logs still needing migration.
     */
    public function get_remaining_migrations(): int;

    /**
     * Get the batch size.
     *
     * @return int Batch size.
     */
    public function get_batch_size(): int;

    /**
     * Get the limit.
     *
     * @return int|null Limit.
     */
    public function get_limit(): ?int;

    /**
     * Get the maximum runtime.
     *
     * @return int|null Maximum runtime.
     */
    public function get_max_runtime(): ?int;

    /**
     * Set the batch size.
     *
     * @param int $size Batch size.
     */
    public function set_batch_size(int $size): void;

    /**
     * Set the limit.
     *
     * @param int|null $limit Limit.
     */
    public function set_limit(int $limit): void;

    /**
     * Set the maximum runtime.
     *
     * @param int|null $runtime Maximum runtime.
     */
    public function set_max_runtime(int $runtime): void;

    /**
     * Set the trace.
     *
     * @param \progress_trace $trace
     */
    public function set_trace(\progress_trace $trace): void;
}
