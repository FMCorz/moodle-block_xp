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

namespace block_xp\local\utils;

use block_xp\local\reason\reason;
use block_xp\local\reason\reason_with_tracking;

/**
 * Reason utilities.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class reason_utils {

    /**
     * Get backfilled tracking values for a reason that does not implement tracking.
     *
     * Provides envid, parentid, objectid for reasons that do not yet implement
     * reason_with_tracking. Used as a temporary measure until those reasons are upgraded.
     *
     * @param reason $reason The reason.
     * @return \stdClass Object with envid, parentid, objectid.
     */
    public static function get_backfilled_tracking_values(reason $reason): \stdClass {
        if ($reason instanceof reason_with_tracking) {
            throw new \coding_exception('Reason implements reason_with_tracking, no need to backfill.');
        }

        $result = (object) [
            'envid' => null,
            'parentid' => null,
            'objectid' => null,
        ];

        if ($reason instanceof \block_gearup\local\xp\mission_completed_reason && method_exists($reason, 'get_signature')) {
            $result->objectid = (int) $reason->get_signature();
        }

        return $result;
    }

}
