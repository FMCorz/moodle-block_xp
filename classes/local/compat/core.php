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
 * Compatibility file for IDE support of aliased classes.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// No declared namespace, on purpose!
defined('MOODLE_INTERNAL') || die();

// This file will never be autoloaded, and should never be included either. Its content
// will never be executed, even if it is loaded by accident. It is only here for compatibility
// reasons since Moodle has deprecated the top-level classes and Intelephense does not
// understand the class_alias function (https://github.com/bmewburn/vscode-intelephense/issues/600).
if (false) {

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class core_component extends core\component {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class emoticon_manager extends core\emoticon_manager {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class lang_string extends core\lang_string {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class core_plugin_manager extends core\plugin_manager {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class moodle_url extends core\url {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class core_user extends core\user {
    }
}
