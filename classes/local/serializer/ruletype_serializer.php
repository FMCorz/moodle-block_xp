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
use block_xp\local\icon\fa_icon;
use block_xp\local\rulefilter\filter_mediator;
use block_xp\local\ruletype\repeat_option;
use block_xp\local\ruletype\resolver;
use block_xp\local\ruletype\ruletype_with_goal;
use block_xp\local\ruletype\ruletype_with_limit;
use block_xp\local\ruletype\ruletype_with_profile;

/**
 * Serializer.
 *
 * @package    block_xp
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ruletype_serializer implements serializer {

    /** @var resolver The type resolver. */
    protected $typeresolver;
    /** @var serializer The serializer. */
    protected $availabilityinfoserializer;
    /** @var filter_mediator|null The filter mediator. */
    protected $filtermediator;
    /** @var serializer The serializer. */
    protected $limitspecserializer;
    /** @var serializer The serializer. */
    protected $profileserializer;

    /**
     * Constructor.
     *
     * @param resolver $typeresolver The type resolver.
     * @param serializer $availabilityinfoserializer The availability serializer.
     * @param serializer $limitspecserializer The limit spec serializer.
     * @param serializer $profileserializer The profile serializer.
     */
    public function __construct(
        resolver $typeresolver,
        serializer $availabilityinfoserializer,
        serializer $limitspecserializer,
        serializer $profileserializer
    ) {
        $this->typeresolver = $typeresolver;
        $this->availabilityinfoserializer = $availabilityinfoserializer;
        $this->limitspecserializer = $limitspecserializer;
        $this->profileserializer = $profileserializer;
    }

    /**
     * Set the filter mediator.
     *
     * @param filter_mediator $filtermediator The filter mediator.
     * @return self
     */
    public function set_filter_mediator(filter_mediator $filtermediator): self {
        $this->filtermediator = $filtermediator;
        return $this;
    }

    /**
     * Serialize.
     *
     * @param \block_xp\local\ruletype\ruletype $type The type.
     * @return array
     */
    public function serialize($type) {
        $icondata = null;

        // TODO Move to dedicated serializer?
        $icon = $type instanceof \block_xp\local\icon\with_iconography ? $type->get_icon() : null;
        $icon = $icon ?? new fa_icon('question-circle-o');
        $icondata = [
            'type' => $icon->get_type(),
            'value' => $icon->get_value(),
        ];

        $profiledata = null;
        if ($type instanceof ruletype_with_profile) {
            $profiledata = $this->profileserializer->serialize($type->get_profile());
        }

        $availabilitydata = null;
        if ($type instanceof has_availability_info) {
            $availabilitydata = $this->availabilityinfoserializer->serialize($type->get_availability_info());
        }

        $defaultlimitdata = null;
        $defaultrepeatlimitdata = null;
        if ($type instanceof ruletype_with_limit) {
            $defaultlimitdata = $this->limitspecserializer->serialize($type->get_default_limit());
            $defaultrepeatlimitdata = $this->limitspecserializer->serialize($type->get_default_repeat_limit());
        }

        $filters = $this->filtermediator ? $this->filtermediator->get_compatible_filter_names($type) : [];
        $repeatlimitoptions = null;
        if ($type instanceof ruletype_with_limit) {
            $repeatlimitoptions = array_values(array_map(function ($option) {
                return $this->serialize_repeat_option($option);
            }, $type->get_repeat_limit_options()));
        }

        return [
            'name' => $this->typeresolver->get_type_name($type),
            'label' => (string) $type->get_display_name(),
            'description' => (string) $type->get_short_description(),
            'filters' => $filters,
            'icon' => $icondata,
            'goal' => $type instanceof ruletype_with_goal ? $type->get_education_goal() : null,
            'availabilityinfo' => $availabilitydata,
            'defaultlimit' => $defaultlimitdata,
            'defaultrepeatlimit' => $defaultrepeatlimitdata,
            'repeatlimitoptions' => $repeatlimitoptions,
            'profile' => $profiledata,

            // Deprecated.
            'scope' => null,
            'repeatwindow' => null,
        ];
    }

    /**
     * Serialize a repeat limit option.
     *
     * @param repeat_option $option The option.
     * @return array
     */
    protected function serialize_repeat_option(repeat_option $option): array {
        return [
            'value' => $option->get_value(),
            'oncelabel' => $option->get_once_label(),
            'incompatiblewithfilters' => $option->is_compatible_inside_cm() ? [] : ['cm'],
        ];
    }
}
