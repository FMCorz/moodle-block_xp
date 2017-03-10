<?php

abstract class block_xp_filterset {

    protected $filters;

    public function __construct() {
        $this->filters = array();
        $this->load();
    }

    public abstract function create_filter();

    public abstract function load();

    public function save() {
        foreach($this->filters as $filter) {
            $filter->save();
        }
    }

    // Should import delete previous filters? Dont' think so...
    public function import(block_xp_filterset $filters_object) {

        foreach($filters_object->get() as $filter) {
            $cloned_filter = $this->create_filter();
            $cloned_filter->load($filter);
            $this->filters[] = $cloned_filter;
        }

        $this->save();
    }

    public function empty() {
        return empty($this->filters);
    }

    public function get() {
        return $this->filters;
    }
}

?>