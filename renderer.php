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
 * Block XP renderer.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block XP renderer class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_renderer extends plugin_renderer_base {

    /**
     * Returns the current level rendered.
     *
     * @param renderable $progress The renderable object.
     * @return string HTML produced.
     */
    public function current_level(renderable $progress) {
        $html = '';
        $html .= html_writer::tag('div', $progress->level, array('class' => 'current-level'));
        return $html;
    }

    /**
     * The description to display in the field.
     *
     * @param string $string The text to display.
     * @return string HTML producted.
     */
    public function description($string) {
        return html_writer::tag('div', $string, array('class' => 'description'));
    }

    /**
     * Returns the progress bar rendered.
     *
     * @param renderable $progress The renderable object.
     * @return string HTML produced.
     */
    public function progress_bar(renderable $progress) {
        $html = '';
        $html .= html_writer::start_tag('div', array('class' => 'level-progress'));
        $html .= html_writer::tag('div', '', array('style' => 'width: ' . $progress->percentage . '%;', 'class' => 'bar'));
        $html .= html_writer::tag('div', $progress->xpinlevel . '/' . $progress->xpforlevel, array('class' => 'txt'));
        $html .= html_writer::end_tag('div');
        return $html;
    }

}
