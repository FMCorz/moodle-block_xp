<?php

defined('MOODLE_INTERNAL') || die();

class block_xp_filter_default extends block_xp_filter {

    public function load($filter_object) {

        // TODO: check null
        $this->ruledata = $filter_object->ruledata;
        $this->points = $filter_object->points;
        $this->sortorder = $filter_object->sortorder;
    }

    public function save() {
        $record = (object) array(
                'id' => $this->id,
                'courseid' => 0,
                'ruledata' => $this->ruledata,
                'points' => $this->points,
                'sortorder' => $this->sortorder,
        );

        $this->insert_or_update('block_xp_filters', $record);
    }
}

?>