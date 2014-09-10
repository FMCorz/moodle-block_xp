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
 * Rule property.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Rule property class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_rule_property extends block_xp_rule_base {

    /**
     * The class property to compare against.
     *
     * @var string
     */
    protected $property;

    /**
     * Constructor.
     *
     * Read the parameters as follow:
     *  - Subject must be $compare'd with $value.
     *  - Subject must be equal to $value.
     *  - Subject must be lower than $value.
     *  - Subject must match regex $value.
     *
     * @param string $compare Constant value.
     * @param mixed $value The value.
     */
    public function __construct($compare = self::EQ, $value = '', $property = '') {
        parent::__construct($compare, $value);
        $this->property = $property;
    }

    /**
     * Export the properties and their values.
     *
     * This must return all the values required by the {@link self::create()} method.
     *
     * @return array Keys are properties, values are the values.
     */
    public function export() {
        $properties = parent::export();
        $properties['property'] = $this->property;
        return $properties;
    }

    /**
     * Get the value to use during comparison from the subject.
     *
     * Override this method when the object passed by the user
     * needs to be converted into a suitable value.
     *
     * @param mixed $subject The subject.
     * @return mixed The value to use.
     */
    protected function get_subject_value($subject) {
        return $subject->{$this->property};
    }
}
