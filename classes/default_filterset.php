<?php

class block_xp_default_filterset extends block_xp_filterset {

    public function load() {
        global $DB;

        $records = $DB->get_recordset('block_xp_default_filters',
                array(), 'sortorder ASC, id ASC');

        unset($this->filters);

        foreach ($records as $key => $filter_data) {
            $filter = $this->create_filter();
            $filter->load($filter_data);
            $this->filters[] = $filter;
        }
    }

    public function create_filter() {
        return new block_xp_default_filter();
    }
}

?>