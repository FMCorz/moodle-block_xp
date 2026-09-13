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

use action_menu_link;
use moodle_database;
use moodle_url;
use pix_icon;
use renderer_base;
use table_sql;
use block_xp\di;
use block_xp\local\course_world;
use block_xp\local\navigation\navigator;
use block_xp\local\permission\access_logs_permissions;
use block_xp\local\routing\url_resolver;
use block_xp\local\sql\limit;
use block_xp\local\userfilter\group_members;
use block_xp\local\userfilter\nobody;
use block_xp\local\xp\state;
use block_xp\local\xp\state_store_query;
use block_xp\local\xp\state_store_with_delete;
use block_xp\local\xp\state_store_with_query;
use block_xp\local\xp\state_with_presence;
use block_xp\local\xp\state_with_subject;
use block_xp\local\xp\state_with_user;

/**
 * Block XP report table class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class report_table extends table_sql {
    /** @var moodle_database The DB. */
    protected $db;
    /** @var \block_xp\local\course_world The world. */
    protected $world = null;
    /** @var state_store_with_query The store. */
    protected $store = null;
    /** @var access_logs_permissions|null The log access permissions. */
    protected $logaccessperms = null;
    /** @var navigator The navigator. */
    protected $navigator;
    /** @var renderer_base The renderer. */
    protected $renderer = null;
    /** @var url_resolver The URL resolver. */
    protected $urlresolver;
    /** @var int The groupd ID. */
    protected $groupid = null;
    /** @var array The columns definition where keys are IDs, values are lang strings. */
    protected $columnsdefinition;

    /**
     * Constructor.
     *
     * @param moodle_database $db The DB.
     * @param course_world $world The world.
     * @param renderer_base $renderer The renderer.
     * @param state_store_with_query $store The store.
     * @param int $groupid The group ID.
     */
    public function __construct(
        moodle_database $db,
        course_world $world,
        renderer_base $renderer,
        state_store_with_query $store,
        $groupid
    ) {

        parent::__construct('block_xp_report');

        $this->db = $db;
        $this->groupid = $groupid;
        $this->world = $world;
        $this->renderer = $renderer;
        $this->store = $store;
        $this->urlresolver = di::get('url_resolver');
        $this->navigator = di::get('world_navigator_factory')->get_navigator_for_world($world);

        $accessperms = $this->world->get_access_permissions();
        if ($accessperms instanceof access_logs_permissions) {
            $this->logaccessperms = $accessperms;
        }

        // Init the stuff.
        $this->init();
    }

    /**
     * Init function.
     *
     * @return void
     */
    protected function init() {
        $columnsdef = $this->get_columns_definition();
        $this->define_columns(array_keys($columnsdef));
        $this->define_headers(array_values($columnsdef));

        // Level sorting is a fake column sorting that uses the 'xp' column under the hood.
        $this->sortable(true, 'lvl', SORT_DESC);
        $this->no_sorting('userpic');
        $this->no_sorting('progress');
        $this->no_sorting('actions');
        $this->collapsible(false);
        $this->set_attribute('class', 'block_xp-report-table');
        $this->column_class('userpic', 'col-userpic');
        $this->column_class('actions', 'col-actions');
    }

    /**
     * Generate the columns definition.
     *
     * @return array
     */
    protected function generate_columns_definition() {
        $cols = [
            'userpic' => '',
            'fullname' => get_string('fullname', 'core'),
            'lvl' => get_string('level', 'block_xp'),
            'xp' => get_string('total', 'block_xp'),
            'progress' => get_string('progress', 'block_xp'),
        ];
        if ($this->world->get_access_permissions()->can_manage()) {
            $cols['actions'] = '';
        }
        return $cols;
    }

    /**
     * Get the columns definition.
     *
     * @return array
     */
    final protected function get_columns_definition() {
        if (!isset($this->columnsdefinition)) {
            $this->columnsdefinition = $this->generate_columns_definition();
        }
        return $this->columnsdefinition;
    }

    /**
     * Get the columns.
     *
     * @return array
     * @deprecated Since Level Up XP 3.12, please use self::get_columns_definition instead.
     */
    protected function get_columns() {
        return array_keys($this->get_columns_definition());
    }

    /**
     * Get the headers.
     *
     * @return void
     * @deprecated Since Level Up XP 3.12, please use self::get_columns_definition instead.
     */
    protected function get_headers() {
        return array_map(function ($header) {
            return (string) $header;
        }, array_values($this->get_columns_definition()));
    }

    /**
     * Get the actions for row.
     *
     * @param state_with_user $state The state.
     * @return action_menu_link[] List of actions.
     */
    protected function get_row_actions($state) {
        $actions = [];

        $actions[] = new action_menu_link(
            $this->baseurl,
            new pix_icon('t/edit', get_string('edit', 'core')),
            get_string('edit', 'core'),
            false,
            [
                'data-xp-action' => 'open-form',
                'data-form-class' => 'block_xp\form\user_xp',
                'data-form-args__contextid' => $this->world->get_context()->id,
                'data-form-args__userid' => $state->get_id(),
                'data-modal-title' => get_string('edita', 'core', fullname($state->get_user())),
            ]
        );

        if ($this->logaccessperms && $this->logaccessperms->can_access_logs()) {
            $url = $this->navigator->get_url('log');
            $url->param('userid', $state->get_id());
            $actions[] = new action_menu_link(
                $url,
                new pix_icon('t/log', get_string('logs', 'core')),
                get_string('viewlogs', 'block_xp')
            );
        }

        if ($this->store instanceof state_store_with_delete && $state instanceof state_with_presence && $state->is_present()) {
            $url = new moodle_url($this->baseurl, ['action' => '', 'delete' => 1, 'userid' => $state->get_id()]);
            $action = new action_menu_link(
                $url,
                new pix_icon('t/delete', get_string('delete', 'core')),
                get_string('delete', 'core')
            );
            $action->add_class('text-danger');
            $actions[] = $action;
        }

        return $actions;
    }

    /**
     * Formats the column actions.
     *
     * @param state_with_user $state The state.
     * @return string Output produced.
     */
    protected function col_actions($state) {
        $actions = $this->get_row_actions($state);
        if (empty($actions)) {
            return '';
        }
        return $this->renderer->control_menu($actions);
    }

    /**
     * Formats the column.
     *
     * @param state_with_user $state The state.
     * @return string Output produced.
     */
    public function col_fullname($state) {
        $user = $state->get_user();
        $o = parent::col_fullname($user);
        if ($user->suspended) {
            $o .= ' (' . get_string('suspended', 'core') . ')';
        }
        return $o;
    }

    /**
     * Formats the column level.
     *
     * @param state $state The state.
     * @return string Output produced.
     */
    protected function col_lvl($state) {
        if ($state instanceof state_with_presence && !$state->is_present()) {
            return '-';
        }
        return $state->get_level()->get_level();
    }

    /**
     * Formats the column progress.
     *
     * @param state $state The state.
     * @return string Output produced.
     */
    protected function col_progress($state) {
        return $this->renderer->progress_bar($state);
    }

    /**
     * Formats the column XP.
     *
     * @param state $state The state.
     * @return string Output produced.
     */
    protected function col_xp($state) {
        if ($state instanceof state_with_presence && !$state->is_present()) {
            return '-';
        }
        return $this->renderer->xp($state->get_xp());
    }

    /**
     * Formats the column userpic.
     *
     * @param state $state The state.
     * @return string Output produced.
     */
    protected function col_userpic($state) {
        $picture = null;
        $link = null;
        if ($state instanceof state_with_subject) {
            $picture = $state->get_picture();
            $link = $state->get_link();
        }
        return $this->renderer->user_avatar($picture, $link);
    }

    /**
     * Escape a string.
     *
     * @param ?string $value
     * @param bool $preventdoubleencoding.
     * @return string Safe for HTML.
     */
    protected function escape($value, bool $preventdoubleencoding = false) {
        $value ??= '';
        if (!$this->is_downloading() || $this->export_class_instance()->supports_html()) {
            if ($preventdoubleencoding) {
                $value = html_entity_decode($value, ENT_COMPAT);
            }
            return s($value);
        }
        return $value;
    }
    /**
     * Get the columns to sort by.
     *
     * @return array column name => SORT_... constant.
     */
    public function get_sort_columns() {
        $orderby = parent::get_sort_columns();

        // It should never be empty, but if it is then never mind...
        if (!empty($orderby)) {
            // If we are sorting by lvl, remove the xp column as we treat them as alises.
            if (array_key_exists('lvl', $orderby)) {
                unset($orderby['xp']);
            }

            // Always add the user ID, to avoid random ordering.
            if (!array_key_exists('id', $orderby)) {
                $orderby['id'] = SORT_ASC;
            }
        }

        return $orderby;
    }

    /**
     * Make the query from the table filters and sorting preferences.
     *
     * @return state_store_query
     */
    protected function make_query(): state_store_query {
        $query = new state_store_query();
        $filterset = $this->get_filterset();
        if ($filterset && $filterset->has_filter('term')) {
            $query->set_term($filterset->get_filter('term')->current());
        }

        if ($this->groupid < 0) {
            $query->set_user_filter(new nobody());
        } else if ($this->groupid > 0) {
            $query->set_user_filter(new group_members($this->groupid));
        }

        foreach ($this->get_sort_columns() as $field => $direction) {
            $field = $field === 'lvl' ? 'xp' : $field;
            $query->add_order_by($field, $direction === SORT_ASC ? SORT_ASC : SORT_DESC);
        }

        return $query;
    }

    /**
     * Load states for the table.
     *
     * @param int $pagesize The page size.
     * @param bool $useinitialsbar Whether to use initial bars (unused).
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        $query = $this->make_query();
        $limit = new limit(0);
        if (!$this->is_downloading()) {
            $this->pagesize($pagesize, $this->store->count($query));
            $limit = new limit($this->get_page_size(), $this->get_page_start());
        }
        $this->rawdata = $this->store->list($query, $limit);
    }

    /**
     * Override to rephrase.
     *
     * @return void
     */
    public function print_nothing_to_display() {
        $issite = di::get('config')->get('context') == CONTEXT_SYSTEM && $this->world->get_courseid() == SITEID;
        $hasfilters = false;
        $showfilters = false;

        if ($this->can_be_reset()) {
            $hasfilters = true;
            $showfilters = true;
        }

        // Render button to allow user to reset table preferences, and the initial bars if some filters
        // are used. If none of the filters are used and there is nothing to display it just means that
        // the course is empty and thus we do not show anything but a message.
        echo $this->render_reset_button();
        if ($showfilters) {
            $this->print_initials_bar();
        }

        $message = get_string($issite ? 'reportisempty' : 'reportisemptyenrolstudents', 'block_xp');
        if ($hasfilters) {
            $message = get_string('nothingtodisplay', 'core');
        }

        echo \html_writer::div(
            \block_xp\di::get('renderer')->notification_without_close($message, 'info'),
            '',
            ['style' => 'margin: 1em 0']
        );
    }
}
