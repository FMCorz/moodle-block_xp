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
 * Block XP manager.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block XP manager class.
 *
 * Empty class kept for compatibility with plugins connecting with it.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or latern
 * @deprecated Since 3.0.0
 */
class block_xp_manager {

    /** Default base for XP algo. */
    const DEFAULT_BASE = 120;

    /** Default coef for XP algo. */
    const DEFAULT_COEF = 1.3;

    /** User preference key storing if we should notify a user for his level up. It should be prepended to the course ID. */
    const USERPREF_NOTIFY = 'block_xp_notify_level_up_';

    /** User preference key storing if they dismissed the like notice */
    const USERPREF_NOTICES = 'block_xp_notices';

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

    /** @var array Array of singletons. */
    protected static $instances;

    /** @var object Cached course object. */
    protected $course = null;

    /** @var int Course ID. */
    protected $courseid = null;

    /** @var object Config. */
    protected $config = null;

    /** @var array Config default. */
    protected static $configdefaults = array(
        'enabled' => false,
        'levels' => 10,
        'enablelog' => 1,
        'keeplogs' => 3,
        'enablecheatguard' => true,   // Enable cheat guard.
        'enableladder' => true,       // Enable the ladder.
        'enableinfos' => true,        // Enable the infos page.
        'levelsdata' => '',           // JSON encoded value of the levels data.
        'enablelevelupnotif' => true, // Enable the level up notification.
        'enablecustomlevelbadges' => false,  // Enable the usage of custom level badges.
        'maxactionspertime' => 10,           // Max actions during timepermaxactions.
        'timeformaxactions' => 60,           // Time during which max actions cannot be reached.
        'timebetweensameactions' => 180,     // Time between similar actions.
        'identitymode' => self::IDENTITY_ON, // Identity mode.
        'rankmode' => self::RANK_ON,         // Rank mode.
        'neighbours' => 0,                   // Number of neighbours to show on ladder, 0 means everyone.
    );

    /** @var context The context related to this manager.*/
    protected $context;

    /**
     * Constructor.
     *
     * @param int $courseid The course ID.
     * @return void
     */
    protected function __construct($courseid) {
        $this->context = context_system::instance();
        $this->courseid = SITEID;
    }

    /**
     * Check wether or not the user can capture this event.
     *
     * @return bool True when the event is OK.
     */
    protected function can_capture_event(\core\event\base $event) {
        return false;
    }

    /**
     * Returns whether or not the current user can manage.
     *
     * @return bool
     */
    public function can_manage() {
        return false;
    }

    /**
     * Returns whether or not the current user can view the block and its pages.
     *
     * @return bool
     */
    public function can_view() {
        return false;
    }

    /**
     * Returns whether or not the current user can view the infos page.
     *
     * @return bool
     */
    public function can_view_infos_page() {
        return false;
    }

    /**
     * Returns whether or not the current user can view the infos page.
     *
     * @return bool
     */
    public function can_view_ladder_page() {
        return false;
    }


    /**
     * Capture an event.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    public function capture_event(\core\event\base $event) {
    }

    /**
     * Get an instance of the manager.
     *
     * The courseid parameter will be ignored when the block was set to be used on the system.
     * It is easier to do it this way than handling the course ID paramter from everywhere we
     * need to get the manager.
     *
     * @param int $courseid The course ID.
     * @param bool $forcereload Force the reload of the singleton, to invalidate local cache.
     * @return block_xp_manager The instance of the manager.
     */
    public static function get($courseid, $forcereload = false) {
        return new block_xp_manager($courseid);
    }

    /**
     * Get the configuration.
     *
     * @param string $name The config to get.
     * @return mixed Return either an object, or a value.
     */
    public function get_config($name = null) {
        if (empty($this->config)) {
            $record = (object) self::$configdefaults;
            $record->id = 0;
            $record->courseid = $this->courseid;
            $this->config = $record;
        }
        if ($name !== null) {
            return $this->config->$name;
        }
        return $this->config;
    }

    /**
     * Get the context related to the manager.
     *
     * @return context
     */
    public function get_context() {
        return $this->context;
    }

    /**
     * Returns the current course object.
     *
     * @return object Course record.
     */
    public function get_course() {
        return get_course($this->courseid, false);
    }

    /**
     * Return the current course ID.
     *
     * @return int The course ID.
     */
    public function get_courseid() {
        return $this->courseid;
    }

