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

    /** @var block_xp_filter_manager Cache of the manager. */
    protected $filtermanager;

    /** @var array Cache of levels and their required XP. */
    protected $levels;

    /** @var bool Whether or not to trigger events, for instance when the user levels up. */
    protected $triggereevents = true;

    /**
     * Constructor.
     *
     * @param int $courseid The course ID.
     * @return void
     */
    protected function __construct($courseid) {
        global $CFG;

        $courseid = intval($courseid);
        if ($CFG->block_xp_context == CONTEXT_SYSTEM && $courseid != SITEID) {
            throw new coding_exception('Unexpected course ID.');
        }

        if ($courseid == SITEID) {
            $this->context = context_system::instance();
        } else {
            $this->context = context_course::instance($courseid);
        }

        $this->courseid = $courseid;
    }

    /**
     * Check wether or not the user can capture this event.
     *
     * This method is there to prevent a user from refreshing a page
     * 200x times to get more experience points. For simplicity, and performance
     * reason, this does not handle multiple sessions at the same time.
     *
     * It also prevents a user from opening too many pages at the same time
     * by limiting the number of events for a given time. This might potentially lead
     * to ignoring some events in legit situations if the user is quick.
     *
     * This method has not been designed to check if the user has capabilities
     * to capture the event or not, those checks should be done in the observer
     * for performance reasons.
     *
     * @return bool True when the event is OK.
     */
    protected function can_capture_event(\core\event\base $event) {
        global $SESSION;

        $now = time();
        $maxcount = 64;
        $maxactions = $this->get_config('maxactionspertime');
        $maxtime = $this->get_config('timeformaxactions');

        $actiontime = $this->get_config('timebetweensameactions');
        $actionkey = $event->eventname . ':' . $event->contextid . ':' . $event->objectid . ':' . $event->relateduserid;

        // Init the session variable.
        if (!isset($SESSION->block_xp_cheatguard)) {
            $SESSION->block_xp_cheatguard = array();
        }

        // Actions per time.
        if (count($SESSION->block_xp_cheatguard) > $maxactions) {
            $actions = array_reverse($SESSION->block_xp_cheatguard, true);
            $count = 0;
            foreach ($actions as $action => $time) {
                $count++;
                if ($count > $maxactions && $time > $now - $actiontime) {
                    // Too many actions within $actiontime.
                    return false;
                }
            }
        }

        if (isset($SESSION->block_xp_cheatguard[$actionkey])) {
            if ($SESSION->block_xp_cheatguard[$actionkey] > $now - $actiontime) {
                // The key was found and the time has not expired, cheater spotted.
                return false;
            }
        }

        // Unset the value to re-add it at the end of the array.
        unset($SESSION->block_xp_cheatguard[$actionkey]);

        // Log the time at which this event happened.
        $SESSION->block_xp_cheatguard[$actionkey] = time();

        // Limit the array of events to $maxcount, we do not want to flood the session for no reason.
        $SESSION->block_xp_cheatguard = array_slice($SESSION->block_xp_cheatguard, -$maxcount, null, true);
        return true;
    }

    /**
     * Returns whether or not the current user can manage.
     *
     * @return bool
     */
    public function can_manage() {
        return has_capability('block/xp:addinstance', $this->context);
    }

    /**
     * Returns whether or not the current user can view the block and its pages.
     *
     * @return bool
     */
    public function can_view() {
        return has_capability('block/xp:view', $this->context) || $this->can_manage();
    }

    /**
     * Returns whether or not the current user can view the infos page.
     *
     * @return bool
     */
    public function can_view_infos_page() {
        if (!$this->get_config('enableinfos')) {
            return $this->can_manage();
        }
        return $this->can_view();
    }

    /**
     * Returns whether or not the current user can view the infos page.
     *
     * @return bool
     */
    public function can_view_ladder_page() {
        if (!$this->get_config('enableladder')) {
            return $this->can_manage();
        }
        return $this->can_view();
    }


    /**
     * Capture an event.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    public function capture_event(\core\event\base $event) {
        global $CFG, $DB;

        if ($CFG->block_xp_context != CONTEXT_SYSTEM && $event->courseid != $this->courseid) {
            throw new coding_exception('Event course ID does not match block course ID');
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
     * The courseid parameter will be ignored when the block was set to be used on the system.
     * It is easier to do it this way than handling the course ID paramter from everywhere we
     * need to get the manager.
     *
     * @param int $courseid The course ID.
     * @param bool $forcereload Force the reload of the singleton, to invalidate local cache.
     * @return block_xp_manager The instance of the manager.
     */
    public static function get($courseid, $forcereload = false) {
        global $CFG;

        // When the block was set up for the whole site we attach it to the site course.
        if ($CFG->block_xp_context == CONTEXT_SYSTEM) {
            $courseid = SITEID;
        }

        $courseid = intval($courseid);
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
     * Get the context related to the manager.
     *
     * The context and course IDs here can be a bit confusing. When the plugin is set to act in
     * courses ($CFG->block_xp_context == CONTEXT_COURSE), then the context of the manager
     * should be the course context. However, when block_xp_progress is set to CONTEXT_SYSTEM
     * then we have to rely on the system context, not the context of the site course. The reason
     * behind this is that we need to get the context parent of where we are going to use the block
     * and in this case the site/front page context is nto the right one as it's a child of the
     * system context, and not parent of the courses.
     *
     * Also read the nodes of {@link self::get_courseid()}.
     *
     * @return context
     */
    public function get_context() {
        return $this->context;
    }

    /**
     * Returns the current course object.
     *
     * The purpose of this is to provide an efficient way to retrieve the current course.
     *
     * /!\ The course object MUST NOT be modified!
     *
     * @return object Course record.
     */
    public function get_course() {
        global $DB, $PAGE;
        if (!isset($this->course)) {
            $this->course = get_course($this->courseid, false);
        }
        return $this->course;
    }

    /**
     * Return the current course ID.
     *
     * When the block is set to act on the whole site ($CFG->block_xp_context == CONTEXT_SYSTEM),
     * then we will use the SITEID as course ID. We cannot use 0 because there are some logic here
     * and there that assumes that the course exists. In any other scenario we use the course ID
     * of the course the block was added to.
     *
     * Also read the nodes of {@link self::get_context()}.
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
     * @param stdClass $record The prefetched record, if any.
     * @return block_xp_progress The progress renderable.
     */
    public function get_progress_for_user($userid, stdClass $record = null) {
        global $DB;

        if (!$record) {
            $record = $DB->get_record('block_xp', array('courseid' => $this->courseid, 'userid' => $userid));
        }

        if (!$record) {
            $record = new stdClass();
            $record->xp = 0;
            $record->lvl = 1;
            $record->userid = $userid;
            $record->courseid = $this->courseid;
        }

        // Manipulation.
        $record->contextid = $this->context->id;
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
     * Check if the user has levelled up since the last time we reset the status.
     *
     * See {@link self::update_user_level()} for when the flag is set.
     *
     * @param int $userid The user that may have levelled up.
     * @param boolean $reset The reset flag, when true the levelled up flag will be reset.
     * @return boolean
     */
    public function has_levelled_up($userid, $reset = true) {
        $prefkey = self::USERPREF_NOTIFY . $this->courseid;
        $levelledup = get_user_preferences($prefkey, false, $userid);
        if ($levelledup && $reset) {
            unset_user_preference($prefkey);
        }
        return $levelledup;
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
            return;
        } else {
            // The cron is set to run only once a day, so no need to test the last time it was purged here.
            $DB->delete_records_select('block_xp_log', 'time < :time', array(
                'time' => time() - ($keeplogs * DAYSECS)
            ));
        }
    }

    /**
     * Clears the static caches of this class.
     *
     * Usage reserved to PHP Unit.
     *
     * @return void
     */
    public static function purge_static_caches() {
        if (!PHPUNIT_TEST) {
            return;
        }
        self::$instances = array();
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
     * @param int $groupid The group ID.
     * @return void
     */
    public function reset_data($groupid = 0) {
        global $DB;

        // Delete XP records.
        $sql = "DELETE FROM {block_xp} WHERE courseid = :courseid";
        $params = array('courseid' => $this->courseid);
        if ($groupid) {
            $sql .= " AND userid IN
                  (SELECT gm.userid
                     FROM {groups_members} gm
                    WHERE gm.groupid = :groupid)";
            $params['groupid'] = $groupid;
        }
        $DB->execute($sql, $params);

        // Delete logs.
        $sql = "DELETE FROM {block_xp_log} WHERE courseid = :courseid";
        $params = array('courseid' => $this->courseid);
        if ($groupid) {
            $sql .= " AND userid IN
                  (SELECT gm.userid
                     FROM {groups_members} gm
                    WHERE gm.groupid = :groupid)";
            $params['groupid'] = $groupid;
        }
        $DB->execute($sql, $params);
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
                    'context' => $this->context,
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
            set_user_preference(self::USERPREF_NOTIFY . $this->courseid, 1, $userid);
        }
    }

}
