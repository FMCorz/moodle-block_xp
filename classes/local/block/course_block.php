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
        $adminconfig = \block_xp\di::get('config');
        $config = $world->get_config();

        $badge = $renderer->level_badge($state->get_level());

        $this->content->text = $badge;
        $this->content->text .= $renderer->progress_bar($state);
        if (isset($this->config->description)) {
            $this->content->text .= $renderer->description($this->config->description);
        } else {
            $this->content->text .= $renderer->description($adminconfig->get('blockdescription'));
        }

        // TODO Move this elsewhere...
        $timeago = function(\DateTime $dt) {
            $now = new \DateTime();
            $diff = $now->getTimestamp() - $dt->getTimestamp();

            if ($diff < 15) {
                return 'now';
            } else if ($diff < 45) {
                return sprintf('not a minute ago', $diff);
            } else if ($diff < 60 * 1.7) {
                return 'a minute ago';
            } else if ($diff < HOURSECS * 0.7) {
                return sprintf('%d minutes ago', round($diff / 60));
            } else if ($diff < HOURSECS * 1.7) {
                return 'an hour ago';
            } else if ($diff < DAYSECS * 0.7) {
                return sprintf('%d hours ago', round($diff / HOURSECS));
            } else if ($diff < DAYSECS * 1.7) {
                return 'a day ago';
            } else if ($diff < DAYSECS * 7 * 0.7) {
                return sprintf('%d days ago', round($diff / DAYSECS));
            } else if ($diff < DAYSECS * 7 * 1.7) {
                return 'a week ago';
            } else if ($diff < DAYSECS * 30 * 0.7) {
                return sprintf('%d weeks ago', round($diff / DAYSECS * 7));
            } else if ($diff < DAYSECS * 30 * 1.7) {
                return 'a month ago';
            } else {
                return 'months ago';
            }
        };

        $recentactivity = !empty($this->config->recentactivity) ? $this->config->recentactivity : 0;
        if ($config->get('enablelog') && $recentactivity) {
            $repo = $world->get_user_recent_activity_repository();
            $logs = $repo->get_user_recent_activity($USER->id, $recentactivity);

            // TODO Move this somewhere else.
            if ($logs || $canedit) {
                $dostuff = function($log) use ($timeago) {
                    return implode('', array_map(function($entry) use ($timeago) {
                        $date = $timeago($entry->get_date());
                        $title = userdate($entry->get_date()->getTimestamp());
                        $xp = $entry instanceof \block_xp\local\activity\activity_with_xp ? $entry->get_xp() : '';
                        $desc = s($entry->get_description());
                        return "
                            <tr>
                                <td title='" . s($title) . "'>{$date}</td>
                                <td>{$xp}</td>
                                <td>{$desc}</td>
                            </tr>";
                    }, $log));
                };

                $moreurl = $urlresolver->reverse('log', ['courseid' => $world->get_courseid()]);

                $this->content->text .= html_writer::tag('p', html_writer::tag('strong',
                    get_string('recentrewards', 'block_xp')));
                if ($logs) {
                    $this->content->text .= "<table class='table' style='font-size: .75em;'>{$dostuff($logs)}</table>";
                } else {
                    $this->content->text .= html_writer::tag('p', get_string('norecentrewards', 'block_xp'));
                }

                // TODO Link to the log page.
                // if (count($logs) >= 3) {
                //     $this->content->text .= "<p><a href='$moreurl'>View more...</a></p>";
                // }
            }
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
