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

use block_xp\di;

/**
 * XP generator.
 *
 * @package    block_xp
 * @category   test
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_generator extends \testing_block_generator {

    /**
     * Reset process.
     *
     * @return void
     */
    public function reset() {
    }

    /**
     * Create XP.
     *
     * @param object|array $data The data.
     * @return void
     */
    public function create_config($data = null) {
        $data = (object) ($data ?: []);

        if (!isset($data->name)) {
            throw new \coding_exception('Missing name');
        } else if (!isset($data->value)) {
            throw new \coding_exception('Missing value');
        }

        $context = context::instance_by_id($data->contextid ?? SYSCONTEXTID);
        $world = di::get('context_world_factory')->get_world_from_context($context);
        $world->get_config()->set($data->name, $data->value);
    }

    /**
     * Create a test block instance.
     *
     * @param array|stdClass $record
     * @param array $options
     * @return stdClass The block_instance record
     */
    public function create_instance($record = null, $options = []) {
        $instance = parent::create_instance($record, $options);
        $context = context::instance_by_id($instance->parentcontextid);
        $world = di::get('context_world_factory')->get_world_from_context($context);
        $world->get_config()->set('enabled', true);
    }

    /**
     * Create XP.
     *
     * @param object|array $data The data.
     * @return void
     */
    public function create_xp($data = null) {
        $data = (object) ($data ?: []);

        if (!isset($data->userid)) {
            throw new \coding_exception('Missing user ID');
        }

        $context = context::instance_by_id($data->contextid ?? SYSCONTEXTID);
        $world = di::get('context_world_factory')->get_world_from_context($context);
        $world->get_store()->increase($data->userid, $data->xp);
    }

}
