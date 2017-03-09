<?php

class block_xp_course_filterset extends block_xp_filterset {

    protected $courseid;

    public function __construct($id) {
        $this->courseid = $id;

        parent::__construct();
    }

    public function load() {
        global $DB;

        $records = $DB->get_recordset('block_xp_filters',
                array('courseid' => $this->courseid));

        unset($this->filters);

        foreach ($records as $key => $filter_data) {
            $filter = $this->create_filter();
            $filter->load($filter_data);
            $this->filters[] = $filter;
        }
    }

    public function create_filter() {
        return new block_xp_course_filter($this->courseid);
    }
}

?>