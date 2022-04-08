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
 * Addon.
 *
 * @package    block_xp
 * @copyright  2022 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\plugin;

defined('MOODLE_INTERNAL') || die();

/**
 * Addon class.
 *
 * You might be looking here wondering whether this is, in fact, the addon. No, it isn't.
 *
 * @package    block_xp
 * @copyright  2022 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class addon {

    /**
     * Get the plugin info.
     *
     * @return \core\plugininfo\base|null
     */
    public function get_plugin_info() {
        $pluginman = \core_plugin_manager::instance();
        return $pluginman->get_plugin_info('local_xp');
    }

    /**
     * Whether the plugin's release.
     *
     * @return string
     */
    public function get_release() {
        $localxp = $this->get_plugin_info();
        return $localxp ? $localxp->release : '-';
    }

    /**
     * Whether the plugin is activated.
     *
     * @return bool
     */
    public function is_activated() {
        return true;
    }

    /**
     * Whether the plugin is installed and upgraded.
     *
     * @return bool
     */
    public function is_installed_and_upgraded() {
        return false;
    }

    /**
     * Whether the plugin is out of sync.
     *
     * @return bool
     */
    public function is_out_of_sync() {
        return false;
    }

    /**
     * Whether the plugin is present.
     *
     * @return bool
     */
    public function is_present() {
        $localxp = $this->get_plugin_info();
        return !empty($localxp);
    }

    /**
     * Whether the plugin is automatically.
     *
     * @return bool
     */
    public static function is_automatically_activated() {
        global $CFG;
        return empty($CFG->local_xp_disable_automatic_activation);
    }

    /**
     * Whether the plugin is marked to activate.
     *
     * @return bool
     */
    public static function is_marked_to_activate() {
        global $CFG;
        return !empty($CFG->local_xp_activate);
    }

    /**
     * Simplest check to identify whether the plugin is present.
     *
     * @return bool
     */
    public static function is_container_present() {
        return class_exists('local_xp\local\container');
    }

    /**
     * Whether the plugin should be activated.
     *
     * @return bool
     */
    public static function should_activate() {
        return static::is_container_present()
            && (static::is_automatically_activated() || static::is_marked_to_activate());
    }
}