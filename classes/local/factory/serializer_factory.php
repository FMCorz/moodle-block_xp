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
 * Serializer factory.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\factory;

use block_xp\di;
use block_xp\local\serializer\availability_info_serializer;
use block_xp\local\serializer\level_serializer;
use block_xp\local\serializer\limit_spec_serializer;
use block_xp\local\serializer\levels_info_serializer;
use block_xp\local\serializer\rule_serializer;
use block_xp\local\serializer\rulefilter_serializer;
use block_xp\local\serializer\ruletype_profile_serializer;
use block_xp\local\serializer\ruletype_serializer;
use block_xp\local\serializer\url_serializer;

/**
 * Serializer factory.
 *
 * @package    block_xp
 * @copyright  2023 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class serializer_factory {

    /**
     * Get the serializer.
     */
    public function get_availability_info_serializer() {
        return new availability_info_serializer();
    }

    /**
     * Get the serializer.
     */
    public function get_levels_info_serializer() {
        return new levels_info_serializer($this->get_level_serializer());
    }

    /**
     * Get the serializer.
     */
    public function get_level_serializer() {
        return new level_serializer(new url_serializer());
    }

    /**
     * Get the serializer.
     */
    public function get_limit_spec_serializer() {
        return new limit_spec_serializer();
    }

    /**
     * Get the serializer.
     */
    public function get_rulefilter_serializer() {
        return new rulefilter_serializer(di::get('rule_filter_handler'));
    }

    /**
     * Get the serializer.
     */
    public function get_rule_serializer() {
        return new rule_serializer(di::get('rule_filter_handler'));
    }

    /**
     * Get the serializer.
     */
    public function get_ruletype_profile_serializer() {
        return new ruletype_profile_serializer();
    }

    /**
     * Get the serializer.
     */
    public function get_ruletype_serializer() {
        $serializer = new ruletype_serializer(
            di::get('rule_type_resolver'),
            $this->get_availability_info_serializer(),
            $this->get_limit_spec_serializer(),
            $this->get_ruletype_profile_serializer()
        );
        $serializer->set_filter_mediator(di::get('rule_filter_mediator'));
        return $serializer;
    }

}
