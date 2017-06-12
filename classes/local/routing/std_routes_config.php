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
 * Dead simple routes config.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\routing;
defined('MOODLE_INTERNAL') || die();

use coding_exception;

/**
 * Dead simple routes config.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class std_routes_config implements routes_config {

    /** @var array Routes. */
    protected $routes;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->routes = [
            'home' => [
                'url' => '/',
                'regex' => '~$/^~',
                'controller' => 'index',
            ],
            'config' => [
                'url' => '/:courseid/config',
                'regex' => '~^/(\d+)/config$~',
                'controller' => 'config',
                'mapping' => [
                    1 => 'courseid'
                ]
            ],
            'infos' => [
                'url' => '/:courseid/infos',
                'regex' => '~^/(\d+)/infos$~',
                'controller' => 'infos',
                'mapping' => [
                    1 => 'courseid'
                ]
            ],
            'ladder' => [
                'url' => '/:courseid/ladder',
                'regex' => '~^/(\d+)/ladder$~',
                'controller' => 'ladder',
                'mapping' => [
                    1 => 'courseid'
                ]
            ],
            'levels' => [
                'url' => '/:courseid/levels',
                'regex' => '~^/(\d+)/levels$~',
                'controller' => 'levels',
                'mapping' => [
                    1 => 'courseid'
                ]
            ],
            'log' => [
                'url' => '/:courseid/log',
                'regex' => '~^/(\d+)/log$~',
                'controller' => 'log',
                'mapping' => [
                    1 => 'courseid'
                ]
            ],
            'report' => [
                'url' => '/:courseid/report',
                'regex' => '~^/(\d+)/report$~',
                'controller' => 'report',
                'mapping' => [
                    1 => 'courseid'
                ]
            ],
            'rules' => [
                'url' => '/:courseid/rules',
                'regex' => '~^/(\d+)/rules$~',
                'controller' => 'rules',
                'mapping' => [
                    1 => 'courseid'
                ]
            ],
            'visuals' => [
                'url' => '/:courseid/visuals',
                'regex' => '~^/(\d+)/visuals$~',
                'controller' => 'visuals',
                'mapping' => [
                    1 => 'courseid'
                ]
            ],
        ];
    }

    /**
     * Get a route.
     *
     * @param string $name The route name.
     * @return array
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
     * @return array
     */
    public function get_routes() {
        return $this->routes;
    }

}
