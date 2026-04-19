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

namespace block_xp\local\reason;

/**
 * Resolver.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_resolver implements resolver {

    /** @var (string|false)[] A cache of class names. */
    protected $classnames = [];

    /**
     * Get reason class by name.
     *
     * @param string $name The reason name.
     * @return string|null
     */
    public function get_class_name($name): ?string {
        if (!isset($this->classnames[$name])) {
            $candidates = $this->get_class_name_candidates($name);
            $classname = null;
            foreach ($candidates as $candidate) {
                if ($this->is_valid_class($candidate)) {
                    $classname = $candidate;
                    break;
                }
            }
            $this->classnames[$name] = $classname ?? false;
        }
        return $this->classnames[$name] ?: null;
    }

    /**
     * Get the reason name.
     *
     * @param reason $reason A reason instance.
     * @return string
     */
    public function get_name($reason): string {
        $name = get_class($reason);

        // Our classes are stripped of namespace and suffix.
        if (strpos($name, 'block_xp\\local\\reason\\') === 0) {
            $name = str_replace('block_xp\\local\\reason\\', '', $name);
            if (substr($name, -7) === '_reason') {
                $name = substr($name, 0, -7);
            }
        }

        return $name;
    }

    /**
     * Get class name candidates.
     *
     * @param string $name
     */
    protected function get_class_name_candidates(string $name) {
        if (strpos($name, '\\') !== false) {
            return [$name];
        }

        if (strrpos($name, '_reason') === strlen($name) - 7) {
            $name = substr($name, 0, -7);
        }

        return [
            'block_xp\\local\\reason\\' . $name . '_reason',
            'block_xp\\local\\reason\\' . $name,
        ];
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

        if (!$reflector->isSubclassOf('block_xp\local\reason\reason') || !$reflector->isInstantiable()) {
            return false;
        }

        return true;
    }

}
