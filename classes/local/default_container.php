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
 * Dependency container.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local;
defined('MOODLE_INTERNAL') || die();

use coding_exception;
use moodle_url;

/**
 * Dependency container.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_container implements container {

    /** @var array The objects supported by this container. */
    protected static $supports = [
        'base_url' => true,
        'block_class' => true,
        'block_edit_form_class' => true,
        'collection_strategy' => true,
        'course_world_factory' => true,
        'db' => true,
        'file_server' => true,
        'observer_rules_maker' => true,
        'renderer' => true,
        'router' => true,
        'settings_maker' => true,
        'tasks_definition_maker' => true,
        'url_resolver' => true
    ];

    /** @var array Object instances. */
    protected $instances = [];
    /** @var \block_xp\local\factory\factory Multi-purpose factory. */
    protected $factory;

    /**
     * Get a thing.
     *
     * @param string $id The thing's name.
     * @return mixed
     */
    public function get($id) {
        if (!isset($this->instances[$id])) {
            if (!array_key_exists($id, static::$supports)) {
                throw new coding_exception('Unknown thing to create: ' . $id);
            }
            $method = 'get_' . $id;
            $this->instances[$id] = $this->{$method}();
        }
        return $this->instances[$id];
    }

    /**
     * Get the base URL.
     *
     * @return moodle_url
     */
    protected function get_base_url() {
        return new moodle_url('/blocks/xp/index.php');
    }

    /**
     * Block class.
     *
     * @return string
     */
    protected function get_block_class() {
        return 'block_xp\local\block\course_block';
    }

    /**
     * Block edit form class.
     *
     * @return string
     */
    protected function get_block_edit_form_class() {
        return 'block_xp\form\edit_form';
    }

    /**
     * Collection strategy.
     *
     * @return collection_strategy
     */
    protected function get_collection_strategy() {
        global $CFG;
        return new \block_xp\local\strategy\global_collection_strategy(
            $this->get_factory(),
            $CFG->block_xp_context
        );
    }

    /**
     * Course world factory.
     *
     * @return course_world_factory
     */
    protected function get_course_world_factory() {
        return $this->get_factory();
    }

    /**
     * Get DB.
     *
     * @return moodle_database
     */
    protected function get_db() {
        global $DB;
        return $DB;
    }

    /**
     * Get factory.
     *
     * @return factory
     */
    protected function get_factory() {
        global $CFG;
        if (!$this->factory) {
            $factory = new \block_xp\local\factory\factory(
                $this->get('db'),
                $CFG->block_xp_context
            );
        }
        return $factory;
    }

    /**
     * Get the file server.
     *
     * @return file_server
     */
    protected function get_file_server() {
        global $CFG;
        return new \block_xp\local\file\file_server(get_file_storage(), $CFG->block_xp_context);
    }

    /**
     * Get observer rules maker
     *
     * @return observer_rules_maker
     */
    protected function get_observer_rules_maker() {
        return new \block_xp\local\observer\default_observer_rules_maker();
    }

    /**
     * Get the renderer.
     *
     * @return renderer_base
     */
    protected function get_renderer() {
        global $PAGE;
        return $PAGE->get_renderer('block_xp');
    }

    /**
     * Get the router.
     *
     * @return router
     */
    protected function get_router() {
        return new \block_xp\local\routing\router(
            $this->get('url_resolver')
        );
    }

    /**
     * Get the routes config.
     *
     * @return routes_config
     */
    protected function get_routes_config() {
        return new \block_xp\local\routing\default_routes_config();
    }

    /**
     * Get the settings maker.
     *
     * @return settings_maker
     */
    protected function get_settings_maker() {
        return new \block_xp\local\setting\default_settings_maker();
    }

    /**
     * Get the tasks definition maker.
     *
     * @return tasks_definition_maker
     */
    protected function get_tasks_definition_maker() {
        return new \block_xp\local\task\default_tasks_definition_maker();
    }

    /**
     * Get URL resolver.
     *
     * @return url_resolver
     */
    protected function get_url_resolver() {
        return new \block_xp\local\routing\default_url_resolver(
            $this->get('base_url'),
            $this->get_routes_config()
        );
    }

}
