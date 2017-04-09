<?php

define('CLI_SCRIPT', 1);

require(__DIR__.'/../../../config.php');

global $DB;

class DBLogger {
    protected $db;
    function __construct($db) {
        $this->db = $db;
    }

    public function __call($name, $args) {
        $serialized_array = serialize($args);
        print "\n\$serialized_record = <<<'EOD'
$serialized_array
EOD;";
        print "\n\$array = unserialize(\$serialized_record);\n";
        print "\$DB->$name(\$array);\n";

        return call_user_func_array(array($this->db, $name), $args);

    }
}

$DB = new DBLogger($DB);

block_xp_filter_manager::save_default_filters();

