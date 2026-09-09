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

use block_xp\local\routing\url_resolver;
use block_xp\local\world;

/**
 * World URL resolver factory.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class world_url_resolver_factory {
    /** @var url_resolver The URL resolver. */
    protected $urlresolver;

    /**
     * Constructor.
     */
    public function __construct(url_resolver $urlresolver) {
        $this->urlresolver = $urlresolver;
    }

    /**
     * Get a rule manager for a world.
     *
     * @param world $world The world.
     * @return url_resolver
     */
    public function get_url_resolver(world $world): url_resolver {
        return new class($this->urlresolver, $world) implements url_resolver {
            protected int $courseid;
            protected url_resolver $parent;
            protected world $world;
            public function __construct(url_resolver $parent, world $world) {
                $this->parent = $parent;
                $this->world = $world;
                $this->courseid = method_exists($world, 'get_courseid') ? $world->get_courseid() : 0;
            }
            public function get_route_url() {
                // We should never call this on the world URL resolver.
                throw new \Exception('Not implemented');
            }

            public function match($uri) {
                // We should never call this on the world URL resolver.
                throw new \Exception('Not implemented');
            }

            public function reverse($name, array $params = []) {
                // TODO We should not be able to reverse non-world URLs!
                // TODO WHat do we do for when we do not have a 'world' such as in events, or in adhoc linking?
                // TODO I almost think we should not use the `url_resolver` as such, maybe something different with explicit routes?
                $params['courseid'] = $this->courseid;
                return $this->parent->reverse($name, $params);
            }
        };
    }
}
