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

namespace block_xp\local\rule;

use block_xp\local\rulefilter\handler;

/**
 * Rule sorter.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule_sorter {

    /** @var handler The filter handler. */
    protected $filterhandler;

    /**
     * Constructor.
     *
     * @param handler $filterhandler The filter handler.
     */
    public function __construct(handler $filterhandler) {
        $this->filterhandler = $filterhandler;
    }

    /**
     * Sort the rules by priority.
     *
     * Deepest context first, then highest filter weight, then most points, then lowest ID.
     *
     * @param instance[] $rules The rules.
     * @return instance[] The most important rules first.
     */
    public function sort(array $rules): array {
        // Sort the rules by context level, filter method weight, and then points, all descendingly, and then ID.
        // This means that the deepest context, with the highest weight, and the most points will be evaluated first.
        // Although, the context depth does not currently handle a hierarchy within a context level (like course cat).
        usort($rules, function ($a, $b) {
            $achildcontext = $a->get_child_context();
            $bchildcontext = $b->get_child_context();

            // Sort by context.
            $acontextlevel = (int) ($achildcontext ? $achildcontext->contextlevel : $a->get_context()->contextlevel);
            $bcontextlevel = (int) ($bchildcontext ? $bchildcontext->contextlevel : $b->get_context()->contextlevel);
            if ($acontextlevel !== $bcontextlevel) {
                return $bcontextlevel - $acontextlevel;
            }

            // Sort by filter.
            if ($a->get_filter_name() !== $b->get_filter_name()) {
                $prioritya = $this->filterhandler->get_filter_priority_from_name($a->get_filter_name());
                $priorityb = $this->filterhandler->get_filter_priority_from_name($b->get_filter_name());
                return $priorityb - $prioritya;
            }

            // Sort by points descending.
            if ($a->get_points() !== $b->get_points()) {
                return $b->get_points() - $a->get_points();
            }

            // In case we've got a duplicate, sort by ID.
            return $a->get_id() - $b->get_id();
        });

        return $rules;
    }

}
