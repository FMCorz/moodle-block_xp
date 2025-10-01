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

namespace block_xp\local\controller;

use block_xp\di;
use block_xp\local\routing\url;

/**
 * Controller.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_settings_controller extends admin_route_controller {

    /** @var string The section name. */
    protected $sectionname = 'block_xp_default_settingspage';

    protected function define_optional_params() {
        return [
            ['action', false, PARAM_ALPHANUMEXT, false],
            ['confirm', false, PARAM_BOOL, false],
        ];
    }

    /**
     * Get the admin settings page URL.
     *
     * @return url
     */
    protected function get_admin_settings_page_url() {
        return new url('/admin/settings.php', ['section' => 'block_xp_default_settings']);
    }

    protected function pre_content() {
        $action = $this->get_param('action');

        // Reset all courses to defaults.
        if ($this->get_param('action') === 'reset') {
            if ($this->get_param('confirm') && confirm_sesskey()) {
                di::get('bulk_world_config_setter')->set_from_admin_defaults(di::get('config'));
                $this->redirect($this->get_admin_settings_page_url(), get_string('allcoursesreset', 'block_xp'));
            }
        }

        // For now we only suppose the reset option.
        if ($action !== 'reset') {
            $this->redirect($this->get_admin_settings_page_url());
        }
    }

    /**
     * Echo the content.
     *
     * @return void
     */
    protected function content() {
        $output = $this->get_renderer();
        echo $output->heading(get_string('defaultsettings', 'block_xp'));

        if ($this->get_param('action') === 'reset') {
            echo $output->confirm_reset(
                get_string('resetallcoursestodefaults', 'block_xp'),
                get_string('reallyresetallcoursessettingstodefaults', 'block_xp'),
                new url($this->pageurl->get_compatible_url(), ['action' => 'reset', 'confirm' => 1, 'sesskey' => sesskey()]),
                new url($this->get_admin_settings_page_url()->get_compatible_url())
            );
            return;
        }
    }

}
