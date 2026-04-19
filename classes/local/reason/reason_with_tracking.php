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

namespace block_xp\local\reason;

/**
 * Reason.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface reason_with_tracking extends reason {

    /**
     * Get the environment ID.
     *
     * @return int|null
     */
    public function get_env_id(): ?int;

    /**
     * Get the object ID.
     *
     * @return int|null
     */
    public function get_object_id(): ?int;

    /**
     * Get the parent ID.
     *
     * @return int|null
     */
    public function get_parent_id(): ?int;

    /**
     * Set the environment ID.
     *
     * @param int|null $envid The environment ID.
     */
    public function set_env_id(?int $envid): void;

    /**
     * Set the object ID.
     *
     * @param int|null $objectid The object ID.
     */
    public function set_object_id(?int $objectid): void;

    /**
     * Set the parent ID.
     *
     * @param int|null $parentid The parent ID.
     */
    public function set_parent_id(?int $parentid): void;

}
