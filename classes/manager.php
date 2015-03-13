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
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_manager {

    /** Default base for XP algo. */
    const DEFAULT_BASE = 120;

    /** Default coef for XP algo. */
    const DEFAULT_COEF = 1.3;

    /** User preference key storing if we should notify a user for his level up */
    const USERPREF_NOTIFY = 'block_xp_notify_level_up';

    /** @var array Array of singletons. */
    protected static $instances;

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
        'enableladder' => true,       // Enable the ladder.
        'enableinfos' => true,        // Enable the infos page.
        'levelsdata' => '',           // JSON encoded value of the levels data.
        'enablelevelupnotif' => true, // Enable the level up notification.
        'enablecustomlevelbadges' => false,  // Enable the usage of custom level badges.
    );

    /** @var block_xp_filter_manager Cache of the manager. */
    protected $filtermanager;

    /** @var array Cache of levels and their required XP. */
    protected $levels;

    /** @var bool Whether or not to trigger events, for instance when the user levels up. */
    protected $triggereevents = true;

    /**
     * Constructor
     *
     * @param int $courseid The course ID.
     * @return void
     */
    protected function __construct($courseid) {
        $this->courseid = $courseid;
    }

    /**
     * Check wether or not the user can capture this event.
     *
     * This method is there to prevent a user from refreshing a page
     * 200x times to get more experience points. For simplicity, and performance
     * reason, this does not handle multiple sessions at the same time.
     *
     * This method has not been designed to check if the user has capabilities
     * to capture the event or not, those checks should be done in the observer
     * for performance reasons.
     *
     * @return bool True when the event is OK.
     */
    protected function can_capture_event(\core\event\base $event) {
        global $SESSION;

        // Init the session variable.
        if (!isset($SESSION->block_xp_buffer)) {
            $SESSION->block_xp_buffer = array();
        }

        // Check if the user is trying to trick the system by reloading a page.
        $key = $event->eventname . ':' . $event->contextid . ':' . $event->userid;
        if (isset($SESSION->block_xp_buffer[$key])) {
            if ($SESSION->block_xp_buffer[$key] > time() - 30) {
                // A very similar event occured less than 30 seconds ago, we will ignore it.
                return false;
            }
            // Unset the value to re-add it at the end of the array.
            unset($SESSION->block_xp_buffer[$key]);
        }

        // Log the time at which this event happened.
        $SESSION->block_xp_buffer[$key] = time();

        // Limit the array of events to 10, we do not want to flood the session for no reason.
        $SESSION->block_xp_buffer = array_slice($SESSION->block_xp_buffer, -10, null, true);
        return true;
    }

    /**
     * Capture an event.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    public function capture_event(\core\event\base $event) {
        global $DB;

        if ($event->courseid !== $this->courseid) {
            throw new coding_exception('Event course ID does not match event course ID');
        }

        // The capture has not been enabled yet.
        if (!$this->is_enabled()) {
            return;
        }

        // Check if the user can capture this event, anti cheater method.
        if (!$this->can_capture_event($event)) {
            return;
        }

        $userid = $event->userid;
        $points = $this->get_xp_from_event($event);

        // No need to go through the following if the user did not gain XP.
        if ($points > 0) {
            if ($DB->count_records('block_xp', array('courseid' => $this->courseid, 'userid' => $userid)) > 0) {
                $DB->execute('UPDATE {block_xp} SET xp = xp + :xp WHERE courseid = :courseid AND userid = :userid',
                    array('xp' => $points, 'courseid' => $this->courseid, 'userid' => $userid));
            } else {
                $record = new stdClass();
                $record->courseid = $this->courseid;
                $record->userid = $userid;
                $record->xp = $points;
                $DB->insert_record('block_xp', $record);
            }
            $this->update_user_level($userid);
        }

        // Log the event.
        $this->log_event($event->eventname, $userid, $points);
    }

    /**
     * Get an instance of the manager.
     *
     * @param int $courseid The course ID.
     * @param bool $forcereload Force the reload of the singleton, to invalidate local cache.
     * @return block_xp_manager The instance of the manager.
     */
    public static function get($courseid, $forcereload = false) {
        if ($forcereload || !isset(self::$instances[$courseid])) {
            self::$instances[$courseid] = new block_xp_manager($courseid);
        }
        return self::$instances[$courseid];
    }

    /**
     * Get the configuration.
     *
     * @param string $name The config to get.
     * @return mixed Return either an object, or a value.
     */
    public function get_config($name = null) {
        global $DB;
        if (empty($this->config)) {
            $record = $DB->get_record('block_xp_config', array('courseid' => $this->courseid));
            if (!$record) {
                $record = (object) self::$configdefaults;
                $record->id = 0;
                $record->courseid = $this->courseid;
            }
            $this->config = $record;
        }
        if ($name !== null) {
            return $this->config->$name;
        }
        return $this->config;
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
        if (!$this->filtermanager) {
            $this->filtermanager = new block_xp_filter_manager($this);
        }
        return $this->filtermanager;
    }

    /**
     * Return the number of levels in the course.
     *
     * @return int Level count.
     */
    public function get_level_count() {
        return $this->get_config('levels');
    }

    /**
     * Return the level at which we are at $xp.
     *
     * @param int $xp XP acquired.
     * @return int The level.
     */
    public function get_level_from_xp($xp) {
        $levels = $this->get_levels();

        $level = 1;
        for ($i = $level; $i <= count($levels); $i++) {
            if ($levels[$i] <= $xp) {
                $level = $i;
            } else {
                break;
            }
        }

        return $level;
    }

    /**
     * Get the levels and the experience points needed.
     *
     * @return array level => xp required.
     */
    public function get_levels() {
        if (empty($this->levels)) {
            $eventsdata = $this->get_levels_data('levelsdata');
            $this->levels = $eventsdata['xp'];
        }
        return $this->levels;
    }

    /**
     * Get the levels data.
     *
     * @return array of levels data.
     */
    public function get_levels_data() {
        $levelsdata = $this->get_config('levelsdata');
        if ($levelsdata) {
            $levelsdata = json_decode($levelsdata, true);
            if ($levelsdata) {
                return $levelsdata;
            }
        }

        return array(
            'usealgo' => 1,
            'base' => block_xp_manager::DEFAULT_BASE,
            'coef' => block_xp_manager::DEFAULT_COEF,
            'xp' => self::get_levels_with_algo($this->get_level_count()),
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
        $list = array();
        for ($i = 1; $i <= $levelcount; $i++) {
            if ($i == 1) {
                $list[$i] = 0;
            } else if ($i == 2) {
                $list[$i] = $base;
            } else {
                $list[$i] = $base + round($list[$i - 1] * $coef);
            }
        }
        return $list;
    }

    /**
     * Get progress renderable of user.
     *
     * @param int $userid The user ID.
     * @return block_xp_progress The progress renderable.
     */
    public function get_progress_for_user($userid) {
        global $DB;
        $record = $DB->get_record('block_xp', array('courseid' => $this->courseid, 'userid' => $userid));
        if (!$record) {
            $record = new stdClass();
            $record->xp = 0;
            $record->lvl = 1;
            $record->userid = $userid;
            $record->courseid = $this->courseid;
        }

        // Manipulation.
        unset($record->id);
        $record->level = $record->lvl;
        unset($record->lvl);
        $params = (array) $record;

        $params['levelxp'] = $this->get_xp_for_level($record->level);
        $params['nextlevelxp'] = $this->get_xp_for_level($record->level + 1);
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
        $fm = $this->get_filter_manager();
        return $fm->get_points_for_event($event);
    }

    /**
     * Get the amount of XP required for a level.
     *
     * @param int $level The level.
     * @return int|false The amount of XP required, or false there is no such level.
     */
    public function get_xp_for_level($level) {
        $levels = $this->get_levels();
        if (isset($levels[$level])) {
            return $levels[$level];
        }
        return false;
    }

    /**
     * Is the block enabled on the course?
     *
     * @return boolean True if enabled.
     */
    public function is_enabled() {
        return $this->get_config('enabled');
    }

    /**
     * Log a captured event.
     *
     * @param string $eventname The event name.
     * @param int $userid The user ID.
     * @param int $xp The XP earned with that event.
     * @return void
     */
    protected function log_event($eventname, $userid, $xp) {
        global $DB;

        if ($this->get_config('enablelog')) {
            $record = new stdClass();
            $record->courseid = $this->courseid;
            $record->userid = $userid;
            $record->eventname = $eventname;
            $record->xp = $xp;
            $record->time = time();
            try {
                $DB->insert_record('block_xp_log', $record);
            } catch (dml_exception $e) {
                // Ignore.
            }
        }
    }

    /**
     * Purge the logs according to preferences.
     *
     * @return void
     */
    public function purge_log() {
        global $DB;
        $keeplogs = $this->get_config('keeplogs');
        if (!$keeplogs) {
            continue;
        } else {
            // The cron is set to run only once a day, so no need to test the last time it was purged here.
            $DB->delete_records_select('block_xp_log', 'time < :time', array(
                'time' => time() - ($keeplogs * DAYSECS)
            ));
        }
    }

    /**
     * Recalculte the levels of all the users in the course.
     *
     * @param int $courseid The course ID.
     * @return void
     */
    public function recalculate_levels() {
        global $DB;
        $users = $DB->get_recordset('block_xp', array('courseid' => $this->courseid), '', 'userid, lvl, xp');

        // Disable events.
        $oldtriggerevents = $this->triggereevents;
        $this->triggereevents = false;

        foreach ($users as $user) {
            $this->update_user_level($user->userid, $user->xp, $user->lvl);
        }

        // Restore value.
        $this->triggereevents = $oldtriggerevents;

        $users->close();
    }

    /**
     * Reset all the data.
     *
     * @return void
     */
    public function reset_data() {
        global $DB;
        $DB->delete_records('block_xp', array('courseid' => $this->courseid));
        $DB->delete_records('block_xp_log', array('courseid' => $this->courseid));
    }

    /**
     * Reset the XP of a user to something.
     *
     * This will automatically recalculate the user's level, but will
     * not trigger an event in case of level up or down.
     *
     * @param int $userid The user ID.
     * @param int $xp The amount of XP.
     * @return void
     */
    public function reset_user_xp($userid, $xp = 0) {
        global $DB;

        if ($record = $DB->get_record('block_xp', array('courseid' => $this->courseid, 'userid' => $userid))) {
            $record->xp = $xp;
            $DB->update_record('block_xp', $record);
        } else {
            $record = new stdClass();
            $record->courseid = $this->courseid;
            $record->userid = $userid;
            $record->xp = $xp;
            $record->lvl = 1;
            $DB->insert_record('block_xp', $record);
        }

        $oldtriggerevents = $this->triggereevents;
        $this->triggereevents = false;
        $this->update_user_level($userid, $record->xp, $record->lvl);
        $this->triggereevents = $oldtriggerevents;
    }

    /**
     * Update the configuration.
     *
     * @param stdClass $data An object containing the data.
     * @return void
     */
    public function update_config($data) {
        global $DB;
        $config = $this->get_config();
        foreach ((array) $data as $key => $value) {
            if (in_array($key, array('id', 'courseid'))) {
                continue;
            } elseif (property_exists($config, $key)) {
                if (in_array($key, array('levelsdata'))) {
                    // Some keys needs to be JSON encoded.
                    $value = json_encode($value);
                }
                $config->{$key} = $value;
            }
        }
        if (empty($config->id)) {
            $config->id = $DB->insert_record('block_xp_config', $config);
        } else {
            $DB->update_record('block_xp_config', $config);
        }
        $this->config = $config;
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
        global $DB;

        if ($xp === null || $lvl === null) {
            $record = $DB->get_record('block_xp', array('courseid' => $this->courseid, 'userid' => $userid), 'xp,lvl');
            if (!$record) {
                return;
            }
            $xp = $record->xp;
            $lvl = $record->lvl;
        }

        $level = $this->get_level_from_xp($xp);
        if ($level != $lvl) {
            // Level up!
            $DB->set_field('block_xp', 'lvl', $level, array('courseid' => $this->courseid, 'userid' => $userid));
            if ($this->triggereevents) {
                $params = array(
                    'context' => context_course::instance($this->courseid),
                    'relateduserid' => $userid,
                    'other' => array(
                        'level' => $level
                    )
                );
                $event = \block_xp\event\user_leveledup::create($params);
                $event->trigger();
            }
        }

        if ($level > $lvl && $this->get_config('enablelevelupnotif')) {
            // Level up, and we want to notify the user.
            set_user_preference(self::USERPREF_NOTIFY, 1, $userid);
        }
    }

}
