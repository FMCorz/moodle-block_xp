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
     * Administration links.
     *
     * @param int $courseid The course ID.
     * @return string HTML produced.
     */
    public function admin_links($courseid) {
        return html_writer::tag('p',
            html_writer::link(
                new moodle_url('/blocks/xp/report.php', array('courseid' => $courseid)),
                get_string('coursereport', 'block_xp'))
            . ' - '
            . html_writer::link(
                new moodle_url('/blocks/xp/rules.php', array('courseid' => $courseid)),
                get_string('coursesettings', 'block_xp'))
            , array('class' => 'admin-links')
        );
    }

    /**
     * Returns the current level rendered.
     *
     * @param renderable $progress The renderable object.
     * @return string HTML produced.
     */
    public function current_level(renderable $progress) {
        $html = '';
        $html .= html_writer::tag('div', $progress->level, array('class' => 'current-level level-' . $progress->level));
        return $html;
    }

    /**
     * The description to display in the field.
     *
     * @param string $string The text to display.
     * @return string HTML producted.
     */
    public function description($string) {
        if (empty($string)) {
            return '';
        }
        return html_writer::tag('p', $string, array('class' => 'description'));
    }

    /**
     * Returns the links for the students.
     *
     * @param int $courseid The course ID.
     * @return string HTML produced.
     */
    public function student_links($courseid) {
        return html_writer::tag('p',
            html_writer::link(
                new moodle_url('/blocks/xp/ladder.php', array('courseid' => $courseid)),
                get_string('viewtheladder', 'block_xp')
            ), array('class' => 'student-links')
        );
    }

    /**
     * Returns the progress bar rendered.
     *
     * @param renderable $progress The renderable object.
     * @return string HTML produced.
     */
    public function progress_bar(renderable $progress) {
        $html = '';
        $html .= html_writer::start_tag('div', array('class' => 'block_xp-level-progress'));
        $html .= html_writer::tag('div', '', array('style' => 'width: ' . $progress->percentage . '%;', 'class' => 'bar'));
        $html .= html_writer::tag('div', $progress->xpinlevel . '/' . $progress->xpforlevel, array('class' => 'txt'));
        $html .= html_writer::end_tag('div');
        return $html;
    }

}
