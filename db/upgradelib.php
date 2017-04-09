<?php

function save_default_filters() {
    global $DB;

    $transaction = $DB->start_delegated_transaction();

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:651:"{"_class":"block_xp_ruleset","method":"any","rules":[{"_class":"block_xp_rule_event","compare":"eq","value":"\\mod_book\\event\\course_module_viewed","property":"eventname"},{"_class":"block_xp_rule_event","compare":"eq","value":"\\mod_forum\\event\\discussion_subscription_created","property":"eventname"},{"_class":"block_xp_rule_event","compare":"eq","value":"\\mod_forum\\event\\subscription_created","property":"eventname"},{"_class":"block_xp_rule_property","compare":"contains","value":"assessable_submitted","property":"eventname"},{"_class":"block_xp_rule_property","compare":"contains","value":"assessable_uploaded","property":"eventname"}]}";s:6:"points";i:0;s:9:"sortorder";i:0;}}
EOD;
    $array = unserialize($serialized_record);
    $DB->insert_record($array);

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:80:"{"_class":"block_xp_rule_property","compare":"eq","value":"c","property":"crud"}";s:6:"points";i:45;s:9:"sortorder";i:1;}}
EOD;
    $array = unserialize($serialized_record);
    $DB->insert_record($array);

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:80:"{"_class":"block_xp_rule_property","compare":"eq","value":"r","property":"crud"}";s:6:"points";i:9;s:9:"sortorder";i:2;}}
EOD;
    $array = unserialize($serialized_record);
    $DB->insert_record($array);

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:80:"{"_class":"block_xp_rule_property","compare":"eq","value":"u","property":"crud"}";s:6:"points";i:3;s:9:"sortorder";i:3;}}
EOD;
    $array = unserialize($serialized_record);
    $DB->insert_record($array);

    $serialized_record = <<<'EOD'
a:2:{i:0;s:16:"block_xp_filters";i:1;O:8:"stdClass":5:{s:2:"id";N;s:8:"courseid";i:0;s:8:"ruledata";s:80:"{"_class":"block_xp_rule_property","compare":"eq","value":"d","property":"crud"}";s:6:"points";i:0;s:9:"sortorder";i:4;}}
EOD;
    $array = unserialize($serialized_record);
    $DB->insert_record($array);

    $transaction->allow_commit();

}

