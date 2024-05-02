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

namespace block_xp\local\serializer;

use block_xp\local\rulefilter\handler;

/**
 * Serializer.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rule_serializer implements serializer {

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
     * Serialize.
     *
     * @param \block_xp\local\rule\instance $instance The rule instance.
     * @return array
     */
    public function serialize($instance) {
        $filter = $this->filterhandler->get_filter($instance->get_filter_name());
        $effectivectx = $instance->get_child_context() ?? $instance->get_context();
        $label = $filter ? $filter->get_label_for_config($instance->get_filter_config(), $effectivectx) : null;

        return [
            'id' => $instance->get_id(),
            'points' => $instance->get_points(),
            'typename' => $instance->get_type_name(),
            'filtername' => $instance->get_filter_name(),
            'label' => $label ?? get_string('unknownconditiona', 'block_xp', $instance->get_filter_name()),
        ];
    }

}
