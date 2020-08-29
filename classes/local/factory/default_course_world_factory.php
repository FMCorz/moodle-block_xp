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
 * Course world factory.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\factory;
defined('MOODLE_INTERNAL') || die();

use moodle_database;
use block_xp\local\config\config;
use block_xp\local\config\config_stack;
use block_xp\local\config\course_world_config;
use block_xp\local\config\filtered_config;
use block_xp\local\config\immutable_config;

/**
 * Course world factory.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_course_world_factory implements course_world_factory {

    /** @var config The admin config. */
    protected $adminconfig;
    /** @var config The config overrides. */
    protected $configoverrides;
    /** @var moodle_database The DB. */
    protected $db;
    /** @var bool For the whole site? */
    protected $forwholesite = false;
    /** @var badge_url_resolver_course_world_factory Resolver. */
    protected $urlresolverfactory;
    /** @var course_world[] World cache. */
    protected $worlds = [];

    /**
     * Constructor.
     *
     * @param config $adminconfig The admin config.
     * @param moodle_database $db The DB.
     */
    public function __construct(config $adminconfig, moodle_database $db,
            badge_url_resolver_course_world_factory $urlresolverfactory, config $adminconfiglocked) {
        $this->adminconfig = $adminconfig;
        $this->db = $db;
        $this->urlresolverfactory = $urlresolverfactory;
        if ($adminconfig->get('context') == CONTEXT_SYSTEM) {
            $this->forwholesite = true;
        }

        // The overrides for a course config are based on the admin settings, for those admin settings that have
        // had their locked status set to true. The whole is immutable to prevent writes on the admin settings.
        $this->configoverrides = new immutable_config(
            new filtered_config($this->adminconfig, array_keys(array_filter($adminconfiglocked->get_all())))
        );
    }

    /**
     * Get the world.
     *
     * @param int $courseid Course ID.
     * @return block_xp\local\course_world
     */
    public function get_world($courseid) {

        // When the block was set up for the whole site we attach it to the site course.
        if ($this->forwholesite) {
            $courseid = SITEID;
        }

        $courseid = intval($courseid);
        if (!isset($this->worlds[$courseid])) {

            $courseconfig = new course_world_config($this->adminconfig, $this->db, $courseid);
            $config = new config_stack([$this->configoverrides, $courseconfig]);

            $this->worlds[$courseid] = new \block_xp\local\course_world($config, $this->db, $courseid, $this->urlresolverfactory);
        }
        return $this->worlds[$courseid];
    }

}
