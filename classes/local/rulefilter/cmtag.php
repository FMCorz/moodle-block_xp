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

use lang_string;

/**
 * Tag.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cmtag extends xpplusrequired {

    public function get_compatible_context_levels(): array {
        return [CONTEXT_SYSTEM, CONTEXT_COURSE];
    }

    public function get_display_name(): lang_string {
        return new lang_string('rulefiltercmtag', 'block_xp');
    }

    public function get_short_description(): lang_string {
        return new lang_string('rulefiltercmtagdesc', 'block_xp');
    }

    public function is_compatible_with_admin(): bool {
        return true;
    }

}
