<?php

class block_xp_filters_default extends block_xp_filters {

    public function load() {
        throw new coding_exception('not implemented.');
    }

    public function create_filter() {
        return new block_xp_filter_default();
    }
}

?>