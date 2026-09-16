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
// See <https://levelup.plus>.

namespace block_xp\local\factory;

use block_xp\di;
use block_xp\local\config\config;
use block_xp\local\logger\collection_logger;
use block_xp\local\logger\context_collection_logger;
use block_xp\local\reason\resolver;
use block_xp\local\ruletype\resolver as ruletype_resolver;
use block_xp\local\world;
use moodle_database;

/**
 * World logger factory.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class world_logger_factory {
    /** @var config The admin config. */
    protected $config;
    /** @var moodle_database The database. */
    protected $db;
    /** @var reason_from_log_entry_factory The reason factory. */
    protected $reasonfactory;
    /** @var resolver The reason resolver. */
    protected $reasonresolver;
    /** @var ruletype_resolver The rule type resolver. */
    protected $ruletyperesolver;

    /**
     * Constructor.
     *
     * @param config $adminconfig
     */
    public function __construct(config $adminconfig) {
        $this->db = di::get('db');
        $this->reasonresolver = di::get('reason_resolver');
        $this->reasonfactory = di::get('reason_from_log_entry_factory');
        $this->ruletyperesolver = di::get('rule_type_resolver');
        $this->config = $adminconfig;
    }

    /**
     * Instantiate collection logger.
     *
     * @param world $world The world.
     * @return collection_logger
     */
    protected function instantiate_collection_logger(world $world): collection_logger {
        $context = $world->get_context();
        return new context_collection_logger($this->db, (int) $context->id);
    }

    /**
     * Get the logger for a world.
     *
     * @param world $world The world.
     * @return collection_logger
     */
    public function get_logger_for_world(world $world): collection_logger {
        $logger = $this->instantiate_collection_logger($world);

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
}
