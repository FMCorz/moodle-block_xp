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
// See <https://levelup.plus>.

/**
 * OAuth endpoint.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:ignore moodle.Files.MoodleInternal.MoodleInternalGlobalState
$route = '/';
if (!empty($_REQUEST['_r']) && is_string($_REQUEST['_r'])) {
    $route = $_REQUEST['_r'];
} else if (!empty($_SERVER['PATH_INFO']) && is_string($_SERVER['PATH_INFO'])) {
    $route = $_SERVER['PATH_INFO'];
}
$route = '/' . trim($route, '/');

// Only few possible routes needs the user's Moodle session.
if (!in_array($route, ['/tokens', '/authorize'], true)) {
    define('AJAX_SCRIPT', true);
    define('NO_MOODLE_COOKIES', true);
}

// @codingStandardsIgnoreLine
require(__DIR__ . '/../../config.php');

\block_xp\di::get('oauth_router')->dispatch();
