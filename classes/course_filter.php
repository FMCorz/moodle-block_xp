<?php

defined('MOODLE_INTERNAL') || die();

class block_xp_course_filter extends block_xp_filter {

    public function __construct($courseid) {
        $this->courseid = $courseid;
    }

    // TODO: refactor with filter_default?
    public function load($filter_object) {
        // TODO: check null
        $this->ruledata = $filter_object->ruledata;
        $this->points = $filter_object->points;
        $this->sortorder = $filter_object->sortorder;
    }

    public function save() {
        // TODO: check null
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

?>