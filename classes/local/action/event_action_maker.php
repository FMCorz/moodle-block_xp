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

/**
 * Maker.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\action;

/**
 * Maker.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class event_action_maker implements maker_from_event {

    /**
     * Make actions from event.
     *
     * @param \core\event\base $event The event.
     * @return action[]
     */
    public function make_from_event(\core\event\base $event): iterable {
        $actions = [];
        $context = $event->get_context();

        // We cannot trust that the event gives us a context, and we do not want restored ones.
        if (!$context || $event->is_restored()) {
            return $actions;
        }

        if ($event instanceof \core\event\course_viewed) {
            $actions[] = new static_action('course_viewed', $context, $event->userid, $event->courseid);
        } else if ($event instanceof \core\event\course_module_viewed) {
            $actions[] = new static_action('activity_viewed', $context, $event->userid);
        } else if ($event instanceof \mod_assign\event\assessable_submitted) {
            $actions = array_merge($actions, $this->make_from_assign_assessable_submitted($event));
        } else if ($event instanceof \mod_assign\event\feedback_viewed) {
            if ($event->userid == $event->relateduserid) {
                $actions[] = new static_action('assign_feedback_viewed', $context, $event->userid, $event->objectid);
            }
        } else if ($event instanceof \mod_book\event\chapter_viewed) {
            $actions[] = new static_action('book_chapter_viewed', $context, $event->userid, $event->objectid);
        } else if ($event instanceof \mod_data\event\record_created) {
            $actions[] = new static_action('database_entry_created', $context, $event->userid, $event->objectid);
        } else if ($event instanceof \mod_feedback\event\response_submitted) {
            $actions[] = new static_action('feedback_submitted', $context, $event->userid, $event->objectid);
        } else if ($event instanceof \mod_forum\event\discussion_created) {
            $actions[] = new discussion_created($context, $event->userid, $event->objectid);
        } else if ($event instanceof \mod_forum\event\discussion_viewed) {
            $actions[] = new discussion_viewed($context, $event->userid, $event->objectid);
        } else if ($event instanceof \mod_forum\event\post_created) {
            $actions[] = new discussion_replied_to($context, $event->userid, $event->other['discussionid'], $event->objectid);
        } else if ($event instanceof \mod_glossary\event\entry_created
                || $event instanceof \mod_glossary\event\entry_approved
        ) {

            $entry = $event->get_record_snapshot('glossary_entries', $event->objectid);
            $userid = $entry->userid;
            if ($entry->approved) {
                $actions[] = new static_action('glossary_entry_published', $context, $userid, $event->objectid);
            }
        } else if ($event instanceof \mod_lesson\event\content_page_viewed) {
            $actions[] = new static_action('lesson_content_viewed', $context, $event->userid, $event->objectid);
        } else if ($event instanceof \mod_lesson\event\lesson_ended) {
            $actions[] = new static_action('lesson_ended', $context, $event->userid, $event->objectid);
        } else if ($event instanceof \mod_lesson\event\lesson_started) {
            $actions[] = new static_action('lesson_started', $context, $event->userid, $event->objectid);
        } else if ($event instanceof \mod_quiz\event\attempt_started) {
            $actions[] = new static_action('quiz_attempt_started', $context, $event->relateduserid, $event->objectid);
        } else if ($event instanceof \mod_quiz\event\attempt_submitted) {
            $actions[] = new static_action('quiz_attempt_submitted', $context, $event->relateduserid, $event->objectid);
        }

        if ($event->edulevel == $event::LEVEL_PARTICIPATING && ($event->crud === 'c' || $event->crud === 'r')) {
            $actions[] = crud::from_event($event);
        }

        return $actions;
    }

    /**
     * Make actions from assign assessable submitted event.
     *
     * @param \mod_assign\event\assessable_submitted $event The event.
     * @return action[]
     */
    protected function make_from_assign_assessable_submitted(\mod_assign\event\assessable_submitted $event) {
        $submission = $event->get_record_snapshot('assign_submission', $event->objectid);
        if (!$submission) {
            return [];
        }

        $submittedstatus = defined('ASSIGN_SUBMISSION_STATUS_SUBMITTED') ? ASSIGN_SUBMISSION_STATUS_SUBMITTED : 'submitted';
        if ($submission->status != $submittedstatus) {
            return [];
        }

        $userids = [];
        $assign = $event->get_assign();
        if ($assign->get_instance()->teamsubmission) {
            // Get group members, but exclude suspended members for consistency as current user may not see them.
            $users = $assign->get_submission_group_members($submission->groupid, true, true);
            $userids = array_map(function ($user) {
                return $user->id;
            }, $users);
        } else if (!empty($submission->userid)) {
            $userids = [$submission->userid];
        }

        $context = $event->get_context();
        $submissionid = $event->objectid;
        return array_values(array_map(function ($userid) use ($context, $submissionid) {
            return new static_action('assign_submission_submitted', $context, $userid, $submissionid);
        }, $userids));
    }

}
