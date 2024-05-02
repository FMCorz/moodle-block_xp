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
class crud extends static_action {

    /** @var string */
    protected $crud = 'r';
    /** @var string */
    protected $eventname;
    /** @var int|null */
    protected $relateduserid;

    /**
     * Constructor.
     *
     * @param \context $context The context.
     * @param int $userid The user ID.
     * @param string $eventname The event name.
     * @param int|null $objectid The object ID.
     */
    public function __construct(\context $context, $userid, $eventname, $objectid) {
        parent::__construct('crud', $context, (int) $userid, $objectid);
        $this->eventname = $eventname;
    }

    /**
     * Get the crud letter.
     *
     * @return int
     */
    public function get_crud(): string {
        return $this->crud;
    }

    /**
     * Get the event name
     *
     * @return string
     */
    public function get_event_name(): string {
        return $this->eventname;
    }

    /**
     * Get the related user ID, if any.
     *
     * @return ?int
     */
    public function get_related_user_id(): ?int {
        return $this->relateduserid ? (int) $this->relateduserid : null;
    }

    /**
     * Is create?
     *
     * @return bool
     */
    public function is_create(): bool {
        return $this->crud === 'c';
    }

    /**
     * Is delete?
     *
     * @return bool
     */
    public function is_delete(): bool {
        return $this->crud === 'd';
    }

    /**
     * Is read?
     *
     * @return bool
     */
    public function is_read(): bool {
        return $this->crud === 'r';
    }

    /**
     * Is update?
     *
     * @return bool
     */
    public function is_update(): bool {
        return $this->crud === 'u';
    }

    /**
     * From event.
     *
     * @param \core\event\base $event The event.
     * @return static
     */
    public static function from_event(\core\event\base $event) {
        $action = new static($event->get_context(), $event->userid, $event->eventname, $event->objectid);
        $action->crud = $event->crud;
        $action->relateduserid = $event->relateduserid;
        return $action;
    }

}
