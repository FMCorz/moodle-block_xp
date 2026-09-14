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
 * Logs table.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\output;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/tablelib.php');

use table_sql;
use block_xp\local\course_world;
use block_xp\local\factory\reason_from_log_entry_factory;
use block_xp\local\logger\collection_logger;
use block_xp\local\logger\collection_logger_query;
use block_xp\local\logger\collection_logger_with_query;
use block_xp\local\logger\log;
use block_xp\local\reason\reason_with_short_description;
use block_xp\local\sql\limit;
use block_xp\local\userfilter\group_members;
use block_xp\local\userfilter\nobody;
use block_xp\local\world;
use moodle_url;
use pix_icon;

/**
 * Logs table.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class logs_table extends table_sql {
    /** @var ?array The columns definition. */
    protected $columnsdefinition;
    /** @var ?collection_logger The collection logger. */
    protected $collectionlogger = null;
    /** @var world The world. */
    protected $world;
    /** @var ?reason_from_log_entry_factory The reason factory. */
    protected $reasonfactory;
    /** @var \renderer_base The renderer. */
    protected $renderer;
    /** @var int Filter by user ID, falsy means not filtering. */
    protected $filterbyuserid;
    /** @var int The group ID. */
    protected $groupid;

    /**
     * Constructor.
     *
     * @param world $world The world.
     * @param ?reason_from_log_entry_factory $reasonfactory Reason factory, no longer used.
     * @param int $groupid The group ID.
     * @param int|null $userid The user ID.
     */
    public function __construct(world $world, ?reason_from_log_entry_factory $reasonfactory, $groupid, $userid = null) {
        $userid = max(0, (int) $userid);
        $this->groupid = $groupid;
        parent::__construct('block_xp_logs_' . $userid);

        $this->world = $world;
        $this->reasonfactory = $reasonfactory;
        $this->renderer = \block_xp\di::get('renderer');
        $this->filterbyuserid = $userid;

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

        // Define various table settings.
        $this->no_sorting('reason');
        $this->sortable(true, 'timerecorded', SORT_DESC);
        $this->collapsible(false);
    }

    /**
     * Column.
     *
     * @param log $log The log.
     * @return string
     */
    public function col_fullname($log) {
        $user = $log->get_user();
        $fullname = parent::col_fullname($user);
        if (!empty($user->suspended)) {
            $fullname .= ' (' . get_string('suspended', 'core') . ')';
        }
        if (!$this->is_downloading() && !$this->filterbyuserid) {
            $fullname .= ' ' . $this->renderer->action_icon(
                new moodle_url($this->baseurl, ['userid' => $log->get_user_id()]),
                new pix_icon('i/search', get_string('filterbyuser', 'block_xp'))
            );
        }
        return $fullname;
    }

    /**
     * Column.
     *
     * @param log $log The log.
     * @return string|int
     */
    protected function col_points($log) {
        if ($this->is_downloading()) {
            return $log->get_points();
        }
        return $this->renderer->xp($log->get_points());
    }

    /**
     * Column.
     *
     * @param log $log The log.
     * @return string
     */
    protected function col_reason($log) {
        $reason = $log->get_reason();
        if ($reason instanceof reason_with_short_description) {
            return $this->escape($reason->get_short_description());
        }
        return '';
    }

    /**
     * Column.
     *
     * @param log $log The log.
     * @return string
     */
    protected function col_timerecorded($log) {
        return userdate($log->get_time_recorded()->getTimestamp());
    }

    /**
     * Escape a string for HTML output and exports that support HTML.
     *
     * @param string|null $value The value.
     * @param bool $preventdoubleencoding Whether to decode existing entities first.
     * @return string
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
     * Generate the columns definition.
     *
     * @return array
     */
    protected function generate_columns_definition() {
        return [
            'timerecorded' => get_string('eventtime', 'block_xp'),
            'fullname' => get_string('fullname'),
            'points' => get_string('reward', 'block_xp'),
            'reason' => get_string('reason', 'block_xp'),
        ];
    }

    /**
     * Instantiate the query.
     *
     * @return collection_logger_query
     */
    protected function instantiate_query(): collection_logger_query {
        return new collection_logger_query();
    }

    /**
     * Make the query from the table filters and sorting preferences.
     *
     * @return collection_logger_query
     */
    protected function make_query(): collection_logger_query {
        $query = $this->instantiate_query();
        $filterset = $this->get_filterset();
        if ($filterset && $filterset->has_filter('term')) {
            $query->set_term($filterset->get_filter('term')->current());
        }

        if ($this->filterbyuserid) {
            $query->set_user_id($this->filterbyuserid);
        }

        if ($this->groupid < 0) {
            $query->set_user_filter(new nobody());
        } else if ($this->groupid > 0) {
            $query->set_user_filter(new group_members($this->groupid));
        }

        foreach ($this->get_sort_columns() as $key => $direction) {
            $query->add_order_by($key, $direction === SORT_ASC ? SORT_ASC : SORT_DESC);
        }

        return $query;
    }

    /**
     * Load logs for the table.
     *
     * @param int $pagesize The page size.
     * @param bool $useinitialsbar Whether to use initial bars (unused).
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        if (!$this->collectionlogger instanceof collection_logger_with_query) {
            $this->rawdata = [];
            return;
        }

        $query = $this->make_query();
        $limit = new limit(0);
        if (!$this->is_downloading()) {
            $this->pagesize($pagesize, $this->collectionlogger->count($query));
            $limit = new limit($this->get_page_size(), $this->get_page_start());
        }
        $this->rawdata = $this->collectionlogger->list($query, $limit);
    }

    /**
     * Override to rephrase.
     *
     * @return void
     */
    public function print_nothing_to_display() {
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

        $message = get_string('nologsrecordedyet', 'block_xp');
        if ($hasfilters) {
            $message = get_string('nothingtodisplay', 'core');
        }

        echo \html_writer::div(
            \block_xp\di::get('renderer')->notification_without_close($message, 'info'),
            '',
            ['style' => 'margin: 1em 0']
        );
    }

    /**
     * Set the collection logger.
     *
     * @param collection_logger $logger The logger.
     */
    public function set_collection_logger(collection_logger $logger): void {
        $this->collectionlogger = $logger;
    }
}
