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
 * Block XP filters abstract class.
 *
 * @package    block_xp_filterset
 * @copyright  2017 Ruben Cancho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

abstract class block_xp_filterset {

    /** @var block_xp_filterset[] Array of block_xp_filterset's subclasses. */
    protected $filters;


    public function __construct() {
        $this->filters = array();
        $this->load();
    }

    /**
     * Factory method that creates a subclass of block_xp_filter.
     *
     * @return block_xp_filter
     */
    protected abstract function create_filter();

    /**
     * Loads block_xp_filter array ($this->filters) from DB.
     *
     * @return void
     */
    protected abstract function load();

    /**
     * Return the points filtered for this event.
     *
     * @param \core\event\base $event The event.
     * @return int points.
     */
    public function get_points_for_event(\core\event\base $event) {
        foreach ($this->get() as $filter) {
            if ($filter->match($event)) {
                return $filter->get_points();
            }
        }
        throw new coding_exception('The event did not match any filter.');
    }

    /**
     * Save all current filters to DB.
     *
     */
    public function save() {
        foreach ($this->filters as $filter) {
            $filter->save();
        }
    }

    /**
     * Append a filterset to the current filterset.
     *
     * @param block_xp_filterset $filters
     */
    public function append(block_xp_filterset $filters) {
        foreach ($filters->get() as $filter) {
            $this->add_last($filter);
        }
        $this->save();
    }

    /**
     * Substitute current filterset for the passed one.
     *
     * @param block_xp_filterset $filters
     */
    public function import(block_xp_filterset $filters) {
        $this->delete_all();
        $this->append($filters);
    }

    /*
     * Delete all filters from database.
     *
     */
    public function delete_all() {

       foreach ($this->filters as $filter) {
           $filter->delete();
       }

       $this->clean();
    }

    /**
     * Add filter in the last position of the filterset.
     *
     * @param block_xp_filter $filter
     */
    public function add_last(block_xp_filter $filter) {
        $clonedfilter = $this->create_filter();
        $clonedfilter->load_as_new($filter);
        $this->filters[] = $clonedfilter;
        $this->update_sortorder();
    }

    /**
     * Add filter in the first position of the filterset.
     *
     * @param block_xp_filter $filter
     */
    public function add_first(block_xp_filter $filter) {
        $clonedfilter = $this->create_filter();
        $clonedfilter->load_as_new($filter);
        $this->add($clonedfilter, 0);
    }

    /**
     * Add filter in the specified position of the filterset.
     *
     * @param block_xp_filter $filter
     * @param int $position
     */
    public function add($filter, $position) {
        $clonedfilter = $this->create_filter();
        $clonedfilter->load_as_new($filter);
        $position = min($position, $this->count()+1);
        array_splice( $this->filters, $position, 0, [$clonedfilter] );
        $this->update_sortorder();
    }

    /**
     * Update sortorder property of filters based on current array position
     *
     */
    public function update_sortorder() {
        $sortorder = 0;

        foreach ($this->filters as $filter) {

            $filter->sortorder = $sortorder;
            $sortorder++;
        }
    }

    /**
     * check if filterset is empty.
     *
     * @return bool
     */
    public function empty() {
        return empty($this->filters);
    }

    /**
     * Delete all filters from current filterset object, not from DB.
     *
     */
    protected function clean() {
        unset($this->filters);
        $this->filters = array();
    }

    /**
     * Return number of filters currently loaded.
     *
     * @return int
     */
    public function count() {
        return count($this->filters);
    }

    /**
     * Return array of loaded filters.
     *
     * @return block_xp_filter[]
     */
    public function get() {
        return $this->filters;
    }
}