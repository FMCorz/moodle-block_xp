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

use block_xp\local\availability\has_availability_info;
use block_xp\local\rulefilter\handler;

/**
 * Serializer.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rulefilter_serializer implements serializer {

    /** @var handler The filter handler. */
    protected $filterhandler;
    /** @var serializer The serializer. */
    protected $availabilityinfoserializer;

    /**
     * Constructor.
     *
     * @param handler $filterhandler The filter handler.
     */
    public function __construct(handler $filterhandler) {
        $this->filterhandler = $filterhandler;
        $this->availabilityinfoserializer = new availability_info_serializer();
    }

    /**
     * Serialize.
     *
     * @param \block_xp\local\rulefilter\rulefilter $filter The filter.
     * @return array
     */
    public function serialize($filter) {

        $availabilitydata = null;
        if ($filter instanceof has_availability_info) {
            $availabilitydata = $this->availabilityinfoserializer->serialize($filter->get_availability_info());
        }

        return [
            'name' => $this->filterhandler->get_filter_name($filter),
            'label' => (string) $filter->get_display_name(),
            'description' => (string) $filter->get_short_description(),
            'ismultipleallowed' => $filter->is_multiple_allowed(),
            'weight' => $this->filterhandler->get_filter_priority($filter),
            'availabilityinfo' => $availabilitydata,
        ];
    }

}
