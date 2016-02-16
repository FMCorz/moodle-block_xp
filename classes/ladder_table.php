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
 * Block XP ladder table.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/tablelib.php');

/**
 * Block XP ladder table class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_ladder_table extends table_sql {

    /** @var string The key of the user ID column. */
    public $useridfield = 'userid';

    /** @var block_xp_manager XP Manager. */
    protected $xpmanager = null;

    /** @var block_xp_manager XP Manager. */
    protected $xpoutput = null;

    /** @var int The user ID we're viewing the ladder for. */
    protected $userid;

    /** @var Cache of the user record, use {@link self::get_user_record()}. */
    protected $currentuserrecord;

    /** @var int The identity mode. */
    protected $identitymode = block_xp_manager::IDENTITY_ON;

    /** @var boolean Only show neighbours. */
    protected $neighboursonly = false;

    /** @var boolean When showing neighbours only show n before. */
    protected $neighboursabove = 3;

    /** @var boolean When showing neighbours only show n after. */
    protected $neighboursbelow = 3;

    /** @var int The rank mode. */
    protected $rankmode = block_xp_manager::RANK_ON;

    /** @var int The level we're starting from to compute the rank. */
    protected $startinglevel;

    /** @var int The offset to start with to compute the rank. */
    protected $startingoffset;

    /** @var int The rank to start with to compute the rank. */
    protected $startingrank;

    /** @var int The XP we're counting from to compute the rank. */
    protected $startingxp;

    /** @var int The XP to compare with. Used with RANK_REL. */
    protected $startingxpdiff;

    /** @var array The fields found in the XP table. */
    public static $xpfields = array('id', 'courseid', 'userid', 'xp', 'lvl');

    /**
     * Constructor.
     *
     * @param string $uniqueid Unique ID.
     * @param int $courseid Course ID.
     * @param int $groupid Group ID.
     */
    public function __construct($uniqueid, $courseid, $groupid, array $options = array(), $userid = null) {
        global $PAGE, $USER;
        parent::__construct($uniqueid);

        if (isset($options['rankmode'])) {
            $this->rankmode = $options['rankmode'];
        }
        if (isset($options['neighboursonly'])) {
            $this->neighboursonly = $options['neighboursonly'];
        }
        if (isset($options['neighboursabove'])) {
            $this->neighboursabove = $options['neighboursabove'];
        }
        if (isset($options['neighboursbelow'])) {
            $this->neighboursbelow = $options['neighboursbelow'];
        }
        if (isset($options['identitymode'])) {
            $this->identitymode = $options['identitymode'];
        }

        // The user ID we're viewing the ladder for.
        if ($userid === null) {
            $userid = $USER->id;
        }
        $this->userid = $userid;

        // Block XP stuff.
        $this->xpmanager = block_xp_manager::get($courseid);
        $this->xpoutput = $PAGE->get_renderer('block_xp');

        // Define columns, and headers.
        $columns = array();
        $headers = array();
        if ($this->rankmode != block_xp_manager::RANK_OFF) {
            $columns += array('rank');
            if ($this->rankmode == block_xp_manager::RANK_REL) {
                $headers += array(get_string('difference', 'block_xp'));
            } else {
                $headers += array(get_string('rank', 'block_xp'));
            }
        }
        $columns = array_merge($columns, array(
            'userpic',
            'fullname',
            'lvl',
            'xp',
            'progress'
        ));
        $headers = array_merge($headers, array(
            '',
            get_string('fullname'),
            get_string('level', 'block_xp'),
            get_string('xp', 'block_xp'),
            get_string('progress', 'block_xp'),
        ));
        $this->define_columns($columns);
        $this->define_headers($headers);

        // Define SQL.
        $sqlfrom = '';
        $sqlparams = array();
        if ($groupid) {
            $sqlfrom = '{block_xp} x
                     JOIN {groups_members} gm
                       ON gm.groupid = :groupid
                      AND gm.userid = x.userid
                     JOIN {user} u
                       ON x.userid = u.id';
            $sqlparams = array('groupid' => $groupid);
        } else {
            $sqlfrom = '{block_xp} x JOIN {user} u ON x.userid = u.id';
        }
        $sqlfrom .= " JOIN {context} ctx
                        ON ctx.instanceid = u.id
                       AND ctx.contextlevel = :contextlevel";
        $sqlparams += array('contextlevel' => CONTEXT_USER);

        $this->sql = new stdClass();
        $this->sql->fields = 'x.*, ' .
            user_picture::fields('u', null, 'userid') . ', ' .
            context_helper::get_preload_record_columns_sql('ctx');
        $this->sql->from = $sqlfrom;
        $this->sql->where = 'courseid = :courseid';
        $this->sql->params = array_merge(array('courseid' => $courseid), $sqlparams);

        // Define various table settings.
        $this->sortable(false);
        $this->no_sorting('userpic');
        $this->no_sorting('progress');
        $this->collapsible(false);
    }

    /**
     * Process the data returned by the query.
     *
     * @see self::compute_rank_start()
     * @return void
     */
    function build_table() {
        global $USER;

        $this->compute_rank_start();

        $rank = $this->startingrank;
        $lastlvl = $this->startinglevel;
        $lastxp = $this->startingxp;
        $offset = $this->startingoffset;
        $xptodiff = $this->startingxpdiff;

        if ($this->rawdata) {
            foreach ($this->rawdata as $row) {

                // Preload the context.
                context_helper::preload_from_record($row);

                // Show the real rank.
                if ($this->rankmode == block_xp_manager::RANK_ON) {

                    // If this row is different than the previous one.
                    if ($row->lvl != $lastlvl || $row->xp != $lastxp) {
                        $rank += $offset;
                        $offset = 1;
                        $lastlvl = $row->lvl;
                        $lastxp = $row->xp;
                    } else {
                        $offset++;
                    }
                    $row->rank = $rank;

                // Show a "relative" rank, the difference between a student and another.
                } else if ($this->rankmode == block_xp_manager::RANK_REL) {

                    // There was no indication of what XP to diff with, let's take the first entry.
                    if ($xptodiff == -1 && $lastxp == -1) {
                        $xptodiff = $row->xp;
                    }

                    // The last row does not this one.
                    if ($row->xp != $lastxp) {
                        $rank = $row->xp - $xptodiff;
                        $lastxp = $row->xp;
                    }

                    $row->rank = $rank;
                }

                $classes = ($this->userid == $row->userid) ? 'highlight' : '';
                $formattedrow = $this->format_row($row);
                $this->add_data_keyed($formattedrow, $classes);
            }
        }
    }

    /**
     * Formats the column fullname.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    public function col_fullname($row) {
        if ($this->identitymode == block_xp_manager::IDENTITY_OFF && $row->userid != $this->userid) {
            return get_string('someoneelse', 'block_xp');
        }
        return parent::col_fullname($row);
    }

    /**
     * Formats the column progress.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_progress($row) {
        static $fields = null;
        if ($fields === null) {
            $fields = array_flip(self::$xpfields);
        }

        $record = (object) array_intersect_key((array) $row, $fields);
        $progress = $this->xpmanager->get_progress_for_user($row->userid, $record);
        return $this->xpoutput->progress_bar($progress);
    }

    /**
     * Formats the rank column.
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_rank($row) {
        if ($this->rankmode == block_xp_manager::RANK_REL && $row->rank > 0) {
            return '+' . $row->rank;
        }
        return $row->rank;
    }

    /**
     * Formats the column userpic.
     *
     * @param stdClass $row Table row.
     * @return string Output produced.
     */
    protected function col_userpic($row) {
        global $CFG, $OUTPUT;

        if ($this->identitymode == block_xp_manager::IDENTITY_OFF && $this->userid != $row->userid) {
            static $guestuser = null;
            if ($guestuser === null) {
                $guestuser = guest_user();
            }
            return $OUTPUT->user_picture($guestuser, array('link' => false, 'alttext' => false));
        }

        return $OUTPUT->user_picture(user_picture::unalias($row, null, 'userid'));
    }

    /**
     * Guesses where to start the rank computation.
     *
     * @return void
     */
    protected function compute_rank_start() {
        global $DB;

        $this->startingrank = 0;
        $this->startinglevel = -1;
        $this->startingxp = -1;
        $this->startingoffset = 1;
        $this->startingxpdiff = -1;

        // Guess the starting rank.
        if ($this->rankmode == block_xp_manager::RANK_ON && !empty($this->rawdata)) {
            $record = reset($this->rawdata);
            $sql = "SELECT COUNT(x.id)
                      FROM {$this->sql->from}
                     WHERE {$this->sql->where}
                       AND x.xp > :neighxp";
            $this->startingrank = $DB->count_records_sql($sql, $this->sql->params + array('neighxp' => $record->xp)) + 1;
            $params = $this->sql->params + array(
                'neighid' => $record->id,
                'neighxp' => $record->xp,
                'neighxpeq' => $record->xp
            );
            $sql = "SELECT COUNT(x.id)
                      FROM {$this->sql->from}
                     WHERE {$this->sql->where}
                       AND (x.xp > :neighxp
                        OR (x.xp = :neighxpeq AND x.id < :neighid))";
            $this->startingoffset = 1 + $DB->count_records_sql($sql, $params) - $this->startingrank;
            $this->startinglevel = $record->lvl;
            $this->startingxp = $record->xp;

        // When relative, set self XP as difference.
        } else if ($this->rankmode == block_xp_manager::RANK_REL) {

            $record = $this->get_user_record($this->userid);
            if ($record) {
                $this->startingxpdiff = $record->xp;
            }
        }
    }

    /**
     * Get the current user record.
     *
     * @return stdClass|false
     */
    protected function get_user_record() {
        global $DB, $USER;

        if ($this->currentuserrecord === null) {
            $sqlme = "SELECT {$this->sql->fields}
                        FROM {$this->sql->from}
                       WHERE {$this->sql->where}
                         AND x.userid = :myuserid";
            $record = $DB->get_record_sql($sqlme, $this->sql->params + array('myuserid' => $this->userid));

            // Hack so that admin can see something. Hopefully we won't create too many bugs in case of missing fields.
            if (empty($record) && $this->neighboursonly && $this->xpmanager->can_manage()) {
                $record = (object) array(
                    'id' => 0,
                    'userid' => $this->userid,
                    'courseid' => $this->xpmanager->get_courseid(),
                    'xp' => 0,
                    'lvl' => 1,
                );
                $record = username_load_fields_from_object($record, $USER);
                $record->picture = $USER->picture;
                $record->imagealt = $USER->imagealt;
                $record->email = $USER->email;
            }

            $this->currentuserrecord = $record;
        }

        return $this->currentuserrecord;
    }

    /**
     * Get the columns to sort by.
     *
     * @return array column => SORT_ constant.
     */
    public function get_sort_columns() {
        return array('x.lvl' => SORT_DESC, 'x.xp' => SORT_DESC, 'x.id' => SORT_ASC);
    }

    /**
     * Query the db.
     *
     * This is overridden to get all the records in order to generate
     * an accurate rank.
     *
     * @param int $pagesize Size of page for paginated displayed table.
     * @param bool $useinitialsbar Do you want to use the initials bar?
     */
    function query_db($pagesize, $useinitialsbar=true) {
        global $DB;

        // Only display neighbours.
        if ($this->neighboursonly) {
            $this->query_db_neighbours($this->userid, $this->neighboursabove, $this->neighboursbelow);
            return;
        }

        // When we're not downloading there is a pagination.
        if (!$this->is_downloading()) {
            if ($this->countsql === NULL) {
                $this->countsql = 'SELECT COUNT(1) FROM '.$this->sql->from.' WHERE '.$this->sql->where;
                $this->countparams = $this->sql->params;
            }
            $grandtotal = $DB->count_records_sql($this->countsql, $this->countparams);
            if ($useinitialsbar && !$this->is_downloading()) {
                $this->initialbars($grandtotal > $pagesize);
            }

            list($wsql, $wparams) = $this->get_sql_where();
            if ($wsql) {
                $this->countsql .= ' AND '.$wsql;
                $this->countparams = array_merge($this->countparams, $wparams);

                $this->sql->where .= ' AND '.$wsql;
                $this->sql->params = array_merge($this->sql->params, $wparams);

                $total  = $DB->count_records_sql($this->countsql, $this->countparams);
            } else {
                $total = $grandtotal;
            }

            $this->pagesize($pagesize, $total);

            // When we are displaying the full ranking, and the user did not request a specific page,
            // we will guess what page they appear on and jump right to that page. This logic makes
            // some assumption on the logic present in the parent class, not ideal but we have no choice.
            $requestedpage = optional_param($this->request[TABLE_VAR_PAGE], null, PARAM_INT);
            if ($requestedpage === null && ($record = $this->get_user_record())) {
                $sql = "SELECT COUNT('x')
                          FROM {$this->sql->from}
                         WHERE {$this->sql->where}
                           AND (x.xp > :thexp
                            OR (x.xp = :thexpeq AND x.id < :theid))";
                $params = $this->sql->params + array(
                    'thexp' => $record->xp,
                    'thexpeq' => $record->xp,
                    'theid' => $record->id
                );
                $count = $DB->count_records_sql($sql, $params);
                if ($count > 0) {
                    $this->currpage = floor($count / $pagesize);
                }
            }
        }

        $sort = $this->get_sql_sort();
        if ($sort) {
            $sort = "ORDER BY $sort";
        }
        $sql = "SELECT
                {$this->sql->fields}
                FROM {$this->sql->from}
                WHERE {$this->sql->where}
                {$sort}";

        $this->rawdata = $DB->get_records_sql($sql, $this->sql->params, $this->pagesize * $this->currpage, $this->pagesize);
    }

    /**
     * Query DB for the neighbours only.
     *
     * Note that this method resets and ignores pagination settings.
     *
     * @param int $userid The user to get the ladder for.
     * @param int $abovecount Number of neighbours to display above.
     * @param int $belowcount Number of neighbours to display below.
     * @return void
     */
    public function query_db_neighbours($userid, $abovecount, $belowcount) {
        global $DB;

        // First fetch self.
        $me = $this->get_user_record($userid);
        if (!$me) {
            $this->rawdata = array();
            return;
        }

        // Fetch the neighbours.
        $params = $this->sql->params + array(
            'neighid' => $me->id,
            'neighxp' => $me->xp,
            'neighxpeq' => $me->xp,
        );
        $sqlabove = "SELECT {$this->sql->fields}
                       FROM {$this->sql->from}
                      WHERE {$this->sql->where}
                        AND (x.xp > :neighxp
                         OR (x.xp = :neighxpeq AND x.id < :neighid))
                      ORDER BY x.xp ASC, x.id DESC";
        $sqlbelow = "SELECT {$this->sql->fields}
                       FROM {$this->sql->from}
                      WHERE {$this->sql->where}
                        AND (x.xp < :neighxp
                         OR (x.xp = :neighxpeq AND x.id > :neighid))
                      ORDER BY x.xp DESC, x.id ASC";

        $records = array();
        $above = $DB->get_records_sql($sqlabove, $params, 0, $abovecount);
        foreach ($above as $record) {
            array_unshift($records, $record);
        }
        array_push($records, $me);
        $below = $DB->get_records_sql($sqlbelow, $params, 0, $belowcount);
        foreach ($below as $record) {
            array_push($records, $record);
        }

        // Set the raw data.
        $this->rawdata = $records;

        // No pagination.
        $count = count($records);
        $this->pagesize($count, $count);
    }

}
