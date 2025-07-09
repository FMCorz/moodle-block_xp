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

namespace block_xp\local\check;

use block_xp\di;
use block_xp\local\plugin\addon;
use core\check\result;
use moodle_url;

/**
 * Addon compatibility.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class addon_compatibility extends \core\check\check {

    /**
     * Get the short check name.
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('checkaddoncompatibility', 'block_xp');
    }

    /**
     * A link.
     *
     * @return \action_link|null
     */
    public function get_action_link(): ?\action_link {
        [$code, $message, $url] = $this->compute_result();
        if (empty($url)) {
            return null;
        }
        return new \action_link($url, get_string('documentation', 'block_xp'));
    }

    /**
     * Return the result.
     *
     * @return result object
     */
    public function get_result(): result {
        [$code, $message, $url] = $this->compute_result();
        return new result($code, $message, $url);
    }

    /**
     * Return the result.
     *
     * @return array
     */
    protected function compute_result() {
        $noissues = [result::OK, get_string('noissuesidentified', 'block_xp'), ''];
        if (!addon::is_container_present()) {
            return $noissues;
        }

        $addon = di::get('addon');
        if ($addon->is_deactivated()) {
            return [result::ERROR, strip_tags(markdown_to_html(get_string('erroraddondeactivated', 'block_xp', [
                'docsurl' => (new moodle_url('https://docs.levelup.plus/xp/docs/addon-deactivated'))->out(false),
            ])), '<a>'), new moodle_url('https://docs.levelup.plus/xp/docs/addon-deactivated')];
        }

        return $noissues;
    }

}
