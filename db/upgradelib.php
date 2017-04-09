<?php

function append_default_filters_to_courses() {
    global $DB;

    $transaction = $DB->start_delegated_transaction();

    global $DB;

    $records = $DB->get_records('block_xp_config');
    foreach($records as $record) {
        save_static_filters($record->courseid);
    }

    $transaction->allow_commit();
}

function save_default_filters() {
    global $DB;
    $transaction = $DB->start_delegated_transaction();

    save_static_filters(0);

    $transaction->allow_commit();
}

function save_static_filters(int $courseid) {
    global $DB;

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:651:"{"_class":"block_xp_ruleset","method":"any","rules":[{"_class":"block_xp_rule_event","compare":"eq","value":"\\mod_book\\event\\course_module_viewed","property":"eventname"},{"_class":"block_xp_rule_event","compare":"eq","value":"\\mod_forum\\event\\discussion_subscription_created","property":"eventname"},{"_class":"block_xp_rule_event","compare":"eq","value":"\\mod_forum\\event\\subscription_created","property":"eventname"},{"_class":"block_xp_rule_property","compare":"contains","value":"assessable_submitted","property":"eventname"},{"_class":"block_xp_rule_property","compare":"contains","value":"assessable_uploaded","property":"eventname"}]}";s:6:"points";i:0;s:9:"sortorder";i:100;}}
EOD;
    $array = unserialize($serialized_record);
    $array[1]->courseid = $courseid;
    $DB->insert_record("block_xp_filters", $array[1]);

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:80:"{"_class":"block_xp_rule_property","compare":"eq","value":"c","property":"crud"}";s:6:"points";i:45;s:9:"sortorder";i:101;}}
EOD;
    $array = unserialize($serialized_record);
    $array[1]->courseid = $courseid;
    $DB->insert_record("block_xp_filters", $array[1]);

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:80:"{"_class":"block_xp_rule_property","compare":"eq","value":"r","property":"crud"}";s:6:"points";i:9;s:9:"sortorder";i:102;}}
EOD;
    $array = unserialize($serialized_record);
    $array[1]->courseid = $courseid;
    $DB->insert_record("block_xp_filters", $array[1]);

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:80:"{"_class":"block_xp_rule_property","compare":"eq","value":"u","property":"crud"}";s:6:"points";i:3;s:9:"sortorder";i:103;}}
EOD;
    $array = unserialize($serialized_record);
    $array[1]->courseid = $courseid;
    $DB->insert_record("block_xp_filters", $array[1]);

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:80:"{"_class":"block_xp_rule_property","compare":"eq","value":"d","property":"crud"}";s:6:"points";i:0;s:9:"sortorder";i:1044;}}
EOD;
    $array = unserialize($serialized_record);
    $array[1]->courseid = $courseid;
    $DB->insert_record("block_xp_filters", $array[1]);

}

