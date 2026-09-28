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

namespace block_xp\local\userfilter;

use block_xp\di;

/**
 * Filter users belonging to any of the given groups.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class groups_members implements user_filter {
    /** @var int[] */
    protected $groupids;

    /**
     * Constructor.
     *
     * @param int[] $groupids Group IDs, matching membership in any group.
     */
    public function __construct(array $groupids) {
        $this->groupids = $groupids;
    }

    /**
     * Get the SQL fragment to filter users.
     *
     * @return array
     */
    public function get_sql(string $useridalias): array {
        if (empty($this->groupids)) {
            return ['1=0', []];
        }
        [$groupssql, $params] = di::get('db')->get_in_or_equal(
            $this->groupids,
            SQL_PARAMS_NAMED,
            static::generate_param_name() . '_'
        );
        $sql = "$useridalias IN (SELECT userid FROM {groups_members} WHERE groupid $groupssql)";
        return [$sql, $params];
    }

    /**
     * Generate a parameter name.
     *
     * @return string
     */
    protected static function generate_param_name(): string {
        static $i = 0;
        return 'xpufgms' . $i++;
    }
}
