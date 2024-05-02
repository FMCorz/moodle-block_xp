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

namespace block_xp\local\logger;

use block_xp\local\reason\reason;
use block_xp\local\ruletype\limit_spec;
use block_xp\local\ruletype\ruletype;
use DateTimeImmutable;

/**
 * Rule type occurrence indicator.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface ruletype_occurrence_indicator {

    /**
     * Has the rule type happened since.
     *
     * @param int $id The ID.
     * @param ruletype $ruletype The rule type.
     * @param DateTimeImmutable $since The since.
     * @param int $maxcount The max count.
     * @return bool
     */
    public function has_ruletype_happened_since(int $id, ruletype $ruletype, DateTimeImmutable $since, int $maxcount = 1): bool;

    /**
     * Is the rule type reason limit reached.
     *
     * @param int $id The ID.
     * @param ruletype $ruletype The rule type.
     * @param reason $reason The reason.
     * @param limit_spec $limit The limit.
     * @return bool
     */
    public function is_ruletype_reason_limit_reached(int $id, ruletype $ruletype, reason $reason, limit_spec $limit): bool;

}
