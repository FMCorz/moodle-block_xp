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

namespace block_xp\local\rulefilter;

use block_xp\local\ruletype\profile\profile;
use block_xp\local\ruletype\ruletype;
use block_xp\local\ruletype\ruletype_with_profile;

/**
 * Filter mediator.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_mediator {

    /** @var handler The filter handler. */
    protected $handler;

    /**
     * Constructor.
     *
     * @param handler $handler The filter handler.
     */
    public function __construct(handler $handler) {
        $this->handler = $handler;
    }

    /**
     * Get compatible filter names for a rule type.
     *
     * @param ruletype $type The rule type.
     * @return string[] The compatible filter names.
     */
    public function get_compatible_filter_names(ruletype $type): array {
        if (!$type instanceof ruletype_with_profile) {
            return $type->get_compatible_filters();
        }

        $profile = $type->get_profile();
        $subject = $profile->get_subject();

        if ($subject === profile::SUBJECT_CM) {
            return $this->get_cm_filter_names($profile);
        } else if ($subject === profile::SUBJECT_SECTION) {
            return $this->get_section_filter_names($profile);
        } else if ($subject === profile::SUBJECT_COURSE) {
            return $this->get_course_filter_names($profile);
        }

        return $this->get_unscoped_filter_names($profile);
    }

    /**
     * Get compatible filter names.
     *
     * @param profile $profile The profile.
     * @return string[]
     */
    protected function get_cm_filter_names(profile $profile): array {
        return ['cm', 'cmname', 'cmtag', 'anycm'];
    }

    /**
     * Get compatible filter names.
     *
     * @param profile $profile The profile.
     * @return string[]
     */
    protected function get_course_filter_names(profile $profile): array {
        return ['thiscourse', 'anycourse'];
    }

    /**
     * Get compatible filter names.
     *
     * @param profile $profile The profile.
     * @return string[]
     */
    protected function get_section_filter_names(profile $profile): array {
        return ['section', 'anysection'];
    }

    /**
     * Get compatible filter names.
     *
     * @param profile $profile The profile.
     * @return string[]
     */
    protected function get_unscoped_filter_names(profile $profile): array {
        return ['any'];
    }

}
