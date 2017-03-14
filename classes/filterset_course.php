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
 * Block XP course filters class.
 *
 * @package    block_xp_filterset_course
 * @copyright  2017 Ruben Cancho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_xp_filterset_course extends block_xp_filterset {

    protected $courseid;

    public function __construct($id) {
        $this->courseid = $id;

        parent::__construct();
    }

    public function load() {
        global $DB;

        $records = $DB->get_recordset('block_xp_filters',
                array('courseid' => $this->courseid));

        $this->clean();

        foreach ($records as $key => $filterdata) {
            $filter = $this->create_filter();
            $filter->load($filterdata);
            $this->filters[] = $filter;
        }
    }

    public function create_filter() {
        return new block_xp_filter_course($this->courseid);
    }
}