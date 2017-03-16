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
 * Block XP filter course class.
 *
 * @package    block_xp_filter_course
 * @copyright  2017 Ruben Cancho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_xp_filter_course extends block_xp_filter {

    public function __construct($courseid) {
        $this->courseid = $courseid;
    }

    /**
     *  Loads object properties from array, stdClass or block_xp_filter.
     *
     *
     * @param block_xp_filter|array $filter
     */
    public function load($object) {
        //$object = (is_array($object)) ? (object)$object : $object;
        $tempcourseid = $this->courseid;
        $this->load_from_data2($object);
        $this->courseid = $tempcourseid;
//         foreach ($this as $key => $value) {
//             if ($key != "courseid") {
//                 if (isset($object->$key)) {
//                     $this->$key = $object->$key;
//                 }
//             }
//         }
    }

    /**
     * As load but always create a new object in DB.
     *
     * @param block_xp_filter|array $filter
     */
    public function load_as_new($object) {
        $this->load($object);
        $this->id = null;
    }

    public function save() {
        // TODO: check null.
        $record = (object) array(
                'id' => $this->id,
                'courseid' => $this->courseid,
                'ruledata' => $this->ruledata,
                'points' => $this->points,
                'sortorder' => $this->sortorder,
        );

        $this->insert_or_update('block_xp_filters', $record);
    }

}