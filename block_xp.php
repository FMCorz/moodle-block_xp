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
 * Block XP.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block XP class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp extends block_base {

    /**
     * Applicable formats.
     *
     * @return array
     */
    public function applicable_formats() {
        global $CFG;
        if (isset($CFG->block_xp_context) && $CFG->block_xp_context == CONTEXT_SYSTEM) {
            return array('site' => true, 'course' => true, 'my' => true);
        }
        return array('course' => true);
    }

    /**
     * Cron.
     *
     * @return void
     */
    public function cron() {
        global $DB;
        $courseids = $DB->get_fieldset_sql('SELECT DISTINCT(courseid) FROM {block_xp}', array());
        foreach ($courseids as $courseid) {
            $manager = block_xp_manager::get($courseid);
            $manager->purge_log();
        }
        return true;
    }

    /**
     * The plugin has a settings.php file.
     *
     * @return boolean True.
     */
    public function has_config() {
        return true;
    }

    /**
     * Init.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_xp');
    }

    /**
     * Callback when a block is created.
     *
     * @return bool
     */
    public function instance_create() {
        // Enable the capture of events for that course.
        $manager = block_xp_manager::get($this->page->course->id);
        $manager->update_config((object) array('enabled' => true));
        return true;
    }

    /**
     * Callback when a block is deleted.
     *
     * @return bool
     */
    public function instance_delete() {
        // It's bad, but here we assume there is only one block per course.
        $manager = block_xp_manager::get($this->page->course->id);
        $manager->update_config((object) array('enabled' => false));
        return true;
    }

    /**
     * Get content.
     *
     * @return stdClass
     */
    public function get_content() {
        global $DB, $PAGE, $USER;

        if (isset($this->content)) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        $manager = block_xp_manager::get($this->page->course->id);

        $canview = $manager->can_view();
        $canedit = $manager->can_manage();

        // Hide the block to non-logged in users, guests and those who cannot view the block.
        if (!$USER->id || isguestuser() || !$canview) {
            return $this->content;
        }

        $progress = $manager->get_progress_for_user($USER->id);
        $renderer = $this->page->get_renderer('block_xp');
        $currentlevelmethod = $manager->get_config('enablecustomlevelbadges') ? 'custom_current_level' : 'current_level';

        $this->content->text = $renderer->$currentlevelmethod($progress);
        $this->content->text .= $renderer->progress_bar($progress);
        if (isset($this->config->description)) {
            $this->content->text .= $renderer->description($this->config->description);
        } else {
            $this->content->text .= $renderer->description(get_string('participatetolevelup', 'block_xp'));
        }

        $this->content->footer .= $renderer->student_links($manager->get_courseid(),
            $manager->can_view_ladder_page(), $manager->can_view_infos_page());

        if ($canedit) {
            $this->content->footer .= $renderer->admin_links($manager->get_courseid());
            if (!$manager->get_config('enabled')) {
                $this->content->footer .= html_writer::tag('p',
                    html_writer::tag('small', get_string('xpgaindisabled', 'block_xp')), array('class' => 'alert alert-warning'));
            }
        }

        // We should be congratulating the user because they leveled up!
        // Also resets the flag. We could potentially do that from JS so that if the user does not
        // stay on the page long enough they'd be notified the next time they access the course page,
        // but that's probably an overkill for now.
        if ($manager->has_levelled_up($USER->id)) {
            $args = array(
                'badge' => $renderer->$currentlevelmethod($progress),
                'headline' => get_string('youreachedlevela', 'block_xp', $progress->level),
                'level' => $progress->level,
            );

            $PAGE->requires->yui_module('moodle-block_xp-notification', 'Y.M.block_xp.Notification.init', array($args));
            $PAGE->requires->strings_for_js(
                array(
                    'coolthanks',
                    'congratulationsyouleveledup',
                ),
                'block_xp'
            );
        }

        return $this->content;
    }

    /**
     * Specialization.
     *
     * Happens right after the initialisation is complete.
     *
     * @return void
     */
    public function specialization() {
        parent::specialization();
        if (!empty($this->config->title)) {
            $this->title = $this->config->title;
        }
    }

}
