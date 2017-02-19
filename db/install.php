<?php

function xmldb_block_xp_install() {

    // Populate block_xp_default_filters table
    block_xp_filter_manager::save_default_filters();

    return true;

}

?>