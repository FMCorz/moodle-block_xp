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
 * Default settings maker.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\setting;
defined('MOODLE_INTERNAL') || die();

use admin_category;
use admin_externalpage;
use admin_setting_configselect;
use block_xp\local\routing\url_resolver;

/**
 * Default settings maker.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_settings_maker implements settings_maker {

    /** @var url_resolver The URL resolver. */
    protected $urlresolver;

    /**
     * Constructor.
     *
     * @param url_resolver $urlresolver The URL resolver.
     */
    public function __construct(url_resolver $urlresolver) {
        $this->urlresolver = $urlresolver;
    }

    /**
     * Get the settings.
     *
     * @param environment $env The environment for creating the settings.
     * @return part_of_admin_tree|null
     */
    public function get_settings(environment $env) {
        $catname = 'block_xp_category';
        $plugininfo = $env->get_plugininfo();

        // Create a category to hold different pages.
        $settings = new admin_category($catname, $plugininfo->displayname);

        // Block are given a generic settings page.
        // We rename it, add it to the category, and populate it.
        $settingspage = $env->get_settings_page();
        $settingspage->visiblename = get_string('generalsettings', 'admin');
        $settings->add($catname, $settingspage);
        if ($env->is_full_tree()) {
            $this->add_general_settings($env, $settingspage);
        }

        // Add the external rules page.
        $settingspage = new admin_externalpage('block_xp_default_rules',
            get_string('defaultrules', 'block_xp'),
            $this->urlresolver->reverse('admin/rules'));
        $settings->add($catname, $settingspage);

        return $settings;
    }

    /**
     * Add the general settings to the page given.
     *
     * @param environment $env The environment.
     * @param adminsetting_page $page The page.
     */
    protected function add_general_settings(environment $env, $page) {
        // Context in which the block is enabled.
        $page->add(new admin_setting_configselect(
            'block_xp_context',
            get_string('wherearexpused', 'block_xp'),
            get_string('wherearexpused_desc', 'block_xp'),
            CONTEXT_COURSE,
            [
                CONTEXT_COURSE => get_string('incourses', 'block_xp'),
                CONTEXT_SYSTEM => get_string('forthewholesite', 'block_xp')
            ]
        ));
    }

}
