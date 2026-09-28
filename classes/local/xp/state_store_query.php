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

use block_xp\local\sql\query;
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
class state_store_query extends query {
    /** @var string[] Identity fields allowed when matching the term. */
    protected $allowedidentityfields = [];

    /**
     * Add ordering, from most to least significant.
     *
     * @param string $key The supported ordering key.
     * @param int $direction SORT_ASC or SORT_DESC.
     * @return self
     */
    public function add_order_by(string $key, int $direction = SORT_ASC): self {
        $keys = ['xp', 'id', 'firstname', 'lastname', 'firstnamephonetic', 'lastnamephonetic', 'middlename', 'alternatename'];
        if (!in_array($key, $keys, true)) {
            throw new coding_exception('Unsupported state sort key.');
        }
        if ($direction !== SORT_ASC && $direction !== SORT_DESC) {
            throw new coding_exception('Unsupported state sort direction.');
        }
        $this->append_order_by($key, $direction);
        return $this;
    }

    /**
     * Get the identity fields allowed when matching the term.
     *
     * @return string[]
     */
    public function get_allowed_identity_fields(): array {
        return $this->allowedidentityfields;
    }

    /**
     * Set the identity fields allowed when matching the term.
     *
     * The caller is responsible for checking field visibility.
     *
     * @param string[] $fields Allowed identity fields, or an empty array for names only.
     * @return self
     */
    public function set_allowed_identity_fields(array $fields): self {
        $this->allowedidentityfields = $fields;
        return $this;
    }

    /**
     * Set the user search term.
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
}
