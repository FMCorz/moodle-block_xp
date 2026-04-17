<?php
// This file is part of Level Up XP.
//
// Level Up XP is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Level Up XP is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Level Up XP.  If not, see <https://www.gnu.org/licenses/>.
//
// https://levelup.plus

namespace block_xp\local\strategy;

use block_xp\di;
use block_xp\local\action\action;
use block_xp\local\rulefilter\handler as rule_filter_handler;
use block_xp\local\logger\collection_logger;
use block_xp\local\logger\reason_collection_logger;
use block_xp\local\logger\reason_limit_indicator;
use block_xp\local\logger\ruletype_occurrence_indicator;
use block_xp\local\reason\reason;
use block_xp\local\ruletype\resolver as rule_type_resolver;
use block_xp\local\reason\reason_with_rule;
use block_xp\local\rule\instance;
use block_xp\local\rule\rule_sorter;
use block_xp\local\rule\world_rule_manager;
use block_xp\local\ruletype\consume_content;
use block_xp\local\ruletype\produce_content;
use block_xp\local\ruletype\ruletype;
use block_xp\local\ruletype\ruletype_with_limit;
use block_xp\local\strategy\action_collection_strategy;
use block_xp\local\world;
use block_xp\local\xp\state_store_with_reason;
use DateInterval;

