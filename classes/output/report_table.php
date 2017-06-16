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
 * Block XP report table.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\output;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/tablelib.php');

use context_course;
use context_helper;
use html_writer;
use moodle_url;
use pix_icon;
use renderer_base;
use stdClass;
use table_sql;
use user_picture;
use block_xp\local\course_world;
use block_xp\local\xp\user_state_course_store;

/**
 * Block XP report table class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class report_table extends table_sql {

    /** @var block_xp\local\course_world The world. */
    protected $world = null;
    /** @var block_xp\local\xp\user_state_course_store The store. */
    protected $store = null;
    /** @var renderer_base The renderer. */
    protected $renderer = null;

    /**
     * Constructor.
     *
     * @param course_world $world The world.
     * @param renderer_base $renderer The renderer.
     * @param user_state_course_store $store The store.
     * @param int $groupid The group ID.
     */
    public function __construct(
            course_world $world,
            renderer_base $renderer,
            user_state_course_store $store,
            $groupid
        ) {
        global $DB, $PAGE;
        parent::__construct('block_xp_report');

        // Block XP stuff.
        $this->world = $world;
        $this->renderer = $renderer;
        $this->store = $store;
        $courseid = $this->world->get_courseid();
        $context = context_course::instance($courseid);

        // Define columns.
        $this->define_columns(array(
            'userpic',
            'fullname',
            'lvl',
            'xp',
            'progress',
            'actions'
        ));
        $this->define_headers(array(
            '',
            get_string('fullname'),
            get_string('level', 'block_xp'),
            get_string('xp', 'block_xp'),
            get_string('progress', 'block_xp'),
            ''
        ));

        // Get all the users that are enrolled and can earn XP.
        $ids = [];
        $users = get_enrolled_users($context, 'block/xp:earnxp', $groupid);
        foreach ($users as $user) {
            $ids[$user->id] = $user->id;
        }
        unset($users);

        // Get the users which might not be enrolled or are revoked the permission, but still should
        // be displayed in the report for the teachers' benefit. We need to filter out the users which
        // are not a member of the group though.
        if (empty($groupid)) {
            $sql = 'SELECT userid FROM {block_xp} WHERE courseid = :courseid';
            $params = array('courseid' => $courseid);
        } else {
            $sql = 'SELECT b.userid
                      FROM {block_xp} b
                      JOIN {groups_members} gm
                        ON b.userid = gm.userid
                       AND gm.groupid = :groupid
                     WHERE courseid = :courseid';
            $params = array('courseid' => $courseid, 'groupid' => $groupid);
        }
        $entries = $DB->get_recordset_sql($sql, $params);
        foreach ($entries as $entry) {
            $ids[$entry->userid] = $entry->userid;
        }
        $entries->close();
        list($insql, $inparams) = $DB->get_in_or_equal($ids, SQL_PARAMS_NAMED, 'param', true, null);

        // Define SQL.
        $this->sql = new stdClass();
        $this->sql->fields = user_picture::fields('u') . ', COALESCE(x.lvl, 1) AS lvl, x.xp, ' .
            context_helper::get_preload_record_columns_sql('ctx');
        $this->sql->from = "{user} u
                       JOIN {context} ctx
                         ON ctx.instanceid = u.id
                        AND ctx.contextlevel = :contextlevel
                  LEFT JOIN {block_xp} x
                         ON (x.userid = u.id AND x.courseid = :courseid)";
        $this->sql->where = "u.id $insql";
        $this->sql->params = array_merge($inparams, array(
            'courseid' => $courseid,
            'contextlevel' => CONTEXT_USER
        ));

        // Define various table settings.
        $this->sortable(true, 'lvl', SORT_DESC);
        $this->no_sorting('userpic');
        $this->no_sorting('progress');
        $this->collapsible(false);
    }

    /**
     * Override to add states.
     *
     * @return void
     */
    public function build_table() {
        if (!$this->rawdata) {
            return;
        }

        foreach ($this->rawdata as $row) {
            $row->state = $this->store->make_state_from_record($row, 'id');
            $row->lvl = $row->state->get_level()->get_level();

            $formattedrow = $this->format_row($row);
            $this->add_data_keyed($formattedrow,
                $this->get_row_class($row));
        }
    }

    /**
     * Formats the column actions.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_actions($row) {
        $url = new moodle_url($this->baseurl);
        $url->params([
            'action' => 'edit',
            'userid' => $row->id
        ]);
        return $this->renderer->action_icon($url, new pix_icon('t/edit', get_string('edit')));
    }

    /**
     * Formats the column level.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_lvl($row) {
        return isset($row->lvl) ? $row->lvl : 1;
    }

    /**
     * Formats the column progress.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_progress($row) {
        return $this->renderer->progress_bar($row->state);
    }

    /**
     * Formats the column XP.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_xp($row) {
        return isset($row->xp) ? $row->xp : 0;
    }

    /**
     * Formats the column userpic.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_userpic($row) {
        return $this->renderer->user_picture($row->state->get_user());
    }

    /**
     * Construct the ORDER BY clause.
     *
     * We override this to ensure that XP set to null appears at the bottom, not the top.
     *
     * @param array $cols The columns.
     * @param array $textsortcols The text columns.
     * @return string
     */
    public static function construct_order_by($cols, $textsortcols = []) {
        $newcols = [];

        // We use a foreach to maintain the order in which the fields were defined.
        foreach ($cols as $field => $sortorder) {
            if ($field == 'xp') {
                $field = 'COALESCE(xp, 0)';
            }
            $newcols[$field] = $sortorder;
        }

        return parent::construct_order_by($newcols, $textsortcols);
    }

    /**
     * Get SQL sort.
     *
     * Must be overridden because otherwise it calls the parent 'construct_order_by()'.
     *
     * @return string
     */
    public function get_sql_sort() {
        return static::construct_order_by($this->get_sort_columns(), []);
    }
}
