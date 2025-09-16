<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

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
use stdClass;

/**
 * Block instance finder.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_instance_finder implements instance_finder {

    /** @var moodle_database The DB. */
    protected $db;

    /** @var \cache_application The cache. */
    protected $cache;

    /**
     * Constructor.
     *
     * @param moodle_database $db The database.
     */
    public function __construct(moodle_database $db) {
        $this->db = $db;
        $this->cache = \cache::make('block_xp', 'xp_instances');
    }

    /**
     * Tries to find an instance of the block in a context.
     *
     * @param string $name The block name.
     * @param context $context The context to search in.
     * @return block_base Or null when none, or multiple.
     */
    public function get_instance_in_context($name, context $context) {
        $blockname = preg_replace('/^block_/i', '', $name);
        $cachekey = $blockname . '_' . $context->id;

        // Try to get from cache first.
        $cached = $this->cache->get($cachekey);
        if ($cached !== false) {
            if ($cached === null) {
                return null; // Cache hit for no instance or multiple instances.
            }
            return block_instance($cached->blockname, $cached);
        }

        // Not in cache, query database.
        $sql = "SELECT *
                  FROM {block_instances} bi
                 WHERE bi.blockname = :name
                   AND bi.parentcontextid = :contextid";

        $params = [
            'name' => $blockname,
            'contextid' => $context->id,
        ];

        $records = $this->db->get_records_sql($sql, $params);

        // Cache the result.
        if (!$records || count($records) > 1) {
            $this->cache->set($cachekey, null);
            return null;
        }

        $record = reset($records);
        $this->cache->set($cachekey, $record);
        return block_instance($record->blockname, $record);
    }

    /**
     * Add an instance to the cache.
     *
     * @param string $name The block name.
     * @param context $context The context.
     * @param stdClass $record The block instance record.
     */
    public function cache_instance($name, context $context, $record) {
        $blockname = preg_replace('/^block_/i', '', $name);
        $cachekey = $blockname . '_' . $context->id;
        $this->cache->set($cachekey, $record);
    }

    /**
     * Remove an instance from the cache.
     *
     * @param string $name The block name.
     * @param context $context The context.
     */
    public function uncache_instance($name, context $context) {
        $blockname = preg_replace('/^block_/i', '', $name);
        $cachekey = $blockname . '_' . $context->id;
        $this->cache->delete($cachekey);
    }

}
