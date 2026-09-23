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
use block_xp\local\config\course_world_config;
use block_xp\local\world;
use block_xp\local\xp\badge_url_resolver;
use block_xp\local\xp\badge_url_resolver_stack;

/**
 * Main factory.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_badge_url_resolver_course_world_factory implements
    badge_url_resolver_course_world_factory,
    badge_url_resolver_world_factory {
    /** @var badge_url_resolver Resolver. */
    protected $adminresolver;
    /** @var badge_url_resolver Stock resolver. */
    protected $stockresolver;

    /**
     * Constructor.
     *
     * @param badge_url_resolver $adminresolver The admin URL resolver.
     */
    public function __construct(badge_url_resolver $adminresolver) {
        $this->adminresolver = $adminresolver;
    }

    /**
     * Get the URL resolver.
     *
     * @param world $world The world.
     * @return badge_url_resolver
     */
    public function get_url_resolver_for_world(world $world) {
        return $this->make_world_resolver($world);
    }

    /**
     * Get the URL resolver.
     *
     * @param course_world $world The world.
     * @return badge_url_resolver
     */
    public function get_url_resolver(course_world $world) {
        return $this->get_url_resolver_for_world($world);
    }

    /**
     * Make the world resolver.
     *
     * @param world $world
     * @param bool $withstock
     * @return badge_url_resolver
     */
    protected function make_world_resolver(world $world, bool $withstock = true): badge_url_resolver {
        $resolver = null;
        $config = $world->get_config();
        $custombadges = $config->get('enablecustomlevelbadges');

        if ($custombadges == course_world_config::CUSTOM_BADGES_NOOP) {
            // We're all set, use the badges present.
            $resolver = new \block_xp\local\xp\file_storage_badge_url_resolver($world->get_context(), 'block_xp', 'badges', 0);
        } else if ($custombadges == course_world_config::CUSTOM_BADGES_MISSING) {
            // The scenario here is that we are in a new course (not a legacy one),
            // and the badges have not been customised, so we will use the admin
            // ones. We will exit the 'missing' state when the teacher will
            // effectively custommise the levels.
            $resolver = $this->adminresolver;
        } else {
            // Probably the legacy state of course_world_config::CUSTOM_BADGES_NONE.
            // We use the standard look of the levels.
            $resolver = new \block_xp\local\xp\dummy_badge_url_resolver();
        }

        // Use the fallback resolver when we're not using the admin directly. The fallback is used to
        // represent the default behaviour of XP. Using the admin as fallback is not acceptable as it
        // would prevent a world from being customised to not look like the admin, such as by removing an image.
        if ($withstock && $this->stockresolver && $resolver !== $this->adminresolver) {
            return new badge_url_resolver_stack([
                $resolver,
                $this->stockresolver,
            ]);
        }

        return $resolver;
    }

    /**
     * Set the stock resolver.
     */
    public function set_stock_resolver(badge_url_resolver $resolver) {
        $this->stockresolver = $resolver;
    }
}
