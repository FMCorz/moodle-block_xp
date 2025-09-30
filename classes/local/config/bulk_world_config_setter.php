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

namespace block_xp\local\config;

use block_xp\di;

/**
 * Bulk world config setter.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class bulk_world_config_setter {

    /** @var config */
    protected $defaults;
    /** @var config */
    protected $target;

    /**
     * Make the default config.
     *
     * @return config
     */
    protected function create_defaults() {
        return new config_stack([
            new default_admin_config(),
            new default_course_world_config(),
        ]);
    }

    /**
     * Create the target config.
     *
     * @return config
     */
    protected function create_target() {
        return new table_setter_config(di::get('db'), 'block_xp_config', 'courseid > 0', []);
    }

    /**
     * Get the config to write to.
     *
     * @return config
     */
    final protected function get_defaults() {
        if (!$this->defaults) {
            $this->defaults = $this->create_defaults();
        }
        return $this->defaults;
    }

    /**
     * Get excluded keys from admin defaults.
     *
     * @return array
     */
    protected function get_excluded_keys_from_admin_defaults() {
        return ['enabled', 'lastlogpurge', 'levelsdata', 'enablecustomlevelbadges', 'defaultfilters',
            'instructions', 'instructions_format'];
    }

    /**
     * Get the config to write to.
     *
     * @return config
     */
    final protected function get_target() {
        if (!$this->target) {
            $this->target = $this->create_target();
        }
        return $this->target;
    }

    /**
     * Determine if a setting is settable.
     *
     * @param string $name
     * @return bool
     */
    final protected function is_settable(string $name): bool {
        return $this->get_defaults()->has($name)
            && $this->get_target()->has($name);
    }

    /**
     * Set values from.
     *
     * @param config $config
     */
    public function set_from(config $config) {
        $target = $this->get_target();
        foreach ($config->get_all() as $name => $value) {
            if ($this->is_settable($name)) {
                $target->set($name, $value);
            }
        }
    }

    /**
     * Set the settings from admin defaults.
     *
     * This method will filter out keys that are not intended to be set from the admin
     * default settings. For example, this excludes appearance and level settings,
     * as well as technical settings.
     *
     * @param config $config
     */
    public function set_from_admin_defaults(config $config) {
        $this->set_from(new filtered_config($config, null, $this->get_excluded_keys_from_admin_defaults()));
    }

}
