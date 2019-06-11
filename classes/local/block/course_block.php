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

use action_link;
use block_base;
use context;
use context_system;
use html_writer;
use lang_string;
use pix_icon;
use stdClass;
use block_xp\local\course_world;
use block_xp\local\xp\level_with_name;
use block_xp\output\notice;
use block_xp\output\dismissable_notice;

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
        // Enable the capture of events for that course. Note that we are not expecting the permission
        // to 'addinstance' or 'myaddinstance' to be given to standard users!
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
        $db = \block_xp\di::get('db');
        $adminconfig = \block_xp\di::get('config');

        if ($adminconfig->get('context') == CONTEXT_SYSTEM) {
            $context = context::instance_by_id($this->instance->parentcontextid);
            if ($context->contextlevel == CONTEXT_USER) {
                // Someone is removing their block from their dashboard, do nothing.
                return;
            }

            $bifinder = \block_xp\di::get('course_world_block_instances_finder_in_context');
            $instances = $bifinder->get_instances_in_context('xp', context_system::instance());
            if (count($instances) > 1) {
                // We do not want to disable points gain when we find more than one instance.
                return;
            }
        }

        // If we got here that's because we are either removing the block from a course,
        // or from the front page, or from the default dashboard. It's not ideal but
        // in that case we disable points gain.
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
        global $PAGE, $USER;

        if (isset($this->content)) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        $world = $this->get_world($this->page->course->id);
        $context = $world->get_context();
        $canview = $world->get_access_permissions()->can_access();
        $canedit = $world->get_access_permissions()->can_manage();

        // Hide the block to non-logged in users, guests and those who cannot view the block.
        if (!$USER->id || isguestuser() || !$canview) {
            return $this->content;
        }

        $renderer = \block_xp\di::get('renderer');
        $urlresolver = \block_xp\di::get('url_resolver');
        $state = $world->get_store()->get_state($USER->id);
        $adminconfig = \block_xp\di::get('config');
        $indicator = \block_xp\di::get('user_notice_indicator');
        $courseid = $world->get_courseid();
        $config = $world->get_config();

        // Recent activity.
        $activity = [];
        $forcerecentactivity = false;
        $moreurl = null; // TODO Add URL for students to see, and option to control it.
        $recentactivity = isset($this->config->recentactivity) ? $this->config->recentactivity : // @codingStandardsIgnoreLine.
            $adminconfig->get('blockrecentactivity');
        if ($recentactivity) {
            $repo = $world->get_user_recent_activity_repository();
            $activity = $repo->get_user_recent_activity($USER->id, $recentactivity);

            // Users who can manage should see this when it's enabled, even without activity to show.
            $forcerecentactivity = $canedit;
        }

        // Navigation.
        $actions = $this->get_block_navigation($world);

        // Introduction.
        $introduction = isset($this->config->description) ?
            format_string($this->config->description, true, ['context' => $context]) : // @codingStandardsIgnoreLine.
            format_string($adminconfig->get('blockdescription'), true, ['context' => $context]);
        $introname = 'block_intro_' . $courseid;
        if (empty($introduction)) {
            // The intro is empty, no need for further checks then...
            $introduction = null;
        } else if ($canedit) {
            // Always show the notification to teachers.
            $introduction = $introduction ? new notice($introduction, notice::INFO) : null;
        } else if (!$indicator->user_has_flag($USER->id, $introname)) {
            // Allow students to dismiss the message.
            $introduction = $introduction ? new dismissable_notice($introduction, $introname, notice::INFO) : null;
        } else {
            $introduction = null;
        }

        // Widget.
        $widget = new \block_xp\output\xp_widget(
            $state,
            $activity,
            $introduction,
            $actions,
            $moreurl
        );
        $widget->set_force_recent_activity($forcerecentactivity);

        // When XP gain is disabled, let the teacher now.
        if (!$config->get('enabled') && $canedit) {
            $widget->add_manager_notice(new lang_string('xpgaindisabled', 'block_xp'));
        }

        $this->content->text = $renderer->render($widget);

        // We should be congratulating the user because they leveled up!
        // Also resets the flag. We could potentially do that from JS so that if the user does not
        // stay on the page long enough they'd be notified the next time they access the course page,
        // but that's probably an overkill for now.
        $service = $world->get_level_up_notification_service();
        if ($service->should_be_notified($USER->id)) {
            $service->mark_as_notified($USER->id);

            $level = $state->get_level();
            $name = $level instanceof level_with_name ? $level->get_name() : null;
            $args = array(
                'badge' => $renderer->level_badge($level),
                'level' => $level->get_level(),
                'name' => $name,
            );

            $PAGE->requires->yui_module('moodle-block_xp-notification', 'Y.M.block_xp.Notification.init', array($args));
            $PAGE->requires->strings_for_js(
                array(
                    'coolthanks',
                    'congratulationsyouleveledup',
                    'youreachedlevela',
                    'youreachedlevel',
                    'levelx'
                ),
                'block_xp'
            );
        }

        return $this->content;
    }

    /**
     * Get the block navigation.
     *
     * @param course_world $world The world.
     * @return action_link[]
     */
    protected function get_block_navigation(course_world $world) {
        $canedit = $world->get_access_permissions()->can_manage();
        $courseid = $world->get_courseid();
        $urlresolver = \block_xp\di::get('url_resolver');
        $config = $world->get_config();
        $actions = [];

        if ($config->get('enableinfos')) {
            $actions[] = new action_link(
                $urlresolver->reverse('infos', ['courseid' => $courseid]),
                get_string('navinfos', 'block_xp'), null, null,
                new pix_icon('i/info', '', 'block_xp')
            );
        }
        if ($config->get('enableladder')) {
            $actions[] = new action_link(
                $urlresolver->reverse('ladder', ['courseid' => $courseid]),
                get_string('navladder', 'block_xp'), null, null,
                new pix_icon('i/ladder', '', 'block_xp')
            );
        }
        if ($canedit) {
            $actions[] = new action_link(
                $urlresolver->reverse('report', ['courseid' => $courseid]),
                get_string('navreport', 'block_xp'), null, null,
                new pix_icon('i/report', '', 'block_xp')
            );
            $actions[] = new action_link(
                $urlresolver->reverse('config', ['courseid' => $courseid]),
                get_string('navsettings', 'block_xp'), null, null,
                new pix_icon('i/settings', '', 'block_xp')
            );
        }

        return $actions;
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
        $world = $this->get_world($this->page->course->id);
        $context = $world->get_context();
        if (!empty($this->config->title)) {
            $this->title = format_string($this->config->title, true, ['context' => $context]);
        } else {
            $this->title = format_string(\block_xp\di::get('config')->get('blocktitle'), true, ['context' => $context]);
        }
    }

}
