<?php

class block_xp_filters_static extends block_xp_filters {

    public function create_filter() {
        throw new coding_exception('Static filters cannot be modified.');
    }

    public function load() {
        if (empty($this->filters)) {
            $this->load_set_1();
        }
    }

    public function save() {
        throw new \coding_exception("Static filters cannot be saved.");
    }

    private function load_set_1() {
        $d = new block_xp_rule_property(block_xp_rule_base::EQ, 'd', 'crud');
        $c = new block_xp_rule_property(block_xp_rule_base::EQ, 'c', 'crud');
        $r = new block_xp_rule_property(block_xp_rule_base::EQ, 'r', 'crud');
        $u = new block_xp_rule_property(block_xp_rule_base::EQ, 'u', 'crud');

        // Skip those as they duplicate other more low level actions.
        $bcmv = new block_xp_rule_event('\mod_book\event\course_module_viewed');
        $dsc = new block_xp_rule_event('\mod_forum\event\discussion_subscription_created');
        $sc = new block_xp_rule_event('\mod_forum\event\subscription_created');
        $as = new block_xp_rule_property(block_xp_rule_base::CT, 'assessable_submitted', 'eventname');
        $au = new block_xp_rule_property(block_xp_rule_base::CT, 'assessable_uploaded', 'eventname');

        $ruleset = new block_xp_ruleset(array($bcmv, $dsc, $sc, $as, $au), block_xp_ruleset::ANY);
        $data = array('rule' => $ruleset, 'points' => 0, 'editable' => true);
        $this->filters[] = block_xp_filter::load_from_data($data);

        $data = array('rule' => $c, 'points' => 45, 'editable' => true);
        $this->filters[] = block_xp_filter::load_from_data($data);

        $data = array('rule' => $r, 'points' => 9, 'editable' => true);
        $this->filters[] = block_xp_filter::load_from_data($data);

        $data = array('rule' => $u, 'points' => 3, 'editable' => true);
        $this->filters[] = block_xp_filter::load_from_data($data);

        $data = array('rule' => $d, 'points' => 0, 'editable' => true);
        $this->filters[] = block_xp_filter::load_from_data($data);
    }
}

?>