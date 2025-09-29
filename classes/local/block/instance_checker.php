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

namespace block_xp\local\block;

use context;

/**
 * Block instance checker.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface instance_checker {

    /**
     * Count instances in context.
     *
     * @param string $name The block name, without 'block_'.
     * @param context $context The context to search in.
     * @return int
     */
    public function count_instances_in_context($name, context $context);

    /**
     * Whether it has an instance in the context.
     *
     * @param string $name The block name, without 'block_'.
     * @param context $context The context to search in.
     * @return bool
     */
    public function has_instance_in_context($name, context $context);

}
