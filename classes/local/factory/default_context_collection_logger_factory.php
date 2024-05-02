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

/**
 * Factory.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\factory;

use block_xp\local\logger\collection_logger;
use block_xp\local\logger\context_collection_logger;
use block_xp\local\reason\resolver;
use block_xp\local\ruletype\resolver as ruletype_resolver;
use moodle_database;

/**
 * Factory.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_context_collection_logger_factory implements context_collection_logger_factory {

    /** @var moodle_database The database. */
    protected $db;
    /** @var reason_from_log_entry_factory The reason factory. */
    protected $reasonfactory;
    /** @var resolver The reason resolver. */
    protected $reasonresolver;
    /** @var ruletype_resolver|null The rule type resolver. */
    protected $ruletyperesolver;

    /**
     * Constructor.
     *
     * @param moodle_database $db The database.
     */
    public function __construct(moodle_database $db) {
        $this->db = $db;
    }

    /**
     * Get the logger.
     *
     * @param \context $context The context.
     * @return collection_logger
     */
    public function get_logger_from_context(\context $context): collection_logger {
        $logger = $this->instantiate_collection_logger($context);
        if ($this->reasonresolver && method_exists($logger, 'set_reason_resolver')) {
            $logger->set_reason_resolver($this->reasonresolver);
        }
        if ($this->reasonfactory && method_exists($logger, 'set_reason_from_log_entry_factory')) {
            $logger->set_reason_from_log_entry_factory($this->reasonfactory);
        }
        if ($this->ruletyperesolver && method_exists($logger, 'set_rule_type_resolver')) {
            $logger->set_rule_type_resolver($this->ruletyperesolver);
        }
        return $logger;
    }

    /**
     * Instantiate collection logger..
     *
     * @param \context $context
     * @return collection_logger
     */
    protected function instantiate_collection_logger(\context $context): collection_logger {
        return new context_collection_logger($this->db, (int) $context->id);
    }

    /**
     * Set the reason from log entry factory.
     *
     * @param reason_from_log_entry_factory $reasonfactory The reason factory.
     */
    public function set_reason_from_log_entry_factory(reason_from_log_entry_factory $reasonfactory) {
        $this->reasonfactory = $reasonfactory;
    }

    /**
     * Set the reason resolver.
     *
     * @param resolver $reasonresolver The reason resolver.
     */
    public function set_reason_resolver(resolver $reasonresolver) {
        $this->reasonresolver = $reasonresolver;
    }

    /**
     * Set the rule type resolver.
     *
     * @param ruletype_resolver $ruletyperesolver The rule type resolver.
     */
    public function set_rule_type_resolver(ruletype_resolver $ruletyperesolver) {
        $this->ruletyperesolver = $ruletyperesolver;
    }

}
