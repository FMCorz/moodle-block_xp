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
 * Course world config.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\config;
defined('MOODLE_INTERNAL') || die();

use coding_exception;
use moodle_database;
use stdClass;

/**
 * Course world config.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_world_config implements config {

    /** No ranking. */
    const RANK_OFF = 0;
    /** Ranking enabled. */
    const RANK_ON = 1;
    /** Relative ranking. Difference in XP between row and point of reference. */
    const RANK_REL = 2;
    /** Hide identity. */
    const IDENTITY_OFF = 0;
    /** Identity displayed. */
    const IDENTITY_ON = 1;

    /** When there is nothing to do about the default filters. */
    const DEFAULT_FILTERS_NOOP = 0;
    /** When the default filters are static, and non-editable. This is a legacy state for spotting v2.x. */
    const DEFAULT_FILTERS_STATIC = 1;
    /** When the defaults filters have not yet been added. */
    const DEFAULT_FILTERS_MISSING = 2;

    /** @var array Config defaults. */
    protected static $configdefaults = array(
        'enabled' => false,
        'enablelog' => 1,
        'keeplogs' => 3,
        'enablecheatguard' => true,   // Enable cheat guard.
        'enableladder' => true,       // Enable the ladder.
        'enableinfos' => true,        // Enable the infos page.
        'levels' => 0,                // Not used any more.
        'levelsdata' => '',           // JSON encoded value of the levels data.
        'enablelevelupnotif' => true, // Enable the level up notification.
        'enablecustomlevelbadges' => false,  // Enable the usage of custom level badges.
        'maxactionspertime' => 10,           // Max actions during timepermaxactions.
        'timeformaxactions' => 60,           // Time during which max actions cannot be reached.
        'timebetweensameactions' => 180,     // Time between similar actions.
        'identitymode' => self::IDENTITY_ON, // Identity mode.
        'rankmode' => self::RANK_ON,         // Rank mode.
        'neighbours' => 0,                   // Number of neighbours to show on ladder, 0 means everyone.
        'defaultfilters' => self::DEFAULT_FILTERS_MISSING  // Flag about the default filters.
    );

    /** @var config The proxied config object. */
    protected $store;

    /**
     * Constructor.
     *
     * @param moodle_database $db The DB.
     * @param int $courseid The course ID.
     */
    public function __construct(moodle_database $db, $courseid) {
        $this->store = new \block_xp\local\config\table_row_config($db, 'block_xp_config',
            static::$configdefaults, ['courseid' => $courseid]);
    }

    /**
     * Get a value.
     *
     * @param string $name The name.
     * @return mixed
     */
    public function get($name) {
        return $this->store->get($name);
    }

    /**
     * Get all config.
     *
     * @return array
     */
    public function get_all() {
        return $this->store->get_all();
    }

    /**
     * Set a value.
     *
     * @param string $name Name of the config.
     * @param mixed $value The value.
     */
    public function set($name, $value) {
        return $this->store->set($name, $value);
    }

    /**
     * Set many.
     *
     * @param array $values Keys are config names, and values are values.
     * @throws coding_exception When a value is not scalar.
     */
    public function set_many(array $values) {
        return $this->store->set_many($values);
    }
}
