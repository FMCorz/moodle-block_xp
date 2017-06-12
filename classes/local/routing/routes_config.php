<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Routes config interface.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\routing;
defined('MOODLE_INTERNAL') || die();

/**
 * Routes config interface.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface routes_config {

    /**
     * Get a route.
     *
     * @param string $name The route name.
     * @return array
     */
    public function get_route($name);

    /**
     * Return an array of routes.
     *
     * The keys are the route names.
     *
     * Each route in the array should contain the following:
     *
     * - url (string) The URL to the page, with placeholders (:name).
     * - regex (string) The regex the route should match.
     * - controller (string) The name of the controller.
     *
     * Additionally, routes can contain the following:
     *
     * - mapping (array) An array where the keys are the group numbers from the
     *                   regex argument, and the values are the parameter names.
     *
     * @return array
     */
    public function get_routes();

}
