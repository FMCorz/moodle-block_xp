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
 * Resolver.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\ruletype;

use core_component;

/**
 * Resolver.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_resolver implements resolver {

    /** @var (ruletype|false)[] The types. */
    protected $types = [];

    /**
     * Get type by name.
     *
     * @param string $name The type name.
     * @return ruletype|null
     */
    public function get_type($name): ?ruletype {
        if (!isset($this->types[$name])) {
            $class = "block_xp\\local\\ruletype\\$name";
            $instance = class_exists($class) ? new $class() : false;
            $this->types[$name] = $instance && $instance instanceof ruletype ? $instance : false;
        }
        return $this->types[$name] ?: null;
    }

    /**
     * Get type name.
     *
     * @param ruletype $type The type.
     * @return string
     */
    public function get_type_name(ruletype $type): string {
        return str_replace("block_xp\\local\\ruletype\\", '', get_class($type));
    }

    /**
     * Get the types.
     *
     * @return ruletype[]
     */
    public function get_types(): array {
        return array_values(array_filter(array_map(function ($name) {
            $parts = explode('\\', $name);
            return $this->get_type(end($parts));
        }, $this->get_class_name_list())));
    }

    /**
     * Get class name list.
     *
     * @return string[]
     */
    protected function get_class_name_list() {
        $classes = array_keys(core_component::get_component_classes_in_namespace('block_xp', 'local\\ruletype'));
        return array_filter($classes, [$this, 'is_valid_class']);
    }

    /**
     * Whether it is a valid class.
     *
     * @param string $class
     * @return bool
     */
    protected function is_valid_class(string $class) {
        try {
            $reflector = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            return false;
        }

        if (!$reflector->isSubclassOf('block_xp\local\ruletype\ruletype') || !$reflector->isInstantiable()) {
            return false;
        }

        return true;
    }

}
