<?php

class block_xp_course_filters extends block_xp_filters {

    protected $courseid;

    public function __construct($id) {
        $this->$courseid = $id;
    }
    public function load() {
        throw new coding_exception('not implemented.');
    }

    public function save() {
        throw new coding_exception('not implemented.');
    }

    public function create_filter() {
        return new block_xp_filter_course($this->courseid);
    }



}

?>