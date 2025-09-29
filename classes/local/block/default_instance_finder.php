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

/**
 * Block instance finder.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\block;

use context;
use moodle_database;

/**
 * Block instance finder.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_instance_finder implements instance_finder, instance_checker {

    /** @var moodle_database The DB. */
    protected $db;

    /**
     * Constructor.
     *
     * @param moodle_database $db The database.
     */
    public function __construct(moodle_database $db) {
        $this->db = $db;
    }

    /**
     * Count instances in context.
     *
     * @param string $name The block name, without 'block_'.
     * @param context $context The context to search in.
     * @return int
     */
    public function count_instances_in_context($name, context $context) {
        return $this->db->count_records('block_instances', [
            'blockname' => preg_replace('/^block_/i', '', $name),
            'parentcontextid' => $context->id,
        ]);
    }

    /**
     * Tries to find an instance of the block in a context.
     *
     * @param string $name The block name.
     * @param context $context The context to search in.
     * @return block_base Or null when none, or multiple.
     */
    public function get_instance_in_context($name, context $context) {
        $sql = "SELECT *
                  FROM {block_instances} bi
                 WHERE bi.blockname = :name
                   AND bi.parentcontextid = :contextid";

        $params = [
            'name' => preg_replace('/^block_/i', '', $name),
            'contextid' => $context->id,
        ];

        $records = $this->db->get_records_sql($sql, $params);
        if (!$records || count($records) > 1) {
            return null;
        }

        $record = reset($records);
        return block_instance($record->blockname, $record);
    }

    /**
     * Whether it has an instance in the context.
     *
     * @param string $name The block name, without 'block_'.
     * @param context $context The context to search in.
     * @return bool
     */
    public function has_instance_in_context($name, context $context) {
        return $this->db->record_exists('block_instances', [
            'blockname' => preg_replace('/^block_/i', '', $name),
            'parentcontextid' => $context->id,
        ]);
    }

}
