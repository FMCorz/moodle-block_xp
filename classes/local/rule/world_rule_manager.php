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

namespace block_xp\local\rule;

use block_xp\local\config\course_world_config;
use block_xp\local\world;
use moodle_database;

/**
 * World rule manager.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class world_rule_manager {

    /** @var admin_rule_manager The admin rule manager. */
    protected $adminrulemanager;
    /** @var moodle_database The database. */
    protected $db;
    /** @var world The world. */
    protected $world;

    /** @var array Cache indexed by context key. */
    protected $rulesinctxcache = [];

    /**
     * Constructor.
     *
     * @param moodle_database $db The database.
     * @param world $world The world.
     * @param admin_rule_manager $adminrulemanager The admin rule manager.
     */
    public function __construct(moodle_database $db, world $world, admin_rule_manager $adminrulemanager) {
        $this->db = $db;
        $this->world = $world;
        $this->adminrulemanager = $adminrulemanager;
    }

    /**
     * Count rules in the world's context.
     *
     * @param \context|null $childcontext The child context.
     * @param array $options Options (supports 'type' and 'filter').
     * @return int
     */
    public function count_rules(?\context $childcontext = null, array $options = []): int {
        $storecontext = $this->world->get_context();
        $conditions = [
            'contextid' => $storecontext->id,
            'childcontextid' => $this->normalise_childcontext_id($childcontext),
        ];
        if (!empty($options['type'])) {
            $conditions['type'] = $options['type'];
        }
        if (!empty($options['filter'])) {
            $conditions['filter'] = $options['filter'];
        }
        return $this->db->count_records('block_xp_rule', $conditions);
    }

    /**
     * Delete a rule.
     *
     * @param int $ruleid The rule ID.
     */
    public function delete_rule(int $ruleid): void {
        $storecontext = $this->world->get_context();
        $this->db->delete_records('block_xp_rule', ['id' => $ruleid, 'contextid' => $storecontext->id]);
        $this->rulesinctxcache = [];
    }

    /**
     * Delete world rules.
     *
     * @return void
     */
    protected function delete_all_rules(): void {
        $storecontext = $this->world->get_context();
        $this->db->delete_records('block_xp_rule', ['contextid' => $storecontext->id]);
    }

    /**
     * Detach from defaults.
     *
     * @return void
     */
    public function detach(): void {
        if ($this->is_detached()) {
            return;
        }
        $this->world->get_config()->set('defaultactionrules', course_world_config::DEFAULT_ACTION_RULES_NOOP);
    }

    /**
     * Get the effective rules for collection.
     *
     * @param \context $actioncontext The action context.
     * @return instance[]
     */
    public function get_effective_rules(\context $actioncontext): array {
        if (!$this->is_detached()) {
            return $this->adminrulemanager->get_rules_for_world();
        }

        $storecontext = $this->world->get_context();
        $contexts = $this->get_effective_contexts($storecontext, $actioncontext);
        $rules = [];
        foreach ($contexts as $context) {
            $rules = array_merge($rules, $this->get_rules_in_context($storecontext, $context));
        }
        return $rules;
    }

    /**
     * Get the effective rules grouped by type.
     *
     * @param \context $actioncontext The action context.
     * @return instance[][]
     */
    public function get_effective_rules_grouped_by_type(\context $actioncontext): array {
        $rules = $this->get_effective_rules($actioncontext);
        $groupedrules = [];
        foreach ($rules as $rule) {
            $typename = $rule->get_type_name();
            if (!isset($groupedrules[$typename])) {
                $groupedrules[$typename] = [];
            }
            $groupedrules[$typename][] = $rule;
        }
        return $groupedrules;
    }

    /**
     * Get a rule by ID.
     *
     * @param int $ruleid The rule ID.
     * @return instance|null
     */
    public function get_rule(int $ruleid): ?instance {
        $record = $this->fetch_record($ruleid);
        return $record ? $this->make_instance($record) : null;
    }

    /**
     * Get rules.
     *
     * @param \context|null $childcontext The child context.
     * @return instance[]
     */
    public function get_rules(?\context $childcontext = null): array {
        return $this->get_rules_in_context($this->world->get_context(), $childcontext);
    }

    /**
     * Whether the world is detached from the defaults.
     *
     * @return bool
     */
    public function is_detached(): bool {
        return $this->world->get_config()->get('defaultactionrules') != course_world_config::DEFAULT_ACTION_RULES_MISSING;
    }

    /**
     * Reset to the defaults.
     *
     * @return void
     */
    public function reset_to_defaults(): void {
        $this->delete_all_rules();
        $this->world->get_config()->set('defaultactionrules', course_world_config::DEFAULT_ACTION_RULES_MISSING);
        $this->rulesinctxcache = [];
    }

    /**
     * Seed for editing.
     *
     * @return void
     */
    public function seed_for_editing(): void {
        if ($this->is_detached()) {
            return;
        }

        $storecontext = $this->world->get_context();
        $adminrecords = $this->adminrulemanager->get_records_for_world();
        $worldrecords = $this->fetch_records_in_context($storecontext, null);

        if ($this->are_records_matching($worldrecords, $adminrecords)) {
            return;
        }

        $this->delete_all_rules();
        $this->insert_rule_records($adminrecords);
        $this->rulesinctxcache = [];
    }

    /**
     * Whether records are matching.
     *
     * @param \stdClass[] $worldrecords The world's rule records.
     * @param \stdClass[] $adminrecords The admin default rule records.
     * @return bool
     */
    protected function are_records_matching(array $worldrecords, array $adminrecords): bool {
        if (count($worldrecords) !== count($adminrecords)) {
            return false;
        } else if (empty($worldrecords)) {
            return true;
        }

        $sorter = function ($a, $b) {
            if ($a->id != $b->id) {
                return $a->id - $b->id;
            } else if ($a->points != $b->points) {
                return $a->points - $b->points;
            }
            return strcmp($a->type, $b->type);
        };
        usort($worldrecords, $sorter);
        usort($adminrecords, $sorter);

        $normaliser = function ($record) {
            $record = (array) $record; // Make sure we don't change the original.
            unset($record['id']);
            unset($record['contextid']);
            unset($record['childcontextid']);
            return $record;
        };
        $worldrecords = array_map($normaliser, $worldrecords);
        $adminrecords = array_map($normaliser, $adminrecords);

        return $worldrecords === $adminrecords;
    }

    /**
     * Fetch a single rule record.
     *
     * @param int $ruleid The rule ID.
     * @return \stdClass|false
     */
    protected function fetch_record(int $ruleid) {
        $sql = "SELECT r.*
                  FROM {block_xp_rule} r
                  JOIN {context} ctx ON ctx.id = r.contextid
             LEFT JOIN {context} childctx ON childctx.id = r.childcontextid
                 WHERE r.id = :ruleid
                   AND r.contextid = :contextid";
        $params = [
            'ruleid' => $ruleid,
            'contextid' => $this->world->get_context()->id,
        ];
        return $this->db->get_record_sql($sql, $params, IGNORE_MISSING);
    }

    /**
     * Fetch the records in context.
     *
     * @param \context $storecontext The context.
     * @param \context|null $childcontext The child context.
     * @return \stdClass[]
     */
    protected function fetch_records_in_context(\context $storecontext, ?\context $childcontext = null): array {
        $sql = "SELECT r.*
                  FROM {block_xp_rule} r
                  JOIN {context} ctx ON ctx.id = r.contextid
             LEFT JOIN {context} childctx ON childctx.id = r.childcontextid
                 WHERE r.contextid = :contextid
                   AND r.childcontextid = :childcontextid
              ORDER BY r.id";
        $params = [
            'contextid' => $storecontext->id,
            'childcontextid' => $this->normalise_childcontext_id($childcontext),
        ];
        return $this->db->get_records_sql($sql, $params);
    }

    /**
     * Fetch rules in context.
     *
     * @param \context $storecontext The context.
     * @param \context|null $childcontext The child context.
     * @return instance[]
     */
    protected function fetch_rules_in_context(\context $storecontext, ?\context $childcontext = null): array {
        return array_values(array_map(function ($record) {
            return $this->make_instance($record);
        }, $this->fetch_records_in_context($storecontext, $childcontext)));
    }

    /**
     * Get the effective contexts.
     *
     * @param \context $storecontext The context.
     * @param \context|null $actioncontext The action context.
     * @return \context[]
     */
    protected function get_effective_contexts(\context $storecontext, \context $actioncontext): array {
        $contexts = [$storecontext];
        if (!$storecontext instanceof \context_course) {
            $subcontext = $actioncontext->get_course_context(false) ?: null;
            if ($subcontext && $subcontext instanceof \context_course) {
                $contexts[] = $subcontext;
            }
        }
        return $contexts;
    }

    /**
     * Get rules in context with caching.
     *
     * @param \context $storecontext The store context.
     * @param \context|null $childcontext The child context.
     * @return instance[]
     */
    protected function get_rules_in_context(\context $storecontext, ?\context $childcontext = null): array {
        $cachekey = $storecontext->id . ':' . ($childcontext ? $childcontext->id : 0);
        if (!isset($this->rulesinctxcache[$cachekey])) {
            $this->rulesinctxcache[$cachekey] = $this->fetch_rules_in_context($storecontext, $childcontext);
        }
        return $this->rulesinctxcache[$cachekey];
    }

    /**
     * Insert rule records.
     *
     * @param \stdClass[] $records The admin rule records to copy.
     */
    protected function insert_rule_records(array $rulerecords): void {
        foreach ($rulerecords as $record) {
            $this->insert_record($record);
        }
    }

    /**
     * Insert a record.
     *
     * @param \stdClass $record The record.
     * @return int
     */
    protected function insert_record(\stdClass $record): int {
        $storecontext = $this->world->get_context();
        $record = (object) (array) $record;
        unset($record->id);
        unset($record->contextid);
        unset($record->childcontextid);
        $record->contextid = $storecontext->id;
        return $this->db->insert_record('block_xp_rule', $record);
    }

    /**
     * Make an instance.
     *
     * @param \stdClass $record The record.
     * @return instance
     */
    protected function make_instance(\stdClass $record): instance {
        return new static_instance($record);
    }

    /**
     * Normalise child context ID.
     *
     * @param \context|null $childcontext The child context.
     * @return int
     */
    protected function normalise_childcontext_id(?\context $childcontext = null): int {
        $storecontext = $this->world->get_context();
        if ($childcontext && $childcontext->is_child_of($storecontext, false)) {
            return $childcontext->id;
        }
        return 0;
    }

}
