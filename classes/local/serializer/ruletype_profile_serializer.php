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

use block_xp\local\ruletype\profile\profile;

/**
 * Rule type profile serializer.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ruletype_profile_serializer implements serializer {

    /**
     * Serialize.
     *
     * @param profile $profile The profile.
     * @return array
     */
    public function serialize($profile) {
        return [
            'subject' => $profile->get_subject(),
            'cmtype' => $profile->get_cm_type(),
            'requirescompletionenabled' => $profile->get_requires_completion_enabled(),
        ];
    }

}
