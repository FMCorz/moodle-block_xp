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
    class external_api extends core_external\external_api {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class restricted_context_exception extends core_external\restricted_context_exception {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class external_description extends core_external\external_description {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class external_value extends core_external\external_value {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class external_format_value extends core_external\external_format_value {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class external_single_structure extends core_external\external_single_structure {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class external_multiple_structure extends core_external\external_multiple_structure {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class external_function_parameters extends core_external\external_function_parameters {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class external_util extends core_external\util {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class external_files extends core_external\external_files {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class external_warnings extends core_external\external_warnings {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class external_settings extends core_external\external_settings {
    }
}
