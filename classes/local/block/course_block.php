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
 * Block.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\block;
defined('MOODLE_INTERNAL') || die();

use block_base;
use html_writer;
use stdClass;

/**
 * Block class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_block extends block_base {

    /**
     * Applicable formats.
     *
     * @return array
     */
    public function applicable_formats() {
        $mode = \block_xp\di::get('config')->get('context');
        if ($mode == CONTEXT_SYSTEM) {
            return array('site' => true, 'course' => true, 'my' => true);
        }
        return array('course' => true);
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
        // At this stage, this is not the title, it is the name displayed in the block
        // selector. In self::specialization() we will change that property to what it
        // should be as the title of the block.
        $this->title = get_string('pluginname', 'block_xp');
    }

    /**
     * Callback when a block is created.
     *
     * @return bool
     */
    public function instance_create() {
        // Enable the capture of events for that course.
        $world = $this->get_world($this->page->course->id);
        $world->get_config()->set('enabled', true);
        return true;
    }

    /**
     * Callback when a block is deleted.
     *
     * @return bool
     */
    public function instance_delete() {
        // It's bad, but here we assume there is only one block per course.
        $world = $this->get_world($this->page->course->id);
        $world->get_config()->set('enabled', false);
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

        $world = $this->get_world($this->page->course->id);
        $canview = $world->get_access_permissions()->can_access();
        $canedit = $world->get_access_permissions()->can_manage();

        // Hide the block to non-logged in users, guests and those who cannot view the block.
        if (!$USER->id || isguestuser() || !$canview) {
            return $this->content;
        }

        $renderer = \block_xp\di::get('renderer');
        $urlresolver = \block_xp\di::get('url_resolver');
        $state = $world->get_store()->get_state($USER->id);

        $badge = $renderer->level_badge($state->get_level());

        $this->content->text = $badge;
        $this->content->text .= $renderer->progress_bar($state);
        if (isset($this->config->description)) {
            $this->content->text .= $renderer->description($this->config->description);
        } else {
            $this->content->text .= $renderer->description(\block_xp\di::get('config')->get('blockdescription'));
        }

        $this->content->footer .= $renderer->student_links($world, $urlresolver);

        if ($canedit) {
            $this->content->footer .= $renderer->admin_links($world, $urlresolver);
            if (!$world->get_config()->get('enabled')) {
                $this->content->footer .= html_writer::tag('p',
                    html_writer::tag('small', get_string('xpgaindisabled', 'block_xp')), array('class' => 'alert alert-warning'));
            }
        }

        // We should be congratulating the user because they leveled up!
        // Also resets the flag. We could potentially do that from JS so that if the user does not
        // stay on the page long enough they'd be notified the next time they access the course page,
        // but that's probably an overkill for now.
        $service = $world->get_level_up_notification_service();
        if ($service->should_be_notified($USER->id)) {
            $service->mark_as_notified($USER->id);

            $level = $state->get_level();
            $args = array(
                'badge' => $renderer->level_badge($level),
                'headline' => get_string('youreachedlevela', 'block_xp', $level->get_level()),
                'level' => $level->get_level(),
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
     * Get the world.
     *
     * @param int $courseid The course ID.
     * @return \block_xp\local\course_world The world.
     */
    protected function get_world($courseid) {
        return \block_xp\di::get('course_world_factory')->get_world($courseid);
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
            $this->title = \block_xp\di::get('config')->get('blocktitle');
        }
    }

}
