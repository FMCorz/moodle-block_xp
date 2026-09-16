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

namespace block_xp\local\rule;

/**
 * Rule manager.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface rule_manager {
    /**
     * Count rules in the world's context.
     *
     * @param \context|null $childcontext The child context.
     * @param array $options Options (supports 'type' and 'filter').
     * @return int
     */
    public function count_rules(?\context $childcontext = null, array $options = []): int;

    /**
     * Delete a rule.
     *
     * @param int $ruleid The rule ID.
     */
    public function delete_rule(int $ruleid): void;

    /**
     * Detach from defaults.
     *
     * @return void
     */
    public function detach(): void;

    /**
     * Get the effective rules for collection.
     *
     * @param \context $actioncontext The action context.
     * @return instance[]
     */
    public function get_effective_rules(\context $actioncontext): array;

    /**
     * Get the effective rules grouped by type.
     *
     * @param \context $actioncontext The action context.
     * @return instance[][]
     */
    public function get_effective_rules_grouped_by_type(\context $actioncontext): array;

    /**
     * Get a rule by ID.
     *
     * @param int $ruleid The rule ID.
     * @return instance|null
     */
    public function get_rule(int $ruleid): ?instance;

    /**
     * Get rules.
     *
     * @param \context|null $childcontext The child context.
     * @return instance[]
     */
    public function get_rules(?\context $childcontext = null): array;

    /**
     * Whether the world is detached from the defaults.
     *
     * @return bool
     */
    public function is_detached(): bool;

    /**
     * Reset to the defaults.
     *
     * @return void
     */
    public function reset_to_defaults(): void;

    /**
     * Seed for editing.
     *
     * @return void
     */
    public function seed_for_editing(): void;

    /**
     * Update a rule.
     *
     * @param int $ruleid
     * @param \stdClass $data
     */
    public function update_rule(int $ruleid, \stdClass $data): void;
}
