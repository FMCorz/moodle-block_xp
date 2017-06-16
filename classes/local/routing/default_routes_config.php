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
 * Routes config.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\routing;
defined('MOODLE_INTERNAL') || die();

use coding_exception;

/**
 * Routes config.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_routes_config implements routes_config {

    /** @var array Routes. */
    protected $routes;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->routes = [
            'home' => new route_definition(
                'home',
                '/',
                '~$/^~',
                'index'
            ),
            'config' => new route_definition(
                'config',
                '/config/:courseid',
                '~^/config/(\d+)$~',
                'config',
                [
                    1 => 'courseid'
                ]
            ),
            'infos' => new route_definition(
                'infos',
                '/infos/:courseid',
                '~^/infos/(\d+)$~',
                'infos',
                [
                    1 => 'courseid'
                ]
            ),
            'ladder' => new route_definition(
                'ladder',
                '/ladder/:courseid',
                '~^/ladder/(\d+)$~',
                'ladder',
                [
                    1 => 'courseid'
                ]
            ),
            'levels' => new route_definition(
                'levels',
                '/levels/:courseid',
                '~^/levels/(\d+)$~',
                'levels',
                [
                    1 => 'courseid'
                ]
            ),
            'log' => new route_definition(
                'log',
                '/log/:courseid',
                '~^/log/(\d+)$~',
                'log',
                [
                    1 => 'courseid'
                ]
            ),
            'report' => new route_definition(
                'report',
                '/report/:courseid',
                '~^/report/(\d+)$~',
                'report',
                [
                    1 => 'courseid'
                ]
            ),
            'rules' => new route_definition(
                'rules',
                '/rules/:courseid',
                '~^/rules/(\d+)$~',
                'rules',
                [
                    1 => 'courseid'
                ]
            ),
            'visuals' => new route_definition(
                'visuals',
                '/visuals/:courseid',
                '~^/visuals/(\d+)$~',
                'visuals',
                [
                    1 => 'courseid'
                ]
            ),
        ];
    }

    /**
     * Get a route.
     *
     * @param string $name The route name.
     * @return route_definition
     */
    public function get_route($name) {
        if (!isset($this->routes[$name])) {
            throw new coding_exception('Unknown route.');
        }
        return $this->routes[$name];
    }

    /**
     * Return an array of routes.
     *
     * @return route_definition[]
     */
    public function get_routes() {
        return $this->routes;
    }

}
