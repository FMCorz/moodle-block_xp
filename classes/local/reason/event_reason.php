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

use block_xp\local\action\crud;

/**
 * Reason.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class event_reason implements reason, reason_with_rule, reason_with_short_description, reason_with_subtype, reason_with_tracking {
    use reason_deprecation_filler_trait;
    use reason_rule_trait;
    use reason_subtype_trait;
    use reason_tracking_trait;

    public function get_short_description() {
        $class = $this->get_subtype() ?? '';
        if (class_exists($class) && is_subclass_of($class, 'core\event\base')) {
            return $class::get_name();
        }
        return get_string('somethinghappened', 'block_xp');
    }

    /**
     * From event.
     *
     * @param \core\event\base $e
     * @return static
     */
    public static function from_event(\core\event\base $e) {
        $reason = new static();
        $reason->set_subtype($e->eventname);
        $reason->set_env_id((int) $e->contextid);
        $reason->set_object_id($e->objectid);
        $reason->set_parent_id($e->relateduserid);
        return $reason;
    }

    /**
     * From crud action.
     *
     * @param crud $action.
     * @return static
     */
    public static function from_crud(crud $action) {
        $reason = new event_reason();
        $reason->set_subtype($action->get_event_name());
        $reason->set_env_id((int) $action->get_context()->id);
        $reason->set_object_id($action->get_object_id());
        $reason->set_parent_id($action->get_related_user_id());
        return $reason;
    }

}
