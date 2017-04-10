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
 * Filter.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Filter class.
 *
 * The filter only works with block_xp_rule_property, or rulesets containing them.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_filter implements renderable {

    /**
     * The course ID.
     *
     * @var int
     */
    protected $courseid;

    /**
     * Whether or not this filter is editable.
     *
     * This is not stored in the database, it is just a flag.
     *
     * @var boolean
     */
    protected $editable = true;

    /**
     * The ID.
     *
     * @var int
     */
    protected $id;

    /**
     * Points for this filter.
     *
     * @var int
     */
    protected $points = 0;

    /**
     * Rule.
     *
     * This is not stored in the DB, it is constructed when needed.
     *
     * @var block_xp_rule
     */
    protected $rule;

    /**
     * The rule data.
     *
     * @var string
     */
    protected $ruledata = '';

    /**
     * The sort order.
     *
     * @var int
     */
    protected $sortorder = 0;

    /**
     * Constructor.
     *
     * Use {@link self::create_from_data()} instead.
     */
    public function __construct() {}

    /**
     *  Compare a filter with other, using ruledata string.
     *
     *  @return bool true if are equal
     */
    public static function compare(block_xp_filter $filter1, block_xp_filter $filter2) {
        return ($filter1->ruledata == $filter2->ruledata);
    }

    /**
     * Delete the rule.
     *
     * @return void
     */
    public function delete() {
        global $DB;
        if (!$this->id) {
            throw new coding_exception('ID of the filter is unknown.');
        }
        $DB->delete_records('block_xp_filters', array('id' => $this->id));
    }

    /**
     * Return whether or not the filter is editable.
     *
     * @return boolean
     */
    public function is_editable() {
        return $this->editable;
    }

    /**
     * Return the ID.
     *
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Return the points.
     *
     * @return int points.
     */
    public function get_points() {
        return $this->points;
    }

    /**
     * Return the rule object.
     *
     * @return block_xp_rule
     */
    public function get_rule() {
        if (!$this->rule) {
            $this->load_rule();
        }
        return $this->rule;
    }

    /**
     * Return the sortorder.
     *
     * @return int
     */
    public function get_sortorder() {
        return $this->sortorder;
    }

    /**
     * Simple Factory. Create an empty subclass of filter, based on courseid.
     *
     * @param int $id
     * @return block_xp_filter_default|block_xp_filter_course
     */
    public static function create(int $courseid = 0) {
        if ($courseid == 0) {
            return new block_xp_filter_default();
        }
        else {
            return new block_xp_filter_course($courseid);
        }
    }
    /**
     * Simple Factory. Create a new filter from data.
     *
     * Do not combine the keys 'rule' and 'ruledata' as it could lead to random behaviours.
     *
     * @param stdClass|array $record Information of the filter, from DB or not.
     * @return block_xp_filter The filter.
     */
    public static function create_from_data($record) {
        $courseid = (isset($record->courseid)) ? $record->courseid : 0;
        $filter = self::create($courseid);
        $filter->load($record);
        return $filter;
    }

    /**
     * Load the current filter from data.
     *
     * Do not combine the keys 'rule' and 'ruledata' as it could lead to random behaviours.
     *
     * @param stdClass|array $record Information of the filter, from DB or not.
     * @return block_xp_filter The filter.
     */
    public function load($object) {
        $tempcourseid = $this->courseid;

        $object = (is_array($object)) ? (object)$object : $object;
        foreach ($object as $key => $value) {
            if ($key == 'ruledata' && !empty($value)) {
                $this->set_ruledata($value);
                continue;
            }

            if ($key == 'rule' && !empty($value)) {
                $this->set_rule($value);
                continue;
            }

            if ($key == 'points') {
                // Prevent negatives.
                $value = abs(intval($value));
            } else if ($key == 'sortorder') {
                $value = intval($value);
            }

            $this->$key = $value;
        }

        // Preserve courseid
        $this->courseid = $tempcourseid;

        if (is_null($this->ruledata)) {
            throw new coding_exception("filter must have ruledata property");
        }
    }

    /**
     * As load but always create a new object in DB, by clearing id.
     *
     * @param block_xp_filter|array $filter
     */
    public function load_as_new($object) {
        $this->load($object);
        $this->id = null;
    }

    /**
     * Load the rule from {@link self::$ruledata}.
     *
     * @return void
     */
    protected function load_rule() {
        if (is_null($this->ruledata)) {
            throw new coding_exception("ruledata must not be null");
        }

        $ruledata = json_decode($this->ruledata, true);
        $this->rule = block_xp_rule::create($ruledata);
    }

    /**
     * Does the event match the filter.
     *
     * @param \core\event\base $event The event.
     * @return bool Whether or not it matches.
     */
    public function match(\core\event\base $event) {
        return $this->get_rule()->match($event);
    }

    /**
     * Save the record to the database.
     *
     * @return block_xp_filter
     */
    public function save() {
        if (empty($this->ruledata)) {
            if (empty($this->rule)) {
                new coding_exception("ruledata and rule should not be empty when saving");
            }

            if(is_array($this->rule)) {
                $this->rule = block_xp_rule::create($this->rule);
            }
            $this->ruledata = json_encode($this->rule->export());
        }

        $record = (object) array(
            'id' => $this->id,
            'courseid' => $this->courseid,
            'ruledata' => $this->ruledata,
            'points' => $this->points,
            'sortorder' => $this->sortorder,
        );

        $this->insert_or_update('block_xp_filters', $record);
    }

    /**
     * Insert or update current filter, based on id property existence.
     * @param string $table
     * @param object $record
     */
    protected function insert_or_update($table, $record) {
        global $DB;

        if (!$this->id) {
            $this->id = $DB->insert_record($table, $record);
        } else {
            $record->id = $this->id;
            $DB->update_record($table, $record);
        }
    }

    /**
     * Set the points.
     *
     * @param int $points
     */
    public function set_points($points) {
        $this->points = $points;
    }

    /**
     * Overrides the rule of the filter.
     *
     * @param block_xp_rule|array $rule
     */
    public function set_rule($rule) {
        if(is_array($rule)) {
            $rule = block_xp_rule::create($rule);
        }
        if (empty($rule)) {
            throw new coding_exception("rule can't be empty to generate ruledata");
        }
        if (!($rule instanceof block_xp_rule)) {
            throw new coding_exception("rule must be a block_xp_rule class");
        }

        $this->rule = $rule;
        $this->ruledata = json_encode($rule->export());
    }

    public function set_ruledata($ruledata) {
        $this->ruledata = $ruledata;
        $this->load_rule();
    }

    /**
     * Set the sortorder.
     *
     * @param int $sortorder
     */
    public function set_sortorder(int $sortorder) {
        $this->sortorder = $sortorder;
    }

    /**
     * Set if filter is editable.
     *
     * @param bool $editable
     */
    public function set_editable(bool $editable) {
        $this->editable = $editable;
    }

    /**
     * Validate the data of this filter.
     *
     * @param array $data Data to validate.
     * @return bool
     */
    public static function validate_data($data) {
        $valid = true;

        if (isset($data['courseid'])) {
            $valid = $valid && clean_param($data['courseid'], PARAM_INT) == $data['courseid'];
        }
        if (isset($data['points'])) {
            $valid = $valid && clean_param($data['points'], PARAM_INT) == $data['points'];
        }
        if (isset($data['sortorder'])) {
            $valid = $valid && clean_param($data['sortorder'], PARAM_INT) == $data['sortorder'];
        }
        if (isset($data['id'])) {
            $valid = $valid && clean_param($data['id'], PARAM_INT) == $data['id'];
        }
        if (isset($data['ruledata'])) {
            $ruledata = json_decode($data['ruledata'], true);
            $valid = $valid && $ruledata !== false;
            if ($valid) {
                $valid = $valid && self::validate_ruledata($ruledata);
            }
        }
        if (isset($data['rule'])) {
            throw new coding_exception('Validation for rule property is not implemented');
        }

        return $valid;
    }

    /**
     * Validate the rule data.
     *
     * @param array $data Data to validate.
     * @return bool
     */
    protected static function validate_ruledata($ruledata) {
        $valid = true;
        foreach ($ruledata as $key => $value) {
            if (!$valid) {
                break;
            }

            if ($key == '_class') {
                $reflexion = new ReflectionClass($value);
                $valid = $reflexion->isSubclassOf('block_xp_rule');
            } else if (is_array($value)) {
                $valid = block_xp_rule::validate_data($value);
            }
        }
        return $valid;
    }

}
