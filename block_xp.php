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
     * Init.
     *
     * @return void
     */
    public function init() {
        $this->title = '';
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

        $context = $this->page->context;
        $canearnxp = has_capability('block/xp:earnxp', $context);
        $canedit = has_capability('block/xp:addinstance', $context);

        // Hide the block to non-logged in users, guests and those who cannot earn XP or edit the block.
        if (!$USER->id || isguestuser() || (!$canearnxp && !$canedit)) {
            return $this->content;
        }

        $manager = block_xp_manager::get($this->page->course->id);
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

        $this->content->footer .= $renderer->student_links($this->page->course->id,
            $manager->get_config('enableladder'), $manager->get_config('enableinfos'));

        if (has_capability('block/xp:addinstance', $context)) {
            $this->content->footer .= $renderer->admin_links($this->page->course->id);
            if (!$manager->get_config('enabled')) {
                $this->content->footer .= html_writer::tag('p',
                    html_writer::tag('small', get_string('xpgaindisabled', 'block_xp')), array('class' => 'alert alert-warning'));
            }
        }

        // We should be congratulating the user because they leveled up!
        if (get_user_preferences($manager::USERPREF_NOTIFY, false)) {
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

            // Reset the value of the user preference. We could potentially do that from JS so that if
            // the user does not stay on the page long enough they'd be notified the next time they access
            // the course page, but that's probably an overkill for now.
            unset_user_preference($manager::USERPREF_NOTIFY);
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
        } else {
            $this->title = get_string('levelup', 'block_xp');
        }
    }

}
