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
 * Course world instance finder.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\block;

use block_xp\di;
use context;
use context_course;
use context_system;
use coding_exception;
use moodle_database;
use stdClass;

/**
 * Course world instance finder.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_world_instance_finder implements
    any_instance_finder_in_context,
    instances_finder_in_context,
    instance_checker,
    instance_finder {

    /** @var moodle_database The DB. */
    protected $db;
    /** @var default_instance_finder The default finder. */
    protected $defaultfinder;
    /** @var \cache The block instances cache. */
    protected $cache;
    /** @var int The dashboard page ID. */
    protected $dashboardpageid;

    /**
     * Constructor.
     *
     * @param moodle_database $db The database.
     */
    public function __construct(moodle_database $db) {
        $this->db = $db;
        $this->defaultfinder = new default_instance_finder($db);
        $this->cache = di::get('block_count_cache');
    }

    /**
     * Count instances in context.
     *
     * @param string $name The block name.
     * @param context $context The context to search in.
     * @return int
     */
    public function count_instances_in_context($name, context $context) {
        $key = "{$name}_{$context->id}";
        $count = $this->cache->get($key);

        if ($count === false) {
            $count = (int) $this->get_candidates_in_context($name, $context, true);
            $this->cache->set($key, $count);
        }

        return (int) $count;
    }

    /**
     * Get the candidates.
     *
     * @param string $name The name of the block.
     * @param context $context The world context.
     * @param bool $countonly Whether to only count.
     * @return stdClass[]|int
     */
    protected function get_candidates_in_context($name, context $context, $countonly = false) {
        $name = preg_replace('/^block_/i', '', $name);
        if ($name !== 'xp') {
            throw new coding_exception('This implementation is only meant to be used with block_xp.');
        }

        // The world is in a course context, this means that we are not in whole site mode
        // and we can defer that to our default finder as we expect to be finding the block
        // on the course page, e.g. there aren't exceptions to take care of yet.
        if ($context instanceof context_course) {
            if ($countonly) {
                return $this->defaultfinder->count_instances_in_context($name, $context);
            }
            $instance = $this->defaultfinder->get_instance_in_context($name, $context);
            if (!$instance) {
                return [];
            }
            return [$instance];
        } else if (!$context instanceof context_system) {
            throw new coding_exception('Invalid context passed, expected course or system.');
        }

        // Now, there are two locations we want to be checking: the front page,
        // and the default dashboard of our users. If the front page one exists we
        // take it because the latter can be displayed throughout the entire site.
        $select = $countonly ? 'COUNT(1)' : '*';
        $orderbyctx = "ORDER BY CASE
                                WHEN bi.parentcontextid = :fpcontextid2 THEN 1
                                WHEN bi.parentcontextid <> :fpcontextid3 THEN 0
                                END DESC,
                                bi.id ASC";
        $orderby = $countonly ? '' : $orderbyctx;

        $sql = "SELECT {$select}
                  FROM {block_instances} bi
                 WHERE bi.blockname = :name
                   AND (bi.parentcontextid = :fpcontextid
                        OR (bi.parentcontextid = :syscontextid
                            AND bi.pagetypepattern = :syspagetype
                            AND bi.subpagepattern = :syssubpage
                            )
                        )
                {$orderby}";

        $fpcontext = context_course::instance(SITEID);
        $params = [
            'name' => $name,
            'fpcontextid' => $fpcontext->id,
            'fpcontextid2' => $fpcontext->id,
            'fpcontextid3' => $fpcontext->id,
            'syscontextid' => $context->id,
            'syspagetype' => 'my-index',
            'syssubpage' => $this->get_dashboard_page_id(),
        ];

        // Return instances.
        if ($countonly) {
            return $this->db->count_records_sql($sql, $params);
        }

        $records = $this->db->get_records_sql($sql, $params);
        return array_map(function ($record) {
            return block_instance($record->blockname, $record);
        }, $records);
    }

    /**
     * Finds any instance in a context.
     *
     * @param string $name The block name, without 'block_'.
     * @param context $context The context to search in.
     * @return block_base Null when none found, else first match.
     */
    public function get_any_instance_in_context($name, context $context) {
        $candidates = $this->get_candidates_in_context($name, $context);
        if (!$candidates) {
            return null;
        }
        return reset($candidates);
    }

    /**
     * Tries to find an instance of the block in a context.
     *
     * @param string $name The block name.
     * @param context $context The context of the course world.
     * @return block_base Or null when none, or multiple.
     */
    public function get_instance_in_context($name, context $context) {
        $candidates = $this->get_candidates_in_context($name, $context);
        if (!$candidates || count($candidates) > 1) {
            return null;
        }
        return reset($candidates);
    }

    /**
     * Get all the instances in context.
     *
     * @param string $name The block name.
     * @param context $context The context of the course world.
     * @return block_base[]
     */
    public function get_instances_in_context($name, context $context) {
        return $this->get_candidates_in_context($name, $context);
    }

    /**
     * Whether it has an instance in the context.
     *
     * @param string $name The block name.
     * @param context $context The context to search in.
     * @return bool
     */
    public function has_instance_in_context($name, context $context) {
        return $this->count_instances_in_context($name, $context) > 0;
    }

    /**
     * Get dashboard page ID.
     *
     * @return int
     */
    protected function get_dashboard_page_id() {
        global $CFG;

        if (!$this->dashboardpageid) {
            require_once($CFG->dirroot . '/my/lib.php');
            $page = my_get_page(null, MY_PAGE_PRIVATE);
            $this->dashboardpageid = $page->id;
        }

        return $this->dashboardpageid;
    }

}