    /**
     * Return the default configuration.
     *
     * @return stdClass Default config.
     */
    public static function get_default_config() {
        return (object) self::$configdefaults;
    }

    /**
     * Get the filter manager.
     *
     * @return block_xp_filter_manager
     */
    public function get_filter_manager() {
        return new block_xp_filter_manager($this);
    }

    /**
     * Return the number of levels in the course.
     *
     * @return int Level count.
     */
    public function get_level_count() {
        return 1;
    }

    /**
     * Return the level at which we are at $xp.
     *
     * @param int $xp XP acquired.
     * @return int The level.
     */
    public function get_level_from_xp($xp) {
        return 1;
    }

    /**
     * Get the levels and the experience points needed.
     *
     * @return array level => xp required.
     */
    public function get_levels() {
        return [1 => 0];
    }

    /**
     * Get the levels data.
     *
     * @return array of levels data.
     */
    public function get_levels_data() {
        return array(
            'usealgo' => 1,
            'base' => self::DEFAULT_BASE,
            'coef' => self::DEFAULT_COEF,
            'xp' => [1 => 0],
            'desc' => array()
        );
    }

    /**
     * Get the levels and their XP based on a simple algorithm.
     *
     * @param int $levelcount The number of levels.
     * @param int $base The base XP required.
     * @param float $coef The coefficient between levels.
     * @return array level => xp required.
     */
    public static function get_levels_with_algo($levelcount, $base = self::DEFAULT_BASE, $coef = self::DEFAULT_COEF) {
        return [1 => 0];
    }

    /**
     * Get progress renderable of user.
     *
     * @param int $userid The user ID.
     * @param stdClass $record The prefetched record, if any.
     * @return block_xp_progress The progress renderable.
     */
    public function get_progress_for_user($userid, stdClass $record = null) {
        $record = new stdClass();
        $record->level = 1;
        $record->xp = 0;
        $record->userid = $userid;
        $record->courseid = $this->courseid;
        $record->contextid = $this->context->id;
        $record->levelxp = 0;
        $record->nextlevelxp = false;
        $params = (array) $record;
        $progress = new block_xp_progress($params);
        return $progress;
    }

    /**
     * Get the experience points generated by an event.
     *
     * @param \core\event\base $event The event.
     * @return int XP points.
     */
    public function get_xp_from_event(\core\event\base $event) {
        return 0;
    }

    /**
     * Get the amount of XP required for a level.
     *
     * @param int $level The level.
     * @return int|false The amount of XP required, or false there is no such level.
     */
    public function get_xp_for_level($level) {
        return false;
    }

    /**
     * Check if the user has levelled up since the last time we reset the status.
     *
     * @param int $userid The user that may have levelled up.
     * @param boolean $reset The reset flag, when true the levelled up flag will be reset.
     * @return boolean
     */
    public function has_levelled_up($userid, $reset = true) {
    }

    /**
     * Is the block enabled on the course?
     *
     * @return boolean True if enabled.
     */
    public function is_enabled() {
        return false;
    }

    /**
     * Purge the logs according to preferences.
     *
     * @return void
     */
    public function purge_log() {
    }

    /**
     * Clears the static caches of this class.
     *
     * Usage reserved to PHP Unit.
     *
     * @return void
     */
    public static function purge_static_caches() {
    }

    /**
     * Recalculte the levels of all the users in the course.
     *
     * @param int $courseid The course ID.
     * @return void
     */
    public function recalculate_levels() {
    }

    /**
     * Reset all the data.
     *
     * @param int $groupid The group ID.
     * @return void
     */
    public function reset_data($groupid = 0) {
    }

    /**
     * Reset the XP of a user to something.
     *
     * @param int $userid The user ID.
     * @param int $xp The amount of XP.
     * @return void
     */
    public function reset_user_xp($userid, $xp = 0) {
    }

    /**
     * Update the configuration.
     *
     * @param stdClass $data An object containing the data.
     * @return void
     */
    public function update_config($data) {
    }

    /**
     * Update the level of a user.
     *
     * The option parameters are only there for a speed gain, they should be equal
     * to the value stored in the database.
     *
     * @param int $userid The user ID.
     * @param int $xp The user XP.
     * @param int $lvl The known user level.
     * @return void
     */
    public function update_user_level($userid, $xp = null, $lvl = null) {
    }

}
