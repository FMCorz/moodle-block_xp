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
 * Dead simple request.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\routing;
defined('MOODLE_INTERNAL') || die();

/**
 * Dead simple request.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class request {

    protected $uri;
    protected $params;
    protected $route;
    protected $method;

    public function __construct($uri, array $params, $route, $method) {
        $this->uri = $uri;
        $this->params = $params;
        $this->route = $route;
        $this->method = $method;
    }

    public function get_method() {
        return $this->method;
    }

    public function get_params() {
        return $this->params;
    }

    public function get_route() {
        return $this->route;
    }

    public function get_uri() {
        return $this->uri;
    }

}
