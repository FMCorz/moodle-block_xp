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

use block_xp\di;
use block_xp\local\factory\reason_from_log_entry_factory;
use block_xp\local\reason\event_reason;
use block_xp\local\reason\reason;
use block_xp\local\reason\reason_with_subtype;
use block_xp\local\reason\resolver;
use moodle_database;
use null_progress_trace;

/**
 * Migrate block_xp_log to block_xp_logs.
 *
 * Migrates legacy log entries from block_xp_log into block_xp_logs. Skips
 * rows already migrated. Processes youngest logs first. Uses batch processing
 * for efficiency with large datasets.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class migrate_xp_log_to_xp_logs implements log_migrator {

    /** @var int Legacy source identifier. */
    const LEGACY_SOURCE_XP = 1;

    /** @var moodle_database */
    protected $db;
    /** @var int Batch size for inserts. */
    protected $batchsize = 5000;
    /** @var int|null Maximum records to process per run. */
    protected $limit;
    /** @var int|null Maximum runtime in seconds, null for no limit. */
    protected $maxruntime;
    /** @var reason_from_log_entry_factory */
    protected $reasonfactory;
    /** @var resolver */
    protected $reasonresolver;
    /** @var \progress_trace */
    protected $trace;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->db = di::get('db');
        $this->reasonfactory = di::get('reason_from_log_entry_factory');
        $this->reasonresolver = di::get('reason_resolver');
        $this->trace = new null_progress_trace();
    }

    /**
     * Migrate block_xp_log records to block_xp_logs.
     *
     * @return int Number of records migrated.
     */
    public function migrate(): int {
        $migrated = 0;
        $processed = 0;
        $batch = [];
        $clock = di::get('clock');
        $deadline = $this->maxruntime !== null ? $clock->time() + $this->maxruntime : null;

        $sql = "SELECT l.id, l.courseid, l.userid, l.eventname, l.xp, l.time, ctx.id AS contextid
                  FROM {block_xp_log} l
             LEFT JOIN {context} ctx ON ctx.contextlevel = :coursectxlevel AND ctx.instanceid = l.courseid
                 WHERE NOT EXISTS (
                    SELECT 1
                      FROM {block_xp_logs} x
                     WHERE x.legacysource = :legacysource
                       AND x.legacyid = l.id
                 )
              ORDER BY l.time DESC, l.id DESC";

        $rs = $this->db->get_recordset_sql($sql, [
            'coursectxlevel' => CONTEXT_COURSE,
            'legacysource' => self::LEGACY_SOURCE_XP,
        ]);

        $this->trace->output('Starting migration block_xp_log -> block_xp_logs...');
        foreach ($rs as $record) {
            if ($this->limit !== null && $processed >= $this->limit) {
                $this->trace->output('Reached limit of ' . $this->limit . ' records, stopping...');
                break;
            } else if ($deadline !== null && $clock->time() >= $deadline) {
                $this->trace->output('Reached deadline of ' . $this->maxruntime . ' seconds, stopping...');
                break;
            }

            $reason = new event_reason();
            $reason->set_subtype($record->eventname);

            $batch[] = (object) [
                'contextid' => (int) $record->contextid, // Could be zero when context no longer exists.
                'userid' => (int) $record->userid,
                'points' => (int) $record->xp,
                'reason' => $this->reasonresolver->get_name($reason),
                'subtype' => $reason->get_subtype(),
                'envid' => null, // Cannot reliably guess.
                'parentid' => null,
                'objectid' => null,
                'ruleid' => null,
                'reasontypehash' => $this->get_reason_type_hash($reason),
                'timerecorded' => (int) $record->time,
                'legacysource' => self::LEGACY_SOURCE_XP,
                'legacyid' => (int) $record->id,
            ];

            $processed++;

            if (count($batch) >= $this->batchsize) {
                $this->trace->output('Inserting batch of ' . count($batch) . ' records...');
                $this->db->insert_records('block_xp_logs', $batch);
                $migrated += count($batch);
                $batch = [];
            }
        }
        $rs->close();

        if (!empty($batch)) {
            $this->trace->output('Inserting final batch of ' . count($batch) . ' records...');
            $this->db->insert_records('block_xp_logs', $batch);
            $migrated += count($batch);
        }

        $this->trace->output('Migration finished, ' . $migrated . ' record(s) migrated.');
        return $migrated;
    }

    /**
     * Get the reason type hash.
     *
     * Matches the logic in context_collection_logger::get_reason_type_hash().
     *
     * @param reason $reason The reason.
     * @return string
     */
    protected function get_reason_type_hash(reason $reason): string {
        $name = $this->reasonresolver->get_name($reason);
        $subtype = $reason instanceof reason_with_subtype ? $reason->get_subtype() : null;
        return substr(sha1($name . ':' . ($subtype ?? '')), 0, 9);
    }

    /**
     * Get the number of records still needing migration.
     *
     * @return int
     */
    public function get_remaining_migrations(): int {
        $sql = "SELECT COUNT(l.id)
                  FROM {block_xp_log} l
                 WHERE NOT EXISTS (
                    SELECT 1
                      FROM {block_xp_logs} x
                     WHERE x.legacysource = :legacysource
                       AND x.legacyid = l.id
                 )";

        return (int) $this->db->count_records_sql($sql, [
            'legacysource' => self::LEGACY_SOURCE_XP,
        ]);
    }

    /**
     * Get the batch size.
     *
     * @return int
     */
    public function get_batch_size(): int {
        return $this->batchsize;
    }

    /**
     * Get the limit.
     *
     * @return int|null
     */
    public function get_limit(): ?int {
        return $this->limit;
    }

    /**
     * Get the maximum runtime.
     *
     * @return int|null
     */
    public function get_max_runtime(): ?int {
        return $this->maxruntime;
    }

    /**
     * Set batch size.
     *
     * @param int $size The batch size.
     */
    public function set_batch_size(int $size): void {
        $this->batchsize = max(1, $size);
    }

    /**
     * Set limit on records to process per run.
     *
     * @param int $limit The maximum number of records to migrate.
     */
    public function set_limit(int $limit): void {
        $this->limit = $limit;
    }

    /**
     * Set maximum runtime in seconds.
     *
     * @param int|null $seconds Maximum runtime in seconds, null for no limit.
     */
    public function set_max_runtime(?int $seconds): void {
        $this->maxruntime = $seconds;
    }

    /**
     * Set the trace.
     *
     * @param \progress_trace $trace
     */
    public function set_trace(\progress_trace $trace): void {
        $this->trace = $trace;
    }
}
