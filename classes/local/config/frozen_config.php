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
 * Frozen config.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\config;
defined('MOODLE_INTERNAL') || die();

/**
 * Frozen config.
 *
 * Wrap a config object around this one to prevent any unexpected writes.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class frozen_config implements config {

    /** @var config The config. */
    private $config;

    /**
     * Constructor.
     *
     * @param config $config The config object.
     */
    public function __construct(config $config) {
        $this->config = $config;
    }

    /**
     * Get a value.
     *
     * @param string $name The name.
     * @return mixed
     */
    final public function get($name) {
        return $this->config->get($name);
    }

    /**
     * Get all config.
     *
     * @return array
     */
    final public function get_all() {
        return $this->config->get_all();
    }

    /**
     * Whether we have that config.
     *
     * @param string $name The config name.
     * @return bool
     */
    final public function has($name) {
        return $this->config->has($name);
    }

    /**
     * Set a value.
     *
     * @param string $name Name of the config.
     * @param mixed $value The value.
     */
    final public function set($name, $value) {
        // Do nothing, it's frozen.
    }

    /**
     * Set many.
     *
     * @param array $values Keys are config names, and values are values.
     */
    final public function set_many(array $values) {
        // Do nothing, it's still frozen.
    }

}
