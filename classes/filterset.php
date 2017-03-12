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

    protected $filters;

    public function __construct() {
        $this->filters = array();
        $this->load();
    }

    protected abstract function create_filter();

    protected abstract function load();

    public function save() {
        foreach($this->filters as $filter) {
            $filter->save();
        }
    }

    public function import(block_xp_filterset $filters_object) {
        foreach($filters_object->get() as $filter) {
            $cloned_filter = $this->create_filter();
            $cloned_filter->load($filter);
            $this->filters[] = $cloned_filter;
        }

        $this->save();
    }

    public function empty() {
        return empty($this->filters);
    }

    public function get() {
        return $this->filters;
    }
}

?>