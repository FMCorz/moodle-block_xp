<?php

class upgradelib {

    public static function append_default_filters_to_courses() {
        global $DB;

        $transaction = $DB->start_delegated_transaction();

        $records = $DB->get_records('block_xp_config');
        foreach($records as $record) {
            self::append_default_filters_to_course($record->courseid);
        }

        $transaction->allow_commit();
    }

    public static function save_default_filters() {
        global $DB;
        $transaction = $DB->start_delegated_transaction();

        self::save_static_filters();

        $transaction->allow_commit();
    }

    private static function append_default_filters_to_course(int $courseid) {
        self::save_static_filters($courseid);
    }

    private static function save_static_filters(int $courseid = 0) {
        global $DB;

        $rules = self::static_rules();
        $sortorder = 100;

        foreach($rules as $rule) {
            $rule['courseid'] = $courseid;
            $rule['sortorder'] = $sortorder;
            $DB->insert_record("block_xp_filters", $rule);
            $sortorder += 1;
        }
    }

    private static function static_rules() {
        $ruledata1 = [
                  "_class" => "block_xp_ruleset",
                  "method" => "any",
                  "rules"  => [
                          [
                                  "_class"   => "block_xp_rule_event",
                                  "compare"  => "eq",
                                  "value"    => "\\mod_book\\event\\course_module_viewed",
                                  "property" => "eventname"
                          ],
                          [
                                  "_class"   => "block_xp_rule_event",
                                  "compare"  => "eq",
                                  "value"    => "\\mod_forum\\event\\discussion_subscription_created",
                                  "property" => "eventname"
                          ],
                          [
                                  "_class"   => "block_xp_rule_event",
                                  "compare"  => "eq",
                                  "value"    => "\\mod_forum\\event\\subscription_created",
                                  "property" => "eventname"
                          ],
                          [
                                  "_class"   => "block_xp_rule_property",
                                  "compare"  => "contains",
                                  "value"    => "assessable_submitted",
                                  "property" => "eventname"
                          ],
                          [
                                  "_class"   => "block_xp_rule_property",
                                  "compare"  => "contains",
                                  "value"    => "assessable_uploaded",
                                  "property" => "eventname"
                          ]]
                ];

        $rule1 = [
                "ruledata" => json_encode($ruledata1),
                "points"   => 0
        ];

        $ruledata2 = [
                "_class"   => "block_xp_rule_property",
                "compare"  => "eq",
                "value"    => "c",
                "property" =>"crud"
        ];

        $rule2 = [
                "ruledata" => json_encode($ruledata2),
                "points"   => 45
        ];

        $ruledata3 = [
                "_class"   => "block_xp_rule_property",
                "compare"  => "eq",
                "value"    => "r",
                "property" => "crud"
        ];

        $rule3 = [
                "ruledata" => json_encode($ruledata3),
                "points"   => 9
        ];

        $ruledata4 = [
                "_class"   => "block_xp_rule_property",
                "compare"  => "eq",
                "value"    => "u",
                "property" => "crud"
        ];

        $rule4 = [
                "ruledata" => json_encode($ruledata4),
                "points"   => 3
        ];

        $ruledata5 = [
                "_class"   => "block_xp_rule_property",
                "compare"  => "eq",
                "value"    => "d",
                "property" => "crud"
        ];

        $rule5 = [
                "ruledata" => json_encode($ruledata5),
                "points"   => 0
        ];

        return [$rule1, $rule2, $rule3, $rule4, $rule5];
    }

}
