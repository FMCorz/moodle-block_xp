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

use block_xp\task\post_deactivation_adhoc;

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
     * Get the expected release version.
     *
     * @return string
     */
    final public function get_expected_release() {
        $pluginman = \core_plugin_manager::instance();
        $blockxp = $pluginman->get_plugin_info('block_xp');
        $release = $blockxp ? $blockxp->release : '';
        if (!preg_match('/^([0-9]+)\./', $release, $parts)) {
            return '?';
        }
        return (int) $parts[1];
    }

    /**
     * Whether the plugin's release.
     *
     * @return string
     */
    final public function get_release() {
        $localxp = static::get_plugin_info();
        return $localxp ? $localxp->release : '-';
    }

    /**
     * Whether the plugin is activated.
     *
     * @return bool
     */
    public function is_activated() {
        return false;
    }

    /**
     * Whether the add-on was deactivated.
     *
     * @return bool
     */
    public function is_deactivated(): bool {
        return !$this->is_activated()
            && static::is_passing_activation_checks();
    }

    /**
     * Whether a legacy version is installed.
     *
     * The legacy version do not know about the concept of this class, which
     * can lead to issues. In order to avoid breaking existing installations
     * using currently outdated local_xp, we flag the addon as activated
     * when using a legacy version.
     *
     * @return bool
     * @deprecated Since XP 18
     */
    final public function is_legacy_version_present() {
        $localxp = static::get_plugin_info();
        return !empty($localxp) && $localxp->versiondb < 2022021115;
    }

    /**
     * Whether the plugin is installed and upgraded.
     *
     * @return bool
     */
    public function is_installed_and_upgraded() {
        return static::get_plugin_info()->is_installed_and_upgraded();
    }

    /**
     * Whether the addon is older than.
     *
     * @param int $version The version to test against.
     * @return bool
     */
    public function is_older_than($version) {
        $localxp = static::get_plugin_info();
        return !empty($localxp) && $localxp->versiondb < $version;
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
    final public function is_present() {
        $localxp = static::get_plugin_info();
        return !empty($localxp);
    }

    /**
     * Require the plugin to be activated.
     */
    public function require_activated() {
        if (!$this->is_activated()) {
            throw new \moodle_exception('addonnotactivated', 'block_xp');
        }
    }

    /**
     * Get the plugin info.
     *
     * @return \core\plugininfo\base|null
     */
    public static function get_plugin_info(): ?\core\plugininfo\local {
        $pluginman = \core_plugin_manager::instance();
        return $pluginman->get_plugin_info('local_xp');
    }


    /**
     * Whether the plugin is automatically.
     *
     * @return bool
     */
    public static function is_automatically_activated(): bool {
        global $CFG;
        return empty($CFG->local_xp_disable_automatic_activation);
    }

    /**
     * Check if compatible.
     *
     * @return bool
     */
    public static function is_compatible(): bool {
        $pluginman = \core_plugin_manager::instance();
        $blockxp = $pluginman->get_plugin_info('block_xp');
        $localxp = static::get_plugin_info();
        $blockversion = $blockxp->versiondisk ?? 1000;
        $localversion = $localxp->versiondisk ?? 100;
        $major = function ($version) {
            return floor($version / 100);
        };
        return $major($blockversion) === $major($localversion);
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
     * Whether the plugin is marked to activate.
     *
     * @return bool
     */
    public static function is_marked_to_activate() {
        global $CFG;
        return !empty($CFG->local_xp_activate);
    }

    /**
     * Whether passing basic activation checks.
     *
     * @return bool
     */
    protected static function is_passing_activation_checks(): bool {
        return static::is_container_present()
            && (static::is_automatically_activated() || static::is_marked_to_activate());
    }

    /**
     * Whether passing compatibility checks.
     *
     * @return bool
     */
    protected static function is_passing_compatibility_checks(): bool {
        global $CFG;

        $cache = \cache::make('block_xp', 'metadata');
        $compatibility = $cache->get('addoncompatibilitycheckresult');
        if ($compatibility !== false) {
            return $compatibility === 'true';
        }

        if (!static::is_compatible()) {
            $acceptincompatibility = false;
            $acceptincompatibilitywith = (string) ($CFG->local_xp_accept_incompatibility_with ?? 0);
            if (!empty($acceptincompatibilitywith)) {
                $blockxp = \core_plugin_manager::instance()->get_plugin_info('block_xp');
                $acceptincompatibility = (string) $blockxp->versiondb === $acceptincompatibilitywith;
            }
            if (!$acceptincompatibility) {
                $cache->set('addoncompatibilitycheckresult', 'false');
                post_deactivation_adhoc::schedule();
                return false;
            }
        }

        $cache->set('addoncompatibilitycheckresult', 'true');
        return true;
    }

    /**
     * Whether the plugin should be activated.
     *
     * @return bool
     */
    public static function should_activate(): bool {
        if (!static::is_passing_activation_checks()) {
            return false;
        } else if (!static::is_passing_compatibility_checks()) {
            return false;
        }
        return true;
    }

}