/**
 * World action collection strategy.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class world_action_collection_strategy implements action_collection_strategy {

    /** @var world The world. */
    protected $world;
    /** @var collection_logger The logger. */
    protected $logger;

    /** @var rule_type_resolver The rule type resolver. */
    protected $ruletyperesolver;
    /** @var rule_filter_handler The rule filter handler. */
    protected $rulefilterhandler;
    /** @var rule_sorter|null The rule sorter. */
    protected $rulesorter;
    /** @var world_rule_manager|null The world rule manager. */
    protected $worldrulemanager;
    /** @var bool */
    protected $warnedaboutdeps = false;

    /**
     * Constructor.
     *
     * @param world $world The world.
     * @param collection_logger $logger The logger.
     * @param mixed $unused Deprecated, no longer used.
     * @param rule_type_resolver $ruletyperesolver The rule type resolver.
     * @param rule_filter_handler $rulefilterhandler The rule filter handler.
     */
    public function __construct(
        world $world,
        collection_logger $logger,
        $unused,
        rule_type_resolver $ruletyperesolver,
        rule_filter_handler $rulefilterhandler
    ) {
        $this->world = $world;
        $this->logger = $logger;
        $this->ruletyperesolver = $ruletyperesolver;
        $this->rulefilterhandler = $rulefilterhandler;
    }

    /**
     * Collection the action.
     *
     * @param action $action The action.
     * @return void
     */
    public function collect_action(action $action) {
        if (!$this->world->get_config()->get('enabled')) {
            return;
        } else if (!$this->worldrulemanager || !$this->rulesorter) {
            if (!$this->warnedaboutdeps) {
                debugging('The world_action_collection_strategy is missing dependencies.', DEBUG_DEVELOPER);
                $this->warnedaboutdeps = true;
            }
            return;
        }

        $store = $this->world->get_store();
        $rules = $this->worldrulemanager->get_effective_rules_grouped_by_type($action->get_context());
        foreach ($rules as $ruletype => $typerules) {
            if (empty($typerules)) {
                continue;
            }

            $type = $this->ruletyperesolver->get_type($ruletype);
            if (!$type) {
                continue;
            };

            if (!$this->is_action_allowed_by_type($type, $action)) {
                continue;
            }

            $typerules = $this->rulesorter->sort($typerules);
            foreach ($typerules as $candidate) {

                // Get the filter.
                $filter = $this->rulefilterhandler->get_filter($candidate->get_filter_name());
                if (!$filter) {
                    continue;
                }

                // Test against the tester.
                $tester = $filter->get_action_tester($candidate->get_context(), $candidate->get_filter_config());
                if (!$tester->is_action_passing_constraints($action)) {
                    continue;
                }

                $rule = $candidate;
                $targetuserid = $action->get_user_id();
                $reason = $type->make_reason($action);
                if ($reason instanceof reason_with_rule) {
                    $reason->set_rule_id($rule->get_id());
                }

                // Check the reason limit.
                if ($this->is_reason_limit_reached($targetuserid, $rule, $reason)) {
                    break;
                }

                $points = $rule->get_points();

                // Award the points.
                if ($points > 0) {
                    if ($reason && $store instanceof state_store_with_reason) {
                        $store->increase_with_reason($targetuserid, $points, $reason);
                    } else {
                        $store->increase($targetuserid, $points);
                    }
                } else {
                    if ($this->logger instanceof reason_collection_logger) {
                        $this->logger->log_reason($targetuserid, $points, $reason);
                    }
                }

                // Stop evaluating the other rules of the same type.
                break;
            }
        }
    }

    /**
     * Get the rule limits.
     *
     * @param instance $rule The rule.
     * @return \block_xp\local\ruletype\limit_spec[]
     */
    protected function get_rule_limits(instance $rule): array {
        $type = $this->ruletyperesolver->get_type($rule->get_type_name());
        if (!$type instanceof ruletype_with_limit) {
            return [];
        }
        return [
            $type->get_default_limit(),
            $type->get_default_repeat_limit(),
        ];
    }

    /**
     * Whether the action is allowed by the type.
     *
     * @param ruletype $type The type.
     * @param action $action The action.
     * @return bool
     */
    protected function is_action_allowed_by_type(ruletype $type, action $action) {
        if (!$type->is_action_compatible($action)) {
            return false;
        }

        if (!$type->is_action_satisfying_requirements($action)) {
            return false;
        }

        if ($this->is_type_limit_reached($type, $action)) {
            return false;
        }

        return true;
    }

    /**
     * Whether the reason limit is reached.
     *
     * @param int $userid The user ID.
     * @param instance $rule The rule.
     * @param reason $reason The reason.
     * @return bool
     */
    protected function is_reason_limit_reached(int $userid, instance $rule, reason $reason): bool {
        if (!$this->logger instanceof reason_limit_indicator) {
            return false;
        }

        // We only apply limits for rules with points.
        $limits = $rule->get_points() <= 0 ? [] : $this->get_rule_limits($rule);
        foreach ($limits as $limit) {
            if (!$limit || !$limit->get_max()) {
                continue;
            }
            if ($this->logger->is_rule_reason_limit_reached($userid, $rule->get_id(), $reason, $limit)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether the type limit is reached.
     *
     * @param ruletype $type The type.
     * @return bool
     */
    protected function is_type_limit_reached(ruletype $type, action $action): bool {
        $maxcount = 0;
        $timeframe = 60;

        if ($type instanceof consume_content) {
            // Limit consume content to 6 times per 60 seconds.
            $maxcount = 10;
            $timeframe = 60;
        } else if ($type instanceof produce_content) {
            // Limit content production to 5 times per 3 minutes.
            $maxcount = 5;
            $timeframe = 60 * 3;
        }

        if ($maxcount > 0) {
            if ($this->logger instanceof ruletype_occurrence_indicator) {
                $since = di::get('clock')->now()->sub(new DateInterval("PT{$timeframe}S"));
                return $this->logger->has_ruletype_happened_since($action->get_user_id(), $type, $since, $maxcount);
            }
        }

        return false;
    }

    /**
     * Set the rule sorter.
     *
     * @param rule_sorter $rulesorter The rule sorter.
     */
    public function set_rule_sorter(rule_sorter $rulesorter): void {
        $this->rulesorter = $rulesorter;
    }

    /**
     * Set the world rule manager.
     *
     * @param world_rule_manager $worldrulemanager The world rule manager.
     */
    public function set_world_rule_manager(world_rule_manager $worldrulemanager): void {
        $this->worldrulemanager = $worldrulemanager;
    }

}
