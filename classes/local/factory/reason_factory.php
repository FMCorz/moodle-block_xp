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
 * Reason factory.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\factory;

use block_xp\local\reason\reason_with_rule;
use block_xp\local\reason\reason_with_subtype;
use block_xp\local\reason\reason_with_tracking;
use block_xp\local\reason\resolver;
use block_xp\local\reason\unknown_reason;
use context;

/**
 * Reason factory.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class reason_factory implements reason_from_log_entry_factory {

    /** @var resolver The reason resolver. */
    protected $resolver;
    /** @var array Where keys are names. */
    protected $isunknowncache = [];

    /**
     * Constructor.
     *
     * @param resolver $resolver The resolver.
     */
    public function __construct(resolver $resolver) {
        $this->resolver = $resolver;
    }

    /**
     * Make a reason from a log entry.
     *
     * @param string $name The reason name.
     * @param object $record The log entry record expecting subtype, envid, parentid, objectid, ruleid, etc.
     * @return reason
     */
    public function get_reason_from_log_entry(string $name, $record) {
        $reason = $this->instantiate_reason($name, $record);

        if (isset($record->subtype) && $reason instanceof reason_with_subtype) {
            $reason->set_subtype($record->subtype);
        }
        if (isset($record->ruleid) && $reason instanceof reason_with_rule) {
            $reason->set_rule_id((int) $record->ruleid);
        }
        if ($reason instanceof reason_with_tracking) {
            if (isset($record->envid)) {
                $reason->set_env_id((int) $record->envid);
            }
            if (isset($record->parentid)) {
                $reason->set_parent_id((int) $record->parentid);
            }
            if (isset($record->objectid)) {
                $reason->set_object_id((int) $record->objectid);
            }
        }

        return $reason;
    }

    /**
     * Instantiate a reason by name.
     *
     * The logic here is only meant to be used when the reason is instantiated
     * from the logs and should not be used in another context. It can return
     * objects that are not setup adequately, and more or less handle compatibility
     * with older versions.
     *
     * @param string $name The name.
     * @param object $record The log record.
     * @return reason
     */
    protected function instantiate_reason(string $name, $record) {
        if ($this->isunknowncache[$name] ?? false) {
            return $this->make_unknown($name);
        }

        $classname = $this->resolver->get_class_name($name);
        if (!$classname) {
            return $this->make_unknown($name);
        }

        try {
            // The new standard is for reasons to be instantiated without arguments, we should return here.
            return new $classname();
        } catch (\Throwable $e) {
            unset($e);
        }

        // Try to mitigate the situation for known cases.
        try {
            $envid = (int) ($record->envid ?? 0);
            $objectid = (int) ($record->objectid ?? 0);
            if ($classname === 'local_xp\local\reason\activity_completion_reason') {
                return new \local_xp\local\reason\activity_completion_reason($objectid, 0);
            } else if ($classname === 'local_xp\local\reason\course_completed_reason') {
                $ctx = context::instance_by_id($envid, IGNORE_MISSING);
                return new \local_xp\local\reason\course_completed_reason($ctx ? (int) $ctx->instanceid : 0);
            } else if ($classname === 'local_xp\local\reason\drop_collected_reason') {
                return new \local_xp\local\reason\drop_collected_reason($objectid);
            } else if ($classname === 'local_xp\local\reason\event_reason') {
                return new \local_xp\local\reason\event_reason($record->subtype ?? '', $envid, 0, 0);
            } else if ($classname === 'local_xp\local\reason\graded_reason') {
                return new \local_xp\local\reason\graded_reason($record->objectid ?? 0, 0);
            } else if ($classname === 'local_xp\local\reason\section_completion_reason') {
                $ctx = context::instance_by_id($envid, IGNORE_MISSING);
                return new \local_xp\local\reason\section_completion_reason($ctx ? (int) $ctx->instanceid : 0, $objectid);
            } else if ($classname === 'block_gearup\local\xp\achievement_unlocked_reason') {
                return new \block_gearup\local\xp\achievement_unlocked_reason(0);
            } else if ($classname === 'block_gearup\local\xp\challenge_completed_reason') {
                return new \block_gearup\local\xp\challenge_completed_reason(0);
            } else if ($classname === 'block_gearup\local\xp\quest_completed_reason') {
                return new \block_gearup\local\xp\quest_completed_reason(0);
            }
        } catch (\Throwable $e) {
            unset($e);
        }

        debugging("Reason {$classname} must have a constructor that accepts no arguments.", DEBUG_DEVELOPER);
        return $this->make_unknown($name);
    }

    /**
     * Make an unknown instance for name.
     *
     * @param string $name The unknown reason's name.
     * @return reason
     */
    protected function make_unknown($name) {
        $this->isunknowncache[$name] = true;
        return new unknown_reason();
    }

}
