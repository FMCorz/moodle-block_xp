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
// See <https://levelup.plus>.

namespace block_xp\local\group;

use block_xp\di;
use block_xp\local\course_world;
use block_xp\local\userfilter\everyone;
use block_xp\local\userfilter\group_members;
use block_xp\local\userfilter\nobody;
use block_xp\local\userfilter\user_filter;
use block_xp\local\world;
use course_modinfo;
use context;
use context_course;
use core_group\visibility;

/**
 * Group policy.
 *
 * The goal is this is to abstract away the logic scattered around core. We do not create
 * an interface of this as we do not expect to implement varying implementations. None of the
 * logic here should be specific to XP.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class group_policy {
    /** @var int The course ID. */
    protected $courseid;
    /** @var context The course context. */
    protected $context;
    /** @var int The default grouping ID. */
    protected $defaultgroupingid;

    /**
     * Constructor.
     *
     * @param int $courseid
     */
    public function __construct(int $courseid) {
        $this->courseid = $courseid;
        $this->context = context_course::instance($courseid); // Will throw if course does not exist.
    }
    /**
     * Whether can access all groups.
     *
     * @param ?int $actinguserid The acting user ID.
     * @return bool
     */
    public function can_access_all_groups(?int $actinguserid = null): bool {
        return has_capability('moodle/site:accessallgroups', $this->context, $actinguserid);
    }

    /**
     * Validates whether a user can see another one, from a group's perspective.
     *
     * This does not validate enrolments, permissions, etc.
     *
     * In its current form, it technically possible for a user to be "seen" when
     * they share a group with the acting user, even if that group is not exposed
     * in the UI menu. That is because Moodle's default grouping ID only seems to
     * be a menu filtering option, rather than a hardcoded user-visibility system.
     *
     * For example, even though a separate group setting may hide users from one
     * another one most if not all pages, crafting a URL to their course profile
     * will display them irrespective, if they share any group, even a group
     * that is not in the default grouping.
     *
     * As such, while this method is more permissive than if we observe the users
     * displayed in the UI through the group selector, it is not indicative of
     * an issue as it matches core's actual behaviour.
     *
     * @param int $targetuserid
     * @param int|null $actinguserid
     * @return bool
     */
    public function can_see_user(int $targetuserid, ?int $actinguserid = null): bool {
        global $USER;
        $actinguserid ??= $USER->id;

        // Logic from here on is matching core groups_user_groups_visible.
        $groupmode = $this->get_group_mode();
        if ($groupmode === NOGROUPS || $groupmode === VISIBLEGROUPS) {
            return true;
        }

        if ($this->can_access_all_groups($actinguserid)) {
            return true;
        }

        $usergroups = $this->get_all_groups($targetuserid, $actinguserid);
        $actingusergroups = $this->get_all_groups($actinguserid, $actinguserid);
        $samegroups = array_intersect_key($actingusergroups, $usergroups);
        return !empty($samegroups);
    }

    /**
     * Whether can select a group.
     *
     * This emulates course group selection while enforcing the separate-groups
     * access check that core expects callers to perform when the active group is 0.
     *
     * @param int $groupid Or 0 for all participants.
     * @param int|null $actinguserid The acting user ID.
     * @return bool
     */
    public function can_select_group(int $groupid, ?int $actinguserid = null): bool {
        global $USER;
        $actinguserid ??= $USER->id;
        $groupmode = $this->get_group_mode();

        if ($groupmode === NOGROUPS) {
            // This is different to the group menu logic, but we essentially allow the group 0
            // because it means everyone, or all participants, and that's what NOGROUPS is.
            return !$groupid;
        }

        $aag = $this->can_access_all_groups($actinguserid);
        $course = $this->get_course_light();

        if ($groupmode === VISIBLEGROUPS || $aag) {
            $allowedgroups = $this->get_all_groups(null, $actinguserid, $course->defaultgroupingid);
        } else {
            $allowedgroups = $this->get_all_groups($actinguserid, $actinguserid, $course->defaultgroupingid);
        }

        if (!$groupid) {
            // This is different from the group menu because in separate groups we want the user
            // to have AAG to be able to select "All participants". Otherwise, they always can.
            return $aag || $groupmode === VISIBLEGROUPS;
        }

        return array_key_exists($groupid, $allowedgroups);
    }

    /**
     * Get the group mode.
     *
     * @return int One of NOGROUPS, VISIBLE_GROUPS or SEPARATE_GROUPS.
     */
    public function get_group_mode(): int {
        return (int) $this->get_course_light()->groupmode;
    }

    /**
     * Get the context.
     *
     * @return context
     */
    public function get_context(): context {
        return $this->context;
    }

    /**
     * Get the course ID.
     *
     * @return int
     */
    public function get_course_id(): int {
        return $this->courseid;
    }

    /**
     * Get a light course object.
     *
     * Modinfo guarantees: id, shortname, fullname, format, enablecompletion, groupmode, groupmodeforce, cacherev.
     * We also guarantee: defaulgroupingid.
     *
     * @return \stdClass
     */
    protected function get_course_light(): \stdClass {
        $course = $this->get_modinfo()->get_course();
        $courselight = (object) [
            'id' => $course->id,
            'shortname' => $course->shortname,
            'fullname' => $course->fullname,
            'format' => $course->format,
            'enablecompletion' => $course->enablecompletion,
            'groupmode' => $course->groupmode,
            'groupmodeforce' => $course->groupmodeforce,
            'cacherev' => $course->cacherev,

            // Our guarantees.
            'defaultgroupingid' => $course->defaultgroupingid ?? $this->get_default_grouping_id(),
        ];
        return $courselight;
    }

    /**
     * Return the current group.
     *
     * This uses the view function `groups_get_course_group` which is session bound and
     * is only meant to be used in stateful web views.
     *
     * @param bool $update Whether to observe the `group` parameter to switch group, see self::get_group_menu.
     * @return false|int Where false is for NOGROUPS and int is for group or all participants.
     */
    public function get_current_group_id(bool $update = true) {
        $groupid = groups_get_course_group($this->get_course_light(), $update);
        return $groupid === false ? false : (int) $groupid;
    }

    /**
     * Get the default grouping ID.
     *
     * @return int
     */
    protected function get_default_grouping_id(): int {
        global $COURSE, $SITE;
        if (!isset($this->defaultgroupingid)) {
            $defaultgroupingid = 0;
            if ($this->courseid == $COURSE->id) {
                $defaultgroupingid = $COURSE->defaultgroupingid;
            } else if ($this->courseid != $SITE->id) {
                $db = di::get('db');
                $defaultgroupingid = $db->get_field('course', 'defaultgroupingid', ['id' => $this->courseid], IGNORE_MISSING);
            }
            $this->defaultgroupingid = $defaultgroupingid ?: 0;
        }
        return $this->defaultgroupingid;
    }

    /**
     * Get the group menu HTML.
     *
     * This uses the view function `groups_print_course_menu` which is only meant to be used in stateful web views.
     * See its self::get_current_group_id counterpart.
     *
     * @param \moodle_url $baseurl
     * @return string
     */
    public function get_group_menu(\moodle_url $baseurl): string {
        return groups_print_course_menu($this->get_course_light(), $baseurl, true);
    }

    /**
     * Get selectable groups.
     *
     * This emulates course group selection while enforcing the separate-groups
     * access check that core expects callers to perform when the active group is 0.
     *
     * @param int|null $actinguserid The acting user ID.
     * @return \stdClass[] With keys id, name, ismember.
     */
    public function get_selectable_groups(?int $actinguserid = null): array {
        global $USER;
        $actinguserid ??= $USER->id;
        $groupmode = $this->get_group_mode();

        if ($groupmode === NOGROUPS) {
            return [];
        }

        $aag = $this->can_access_all_groups($actinguserid);
        $usergroups = [];
        if ($groupmode === VISIBLEGROUPS || $aag) {
            $course = $this->get_course_light();
            $allowedgroups = $this->get_all_groups(null, $actinguserid, $course->defaultgroupingid);
            $usergroups = $this->get_all_groups($actinguserid, $actinguserid, $course->defaultgroupingid);
        } else {
            $course = $this->get_course_light();
            $allowedgroups = $this->get_all_groups($actinguserid, $actinguserid, $course->defaultgroupingid);
            $usergroups = $allowedgroups;
        }

        $groups = [];
        if ($groupmode === VISIBLEGROUPS || $aag) {
            // This is different from the group menu because in separate groups we want the user
            // to have AAG to be able to select "All participants". Otherwise, they always can.
            $groups[] = (object) [
                'id' => 0,
                'name' => get_string('allparticipants', 'core'),
                'ismember' => false,
            ];
        }

        foreach ($usergroups as $group) {
            if (!array_key_exists($group->id, $allowedgroups)) {
                continue;
            }
            $groups[] = (object) [
                'id' => (int) $group->id,
                'name' => format_string($group->name, true, ['context' => $this->get_context()]),
                'ismember' => true,
            ];
            unset($allowedgroups[$group->id]);
        }

        foreach ($allowedgroups as $group) {
            $groups[] = (object) [
                'id' => (int) $group->id,
                'name' => format_string($group->name, true, ['context' => $this->get_context()]),
                'ismember' => false,
            ];
        }

        return $groups;
    }

    /**
     * Get course groups, optionally filtered by user and grouping.
     *
     * When a target user is supplied, only the groups containing that user are returned. Without
     * one, every course group is considered. A grouping ID further limits the result to that grouping.
     *
     * This applies the same membership and visibility rules as groups_get_all_groups(), but evaluates
     * checks normally tied to the global $USER for the acting user instead. The other core arguments
     * are not needed here and remain at their defaults.
     *
     * @param int|null $targetuserid The target user ID, or null to consider all groups.
     * @param int|null $actinguserid The acting user ID, or null for the current user.
     * @param int $groupingid The grouping ID, or 0 for all groupings.
     * @return \stdClass[]
     */
    protected function get_all_groups(?int $targetuserid, ?int $actinguserid = null, int $groupingid = 0): array {
        global $USER;
        $targetuserid = $targetuserid ?: null; // Continue to treat core's 0 value as no target user.
        $actinguserid ??= $USER->id;

        $canviewhidden = has_capability('moodle/course:viewhiddengroups', $this->context, $actinguserid);

        // The cache is safe when no user filter is needed and every group is visible to the acting user.
        if ($targetuserid === null && ($canviewhidden || !visibility::course_has_hidden_groups($this->courseid))) {
            $data = groups_get_course_data($this->courseid);
            if (!$groupingid) {
                return $data->groups;
            }

            $groups = [];
            foreach ($data->mappings as $mapping) {
                if ($mapping->groupingid == $groupingid && isset($data->groups[$mapping->groupid])) {
                    $groups[$mapping->groupid] = $data->groups[$mapping->groupid];
                }
            }
            return $groups;
        }

        $params = ['courseid' => $this->courseid];
        $targetfrom = '';
        $targetwhere = '';
        if ($targetuserid !== null) {
            // Only return groups containing the target user.
            $targetfrom = 'JOIN {groups_members} gm ON gm.groupid = g.id';
            $targetwhere = 'AND gm.userid = :targetuserid';
            $params['targetuserid'] = $targetuserid;
        }

        $groupingfrom = '';
        $groupingwhere = '';
        if ($groupingid) {
            // Only return groups belonging to the requested grouping.
            $groupingfrom = 'JOIN {groupings_groups} gg ON gg.groupid = g.id';
            $groupingwhere = 'AND gg.groupingid = :groupingid';
            $params['groupingid'] = $groupingid;
        }

        $visibilityfrom = '';
        $visibilitywhere = '';
        if (!$canviewhidden) {
            // Core uses the target membership above, or the acting user's membership when there is no target.
            if ($targetuserid === null) {
                $visibilityfrom = 'LEFT JOIN {groups_members} gm ON gm.groupid = g.id AND gm.userid = :actinguserid';
                $params['actinguserid'] = $actinguserid;
            }

            // Public groups are always visible. Member and own groups require the joined membership; hidden groups are omitted.
            $visibilitywhere = 'AND (g.visibility = :visibilityall'
                . ' OR (g.visibility IN (:visibilitymembers, :visibilityown) AND gm.id IS NOT NULL))';
            $params['visibilityall'] = GROUPS_VISIBILITY_ALL;
            $params['visibilitymembers'] = GROUPS_VISIBILITY_MEMBERS;
            $params['visibilityown'] = GROUPS_VISIBILITY_OWN;
        }

        $db = di::get('db');
        return $db->get_records_sql("SELECT g.*
                                       FROM {groups} g
                                       $targetfrom
                                       $groupingfrom
                                       $visibilityfrom
                                      WHERE g.courseid = :courseid
                                       $targetwhere
                                       $groupingwhere
                                       $visibilitywhere
                                   ORDER BY g.name ASC", $params);
    }

    /**
     * Get mod info.
     *
     * @param int $actinguserid Or current user.
     * @return course_modinfo
     */
    protected function get_modinfo(int $actinguserid = 0): course_modinfo {
        return get_fast_modinfo($this->courseid, $actinguserid);
    }

    /**
     * Get a user filter for a group.
     *
     * Handles falsy value for all participants, but does not apply any validation, this
     * is the responsiblity of the calling code.
     *
     * @param int $groupid The group ID, or 0.
     * @return user_filter
     */
    public function get_user_filter(int $groupid): user_filter {
        if (!$groupid) {
            return new everyone();
        } else if ($groupid < 0) {
            throw new \coding_exception('Invalid group_id');
        }
        return new group_members($groupid);
    }

    /**
     * From context.
     *
     * @param context $context
     * @return static
     */
    public static function from_context(context $context) {
        $coursecontext = $context->get_course_context(false);
        return static::from_course_id($coursecontext ? $coursecontext->instanceid : SITEID);
    }

    /**
     * From course ID.
     *
     * @param int $id
     * @return static
     */
    public static function from_course_id(int $id) {
        return new static($id);
    }

    /**
     * From world.
     *
     * @param world $world
     * @return static
     */
    public static function from_world(world $world) {
        return static::from_context($world->get_context());
    }
}
