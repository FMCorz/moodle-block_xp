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
 * Action.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\action;

/**
 * Action.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class discussion_created extends static_action {

    /** @var \context The context. */
    protected $context;
    /** @var int The user ID. */
    protected $userid;
    /** @var int The object ID. */
    protected $objectid;
    /** @var DateTimeImmutable The time. */
    protected $time;

    /**
     * Constructor.
     *
     * @param string $type The type.
     * @param \context $context The context.
     * @param int $authorid The author.
     * @param int $discid The discussion ID.
     */
    public function __construct(\context $context, $authorid, $discid) {
        parent::__construct('discussion_created', $context, (int) $authorid, (int) $discid);
    }

    /**
     * Get the discussion ID.
     *
     * @return int
     */
    public function get_discussion_id(): int {
        return $this->objectid;
    }

}
