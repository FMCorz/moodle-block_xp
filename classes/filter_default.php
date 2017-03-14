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
 * Block XP default filter class.
 *
 * @package    block_xp_filter_default
 * @copyright  2017 Ruben Cancho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_xp_filter_default extends block_xp_filter {

    /**
     * Loads filter data from object except courseid and id.
     *
     * default filters don't have courseid.
     * Not having id means that when saved will always create a new record.
     *
     * @param stdClass|array|block_xp_filter $filter
     */
    public function load($filter) {
        // TODO: check nulls.
        // TODO: validate ruledata to avoid course context.
        $this->id = $filter->id;
        $this->ruledata = $filter->ruledata;
        $this->points = $filter->points;
        $this->sortorder = $filter->sortorder;
    }

    public function save() {
        $record = (object) array(
                'id' => $this->id,
                'courseid' => 0,
                'ruledata' => $this->ruledata,
                'points' => $this->points,
                'sortorder' => $this->sortorder,
        );

        $this->insert_or_update('block_xp_filters', $record);
    }
}