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

use block_xp\local\course_world;
use block_xp\local\navigation\course_world_navigator;
use block_xp\local\navigation\navigator;
use block_xp\local\routing\url_resolver;
use block_xp\local\world;

/**
 * World navigator factory.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class world_navigator_factory {
    /** @var url_resolver The URL resolver. */
    protected $urlresolver;

    /**
     * Constructor.
     */
    public function __construct(url_resolver $urlresolver) {
        $this->urlresolver = $urlresolver;
    }

    /**
     * Get the navigator for a world.
     *
     * @param world $world The world.
     * @return navigator
     */
    public function get_navigator_for_world(world $world) {
        if (!$world instanceof course_world) {
            throw new \coding_exception('Navigation for this world is not implemented.');
        }

        return new course_world_navigator($world, $this->urlresolver);
    }
}
