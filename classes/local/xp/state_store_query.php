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

namespace block_xp\local\xp;

use block_xp\local\userfilter\user_filter;
use coding_exception;

/**
 * State store query.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class state_store_query {
    /** @var array The conditions. */
    protected $conditions = [];
    /** @var array[] The ordered pairs of field names and directions. */
    protected $orderby = [];

    /**
     * Add ordering, from most to least significant.
     *
     * @param string $field The field name.
     * @param int $direction SORT_ASC or SORT_DESC.
     * @return self
     */
    public function add_order_by(string $field, int $direction = SORT_ASC): self {
        $fields = ['xp', 'id', 'firstname', 'lastname', 'firstnamephonetic', 'lastnamephonetic', 'middlename', 'alternatename'];
        if (!in_array($field, $fields, true)) {
            throw new coding_exception('Unsupported state sort field.');
        }
        if ($direction !== SORT_ASC && $direction !== SORT_DESC) {
            throw new coding_exception('Unsupported state sort direction.');
        }
        $this->append_order_by($field, $direction);
        return $this;
    }

    /**
     * Append ordering.
     *
     * @param string $field The field name.
     * @param int $direction SORT_ASC or SORT_DESC.
     */
    final protected function append_order_by(string $field, int $direction): void {
        $this->orderby[] = [$field, $direction];
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
     * @return array[] Ordered pairs of field names and directions.
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
     * Set a condition.
     *
     * @param string $name The condition name.
     * @param mixed $value The value.
     */
    final protected function set_condition(string $name, $value): void {
        $this->conditions[$name] = $value;
    }

    /**
     * Set the user name search term.
     *
     * @param string|null $term The term, or null to remove the condition.
     * @return self
     */
    public function set_term(?string $term): self {
        if ($term === null) {
            $this->unset_condition('term');
        } else {
            $this->set_condition('term', $term);
        }
        return $this;
    }

    /**
     * Set the user filter.
     *
     * @param user_filter|null $filter The filter, or null to remove the condition.
     * @return self
     */
    public function set_user_filter(?user_filter $filter): self {
        if ($filter === null) {
            $this->unset_condition('userfilter');
        } else {
            $this->set_condition('userfilter', $filter);
        }
        return $this;
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
