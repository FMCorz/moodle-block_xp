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

namespace block_xp\local\sql;

/**
 * Query.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class query {
    /** @var array The conditions. */
    private $conditions = [];
    /** @var array[] The ordered pairs of ordering keys and directions. */
    private $orderby = [];

    /**
     * Append ordering.
     *
     * @param string $key The ordering key for the consuming code to interpret.
     * @param int $direction SORT_ASC or SORT_DESC.
     */
    final protected function append_order_by(string $key, int $direction): void {
        $this->orderby[] = [$key, $direction];
    }

    /**
     * Get a condition.
     *
     * @param string $name The condition name.
     * @return mixed
     */
    final public function get_condition(string $name) {
        return $this->conditions[$name];
    }

    /**
     * Get ordering.
     *
     * Keys and directions are not safe to interpolate into SQL. Consuming code must
     * map supported keys to trusted SQL expressions and validate the directions.
     *
     * @return array[] Ordered pairs of ordering keys and directions.
     */
    final public function get_order_by(): array {
        return $this->orderby;
    }

    /**
     * Whether a condition is set.
     *
     * @param string $name The condition name.
     * @return bool
     */
    final public function has_condition(string $name): bool {
        return array_key_exists($name, $this->conditions);
    }

    /**
     * Reset the current order by.
     *
     * @return self
     */
    final public function reset_order_by(): self {
        $this->orderby = [];
        return $this;
    }

    /**
     * Set a condition.
     *
     * @param string $name The condition name.
     * @param mixed $value The value.
     */
    final protected function set_condition(string $name, $value): void {
        $this->conditions[$name] = $value;
    }

    /**
     * Unset a condition.
     *
     * @param string $name The condition name.
     */
    final protected function unset_condition(string $name): void {
        unset($this->conditions[$name]);
    }
}
