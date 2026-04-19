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

use block_xp\di;
use block_xp\local\config\config;
use block_xp\local\config\course_world_config;
use block_xp\local\config\static_config;
use moodle_database;

/**
 * Admin rule manager.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_rule_manager {

    /** @var moodle_database The database. */
    protected $db;
    /** @var config The admin config. */
    protected $config;
    /** @var bool Whether XP is configured sitewide. */
    protected $issitewide;

    /**
     * Constructor.
     *
     * @param moodle_database $db The database.
     * @param config $config The admin config.
     */
    public function __construct(moodle_database $db, config $config) {
        $this->db = $db;
        $this->config = $config;
        $this->issitewide = $config->get('context') == CONTEXT_SYSTEM;
    }

    /**
     * Count admin default rules.
     *
     * @param array $options Options (supports 'type' and 'filter').
     * @return int
     */
    public function count_rules(array $options = []): int {
        $this->ensure_seeded();
        $conditions = ['contextid' => 0, 'childcontextid' => 0];
        if (!empty($options['type'])) {
            $conditions['type'] = $options['type'];
        }
        if (!empty($options['filter'])) {
            $conditions['filter'] = $options['filter'];
        }
        return $this->db->count_records('block_xp_rule', $conditions);
    }

    /**
     * Delete an admin default rule.
     *
     * @param int $ruleid The rule ID.
     */
    public function delete_rule(int $ruleid): void {
        $this->db->delete_records('block_xp_rule', ['id' => $ruleid, 'contextid' => 0]);
    }

    /**
     * Seed the static defaults into the database if not yet done.
     */
    protected function ensure_seeded(): void {
        if ((int) $this->config->get('actionrulesstate') !== course_world_config::DEFAULT_ACTION_RULES_MISSING) {
            return;
        }
        foreach ($this->get_static_default_rule_templates() as $template) {
            $this->db->insert_record('block_xp_rule', (object) array_merge($template, [
                'contextid' => 0,
                'childcontextid' => 0,
            ]));
        }
        $this->config->set('actionrulesstate', course_world_config::DEFAULT_ACTION_RULES_NOOP);
    }

    /**
     * Get the records.
     *
     * @return \stdClass[]
     */
    protected function get_records(): array {
        $this->ensure_seeded();
        return $this->fetch_records();
    }

    /**
     * Get the records prepared for a world.
     *
     * @return \stdClass[]
     */
    public function get_records_for_world(): array {
        return array_map(function ($record) {
            return $this->prepare_record_for_world($record);
        }, $this->get_records());
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
     * Get the rules.
     *
     * @return instance[]
     */
    public function get_rules(): array {
        $this->ensure_seeded();
        return array_values(array_map(function ($record) {
            return $this->make_instance($record);
        }, $this->get_records()));
    }

    /**
     * Get the rules prepared for a world.
     *
     * @return instance[]
     */
    public function get_rules_for_world(): array {
        return array_values(array_map(function ($record) {
            return $this->make_instance($record);
        }, $this->get_records_for_world()));
    }

    /**
     * Fetch a single rule record by ID.
     *
     * @param int $ruleid The rule ID.
     * @return \stdClass|false
     */
    protected function fetch_record(int $ruleid) {
        return $this->db->get_record('block_xp_rule', ['id' => $ruleid, 'contextid' => 0], '*', IGNORE_MISSING);
    }

    /**
     * Fetch the records.
     *
     * @return \stdClass[]
     */
    protected function fetch_records(): array {
        return $this->db->get_records('block_xp_rule', ['contextid' => 0, 'childcontextid' => 0], 'id ASC');
    }

    /**
     * Static default rule templates.
     *
     * @return array[]
     */
    protected function get_static_default_rule_templates(): array {
        return [
            [
                'points' => 9,
                'type' => 'consume_content',
                'filter' => 'any',
            ],
            [
                'points' => 45,
                'type' => 'produce_content',
                'filter' => 'any',
            ],
        ];
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
     * Prepare a record for a world.
     *
     * @param \stdClass $record The record.
     * @return \stdClass
     */
    protected function prepare_record_for_world(\stdClass $record): \stdClass {
        $record = (object) (array) $record;
        $record->filter = $this->translate_filter_for_world($record->filter);
        return $record;
    }

    /**
     * Translate a filter for a world.
     *
     * @param string $filter The filter name.
     * @return string
     */
    protected function translate_filter_for_world(string $filter): string {
        if ($this->issitewide && $filter === 'thiscourse') {
            return 'anycourse';
        } else if (!$this->issitewide && $filter === 'anycourse') {
            return 'thiscourse';
        }
        return $filter;
    }

    /**
     * Reset to the defaults.
     *
     * @return void
     */
    public function reset_all_worlds_to_defaults(): void {
        $this->db->delete_records_select('block_xp_rule', 'contextid > 0', []);
        di::get('bulk_world_config_setter')->set_from(new static_config([
            'defaultactionrules' => course_world_config::DEFAULT_ACTION_RULES_MISSING,
        ]));
    }
}
