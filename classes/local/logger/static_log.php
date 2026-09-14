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
// See <https://levelup.plus>.

namespace block_xp\local\logger;

use block_xp\local\reason\reason;
use DateTimeImmutable;
use stdClass;

/**
 * Static log.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class static_log implements log {
    /** @var stdClass The log record. */
    protected stdClass $record;
    /** @var reason The reason. */
    protected reason $reason;
    /** @var stdClass The user. */
    protected stdClass $user;

    /**
     * Constructor.
     *
     * @param stdClass $record The log record.
     * @param stdClass $user The user.
     * @param reason $reason The reason.
     */
    public function __construct(stdClass $record, stdClass $user, reason $reason) {
        $this->record = $record;
        $this->reason = $reason;
        $this->user = $user;
    }

    /**
     * Get the log ID.
     *
     * @return int
     */
    public function get_id(): int {
        return $this->record->id;
    }

    /**
     * Get the context ID.
     *
     * @return int
     */
    public function get_context_id(): int {
        return $this->record->contextid;
    }

    /**
     * Get the recorded points.
     *
     * @return int
     */
    public function get_points(): int {
        return $this->record->points;
    }

    /**
     * Get the reason.
     *
     * @return reason
     */
    public function get_reason(): reason {
        return $this->reason;
    }

    /**
     * Get the time recorded.
     *
     * @return DateTimeImmutable
     */
    public function get_time_recorded(): DateTimeImmutable {
        return new DateTimeImmutable('@' . $this->record->timerecorded);
    }

    /**
     * Get the user ID.
     *
     * @return int
     */
    public function get_user_id(): int {
        return $this->record->userid;
    }

    /**
     * Get the user.
     *
     * @return stdClass The user object, may be partial.
     */
    public function get_user(): stdClass {
        return $this->user;
    }
}
