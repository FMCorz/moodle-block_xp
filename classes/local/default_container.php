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
        'ajax_base_url' => true,
        'ajax_router' => true,
        'ajax_url_resolver' => true,
        'base_url' => true,
        'block_class' => true,
        'block_edit_form_class' => true,
        'block_instance_finder' => true,
        'collection_strategy' => true,
        'config' => true,
        'course_world_factory' => true,
        'db' => true,
        'file_server' => true,
        'observer_rules_maker' => true,
        'renderer' => true,
        'router' => true,
        'settings_maker' => true,
        'tasks_definition_maker' => true,
        'url_resolver' => true,
        'user_notice_indicator' => true,
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
            if (!$this->has($id)) {
                throw new coding_exception('Unknown thing to create: ' . $id);
            }
            $method = 'get_' . $id;
            $this->instances[$id] = $this->{$method}();
        }
        return $this->instances[$id];
    }

    /**
     * Get the router.
     *
     * @return router
     */
    protected function get_ajax_base_url() {
        return new moodle_url('/blocks/xp/ajax.php');
    }

    /**
     * Get the router.
     *
     * @return router
     */
    protected function get_ajax_router() {
        return new \block_xp\local\routing\router(
            $this->get('ajax_url_resolver')
        );
    }

    /**
     * Get the routes config.
     *
     * @return routes_config
     */
    protected function get_ajax_routes_config() {
        return new \block_xp\local\routing\ajax_routes_config();
    }

    /**
     * Get URL resolver.
     *
     * @return url_resolver
     */
    protected function get_ajax_url_resolver() {
        return new \block_xp\local\routing\default_url_resolver(
            $this->get('ajax_base_url'),
            $this->get_ajax_routes_config()
        );
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
     * Get the block instance finder.
     *
     * @return instance_finder
     */
    protected function get_block_instance_finder() {
        return new \block_xp\local\block\default_instance_finder($this->get('db'));
    }

    /**
     * Collection strategy.
     *
     * @return collection_strategy
     */
    protected function get_collection_strategy() {
        return new \block_xp\local\strategy\global_collection_strategy(
            $this->get_factory(),
            $this->get('config')->get('context')
        );
    }

    /**
     * Get the global config object.
     *
     * @return config
     */
    protected function get_config() {
        return new \block_xp\local\config\admin_config(
            new \block_xp\local\config\default_admin_config()
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
        if (!$this->factory) {
            $factory = new \block_xp\local\factory\factory(
                $this->get('config'),
                $this->get('db')
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
        return new \block_xp\local\file\file_server(get_file_storage(), $this->get('config')->get('context'));
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
        return new \block_xp\local\setting\default_settings_maker(
            new \block_xp\local\config\default_admin_config(),
            $this->get('url_resolver')
        );
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

    /**
     * Get the user notice indicator.
     *
     * @return user_indicator
     */
    protected function get_user_notice_indicator() {
        return new \block_xp\local\indicator\user_notice_indicator($this->get('db'));
    }

    /**
     * Whether this container can return an entry for the given identifier.
     *
     * @param string $id The thing's name.
     * @return bool
     */
    public function has($id) {
        return array_key_exists($id, static::$supports);
    }

}
