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
 * @copyright  2017 Frédéric Massart - FMCorz.net
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
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_container implements container {

    /** @var array The objects supported by this container. */
    protected static $supports = [
        'base_url' => true,
        'filter_manager_factory' => true,
        'manager_factory' => true,
        'observer_rules_maker' => true,
        'renderer' => true,
        'router' => true,
        'routes_config' => true,
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

    protected function get_base_url() {
        return new moodle_url('/blocks/xp/index.php');
    }

    protected function get_factory() {
        if (!$this->factory) {
            $factory = new \block_xp\local\factory\factory();
        }
        return $factory;
    }

    protected function get_filter_manager_factory() {
        return $this->get_factory();
    }

    protected function get_manager_factory() {
        return $this->get_factory();
    }

    protected function get_observer_rules_maker() {
        return new \block_xp\local\observer\default_observer_rules_maker();
    }

    protected function get_renderer() {
        global $PAGE;
        return $PAGE->get_renderer('block_xp');
    }

    protected function get_router() {
        return new \block_xp\local\routing\router(
            $this->get('url_resolver')
        );
    }

    protected function get_routes_config() {
        return new \block_xp\local\routing\default_routes_config();
    }

    protected function get_url_resolver() {
        return new \block_xp\local\routing\default_url_resolver(
            $this->get('base_url'),
            $this->get('routes_config')
        );
    }

}
