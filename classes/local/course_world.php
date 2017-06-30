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
 * Course World.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local;
defined('MOODLE_INTERNAL') || die();

use context_course;
use context_system;
use moodle_database;
use block_xp\local\config\config;
use block_xp\local\config\course_world_config;

/**
 * Course World.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_world implements world {

    /** @var config The config. */
    protected $config;
    /** @var context The context. */
    protected $context;
    /** @var int The course ID. */
    protected $courseid;
    /** @var moodle_database The DB. */
    protected $db;
    /** @var levels_info The levels info. */
    protected $levelsinfo;
    /** @var course_user_event_collection_logger The logger. */
    protected $logger;
    /** @var access_permissions The access permissions. */
    protected $perms;
    /** @var user_state_course_store The store. */
    protected $store;
    /** @var collection_strategy The collection strategy. */
    protected $strategy;
    /** @var course_filter_manager The filter manager. */
    protected $filtermanager;

    /**
     * Constructor.
     *
     * @param config $config The course config.
     * @param moodle_database $db The DB.
     * @param int $courseid The course ID.
     */
    public function __construct(config $config, moodle_database $db, $courseid) {
        $this->config = $config;
        $this->courseid = $courseid;
        $this->db = $db;

        if ($courseid == SITEID) {
            $this->context = context_system::instance();
        } else {
            $this->context = context_course::instance($courseid);
        }

        $this->perms = new \block_xp\local\permission\context_permissions($this->context);
    }

    public function get_access_permissions() {
        return $this->perms;
    }

    public function get_config() {
        return $this->config;
    }

    public function get_collection_strategy() {
        if (!$this->strategy) {
            $this->strategy = new \block_xp\local\strategy\course_world_collection_strategy(
                $this->get_context(),
                $this->get_config(),
                $this->get_levels_info(),
                $this->get_store(),
                $this->get_filter_manager(),
                $this->get_user_event_collection_logger(),
                $this->get_level_up_notification_service()
            );
        }
        return $this->strategy;
    }

    /**
     * Get context.
     *
     * @return context
     */
    public function get_context() {
        return $this->context;
    }

    /**
     * Get course ID.
     *
     * @return int
     */
    public function get_courseid() {
        return $this->courseid;
    }

    /**
     * Get filter manager.
     *
     * @return course_filter_manager
     */
    public function get_filter_manager() {
        if (!$this->filtermanager)  {
            $this->filtermanager = new \block_xp\local\xp\course_filter_manager($this->db, $this->courseid);

            $config = $this->get_config();
            $state = $config->get('defaultfilters');
            if ($state == course_world_config::DEFAULT_FILTERS_NOOP) {
                // Early bail.
                return $this->filtermanager;

            } else if ($state == course_world_config::DEFAULT_FILTERS_MISSING) {
                // The default filters were not applied yet.
                $this->filtermanager->import_default_filters();
                $config->set('defaultfilters', course_world_config::DEFAULT_FILTERS_NOOP);

            } else if ($state == course_world_config::DEFAULT_FILTERS_STATIC) {
                // We are in a legacy state, convert.
                $this->filtermanager->convert_static_filters_to_regular();
                $config->set('defaultfilters', course_world_config::DEFAULT_FILTERS_NOOP);
            }

        }
        return $this->filtermanager;
    }

    public function get_levels_info() {
        if (!$this->levelsinfo) {
            $config = $this->get_config();
            $data = json_decode($config->get('levelsdata'), true);
            $context = $config->get('enablecustomlevelbadges') ? $this->get_context() : null;
            if (!$data) {
                $this->levelsinfo = \block_xp\local\xp\algo_levels_info::make_from_defaults($context);
            } else {
                $this->levelsinfo = new \block_xp\local\xp\algo_levels_info($data, $context);
            }
        }
        return $this->levelsinfo;
    }

    /**
     * Get level up notification service.
     *
     * @return course_level_up_notification_service
     */
    public function get_level_up_notification_service() {
        return new \block_xp\local\notification\course_level_up_notification_service($this->courseid);
    }

    /**
     * Get store.
     *
     * @return user_state_course_store
     */
    public function get_store() {
        if (!$this->store) {
            $this->store = new \block_xp\local\xp\user_state_course_store($this->db, $this->get_levels_info(),
                $this->get_courseid());
        }
        return $this->store;
    }

    /**
     * Get logger.
     *
     * @return course_user_event_collection_logger
     */
    public function get_user_event_collection_logger() {
        if (!$this->logger) {
            $this->logger = new \block_xp\local\logger\course_user_event_collection_logger($this->db, $this->courseid);
        }
        return $this->logger;
    }

}
