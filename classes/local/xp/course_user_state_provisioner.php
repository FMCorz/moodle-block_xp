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

namespace block_xp\local\xp;

use block_xp\di;
use context;
use null_progress_trace;
use progress_trace;

/**
 * State provisioner.
 *
 * This provisioner is based on how the course_user_state_store works.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_user_state_provisioner {

    /** @var context */
    protected $context;
    /** @var \moodle_database */
    protected $db;
    /** @var state_store */
    protected $store;
    /** @var progress_trace */
    protected $logger;

    /**
     * Constructor.
     *
     * @param context $context The context.
     * @param state_store $store The state store.
     * @param progress_trace $logger The logger.
     */
    public function __construct(context $context, state_store $store, ?progress_trace $logger = null) {
        $this->db = di::get('db');
        $this->context = $context;
        $this->store = $store;
        $this->logger = $logger ?? new null_progress_trace();
    }

    /**
     * Provision the states.
     *
     * @return void
     */
    public function provision_states(): void {
        if ($this->context->contextlevel == CONTEXT_SYSTEM) {
            $this->provision_sitewide();
            return;
        } else if ($this->context->contextlevel == CONTEXT_COURSE) {
            $this->provision_course();
            return;
        }
        throw new \coding_exception('Invalid context.');
    }

    /**
     * Provision course.
     */
    protected function provision_course(): void {
        [$subsql, $subparams] = get_with_capability_sql($this->context, 'block/xp:earnxp');
        $sql = "SELECT DISTINCT u.id
                  FROM {user} u
             LEFT JOIN {block_xp} x
                    ON x.userid = u.id
                   AND x.courseid = :courseid
                 WHERE x.id IS NULL
                   AND u.deleted = 0
                   AND u.suspended = 0
                   AND u.id IN ($subsql)";
        $params = [
            'courseid' => $this->context->instanceid,
        ] + $subparams;

        $this->logger->output('Looking for missing states for users with block/xp:earnxp.');
        $this->provision_from_sql($sql, $params);
    }

    /**
     * Provision sitewide.
     */
    protected function provision_sitewide(): void {
        // We fetch the users who have been assigned a role that has the permission to earn XP. We do
        // support role overrides, and only check if the role was assigned in the system, category or course level.
        $sql = "SELECT DISTINCT u.id
                  FROM {user} u
             LEFT JOIN {block_xp} x
                    ON x.userid = u.id
                   AND x.courseid = :courseid
                  JOIN {role_assignments} ra
                    ON ra.userid = u.id
                  JOIN {context} ctx
                    ON ra.contextid = ctx.id
                 WHERE x.id IS NULL
                   AND u.deleted = 0
                   AND u.suspended = 0
                   AND ctx.contextlevel IN (:syslevel, :catlevel, :courselevel)
                   AND ra.roleid IN (SELECT roleid
                                       FROM {role_capabilities}
                                      WHERE capability = :cap
                                        AND permission = :perm
                                        AND contextid = :sysctxid)";
        $params = [
            'courseid' => SITEID,
            'syslevel' => CONTEXT_SYSTEM,
            'catlevel' => CONTEXT_COURSECAT,
            'courselevel' => CONTEXT_COURSE,
            'cap' => 'block/xp:earnxp',
            'perm' => CAP_ALLOW,
            'sysctxid' => SYSCONTEXTID,
        ];

        $this->logger->output('Looking for missing states for users with block/xp:earnxp anywhere.');
        $this->provision_from_sql($sql, $params);
    }

    /**
     * Provision from SQL.
     *
     * @param string $sql The SQL.
     * @param array $params The parameters.
     */
    protected function provision_from_sql(string $sql, array $params): void {
        $userids = $this->db->get_fieldset_sql($sql, $params);
        $nusers = count($userids);
        $this->logger->output("Found {$nusers} user(s) missing state.");
        foreach ($userids as $userid) {
            $this->logger->output("Creating state for user {$userid}.");
            $this->store->increase($userid, 0);
        }
    }
}
