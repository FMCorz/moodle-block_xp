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
                get_string('navreport', 'block_xp'))
            . ' - '
            . html_writer::link(
                new moodle_url('/blocks/xp/rules.php', array('courseid' => $courseid)),
                get_string('navsettings', 'block_xp'))
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
     * Outputs the navigation.
     *
     * @param block_xp_manager $manager The manager.
     * @param string $page The page we are on.
     * @return string The navigation.
     */
    public function navigation($manager, $page) {
        $tabs = array();
        $courseid = $manager->get_courseid();
        $context = context_course::instance($courseid);
        $canedit = has_capability('block/xp:addinstance', $context);

        if ($manager->get_config('enableinfos') || $canedit) {
            $tabs[] = new tabobject(
                'infos',
                new moodle_url('/blocks/xp/infos.php', array('courseid' => $courseid)),
                get_string('navinfos', 'block_xp')
            );
        }
        if ($manager->get_config('enableladder') || $canedit) {
            $tabs[] = new tabobject(
                'ladder',
                new moodle_url('/blocks/xp/ladder.php', array('courseid' => $courseid)),
                get_string('navladder', 'block_xp')
            );
        }

        if ($canedit) {
            $tabs[] = new tabobject(
                'report',
                new moodle_url('/blocks/xp/report.php', array('courseid' => $courseid)),
                get_string('navreport', 'block_xp')
            );
            $tabs[] = new tabobject(
                'log',
                new moodle_url('/blocks/xp/log.php', array('courseid' => $courseid)),
                get_string('navlog', 'block_xp')
            );
            $tabs[] = new tabobject(
                'levels',
                new moodle_url('/blocks/xp/levels.php', array('courseid' => $courseid)),
                get_string('navlevels', 'block_xp')
            );
            $tabs[] = new tabobject(
                'rules',
                new moodle_url('/blocks/xp/rules.php', array('courseid' => $courseid)),
                get_string('navsettings', 'block_xp')
            );
        }

        // If there is only one page, then that is the page we are on.
        if (count($tabs) == 1) {
            return '';
        }

        return $this->tabtree($tabs, $page);
    }

    /**
     * Returns the links for the students.
     *
     * @param int $courseid The course ID.
     * @return string HTML produced.
     */
    public function student_links($courseid, $enableladder, $enableinfos) {
        $html = '';
        $links = array();

        if ($enableinfos) {
            $links[] = html_writer::link(
                new moodle_url('/blocks/xp/infos.php', array('courseid' => $courseid)),
                get_string('infos', 'block_xp')
            );
        }
        if ($enableladder) {
            $links[] = html_writer::link(
                new moodle_url('/blocks/xp/ladder.php', array('courseid' => $courseid)),
                get_string('viewtheladder', 'block_xp')
            );
        }

        if (!empty($links)) {
            $html = html_writer::tag('p', implode(' - ', $links), array('class' => 'student-links'));
        }

        return $html;
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
