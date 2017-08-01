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
 * Page controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

use coding_exception;
use moodle_exception;

/**
 * Page controller class.
 *
 * This is used for typical pages, it handles the heading, navigation,
 * typical capability checks, etc...
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class page_controller extends course_route_controller {

    /** @var string The route name. */
    protected $routename = null;
    /** @var bool Whether manage permissions ar required. */
    protected $requiremanage = true;
    /** @var bool Whether view permissions ar required. */
    protected $requireview = true;
    /** @var bool Whether the page is public. */
    protected $ispublic = false;

    /**
     * Permissions checks.
     *
     * @return void
     */
    protected function permissions_checks() {
        // We only need one of, ordered in such a way that the most important check is done first.
        if ($this->requiremanage) {
            $this->world->get_access_permissions()->require_manage();
        } else if ($this->requireview) {
            $this->world->get_access_permissions()->require_access();
        } else if (!$this->ispublic) {
            throw new coding_exception('Misconfigured controller. Is page public, or are permissions required?');
        }
    }

    /**
     * The heading to display.
     *
     * @return string
     */
    abstract protected function get_page_heading();

    /**
     * The route name as defined by the controller.
     *
     * @return string
     */
    protected function get_route_name() {
        if ($this->routename === null) {
            throw new coding_exception('Invalid route name.');
        }
        return $this->routename;
    }

    /**
     * The content of the page.
     *
     * You probably want to look at {@link self::page_content} instead.
     *
     * @return void
     */
    protected function content() {
        $output = $this->get_renderer();
        echo $output->heading($this->get_page_heading());
        $this->page_navigation();
        $this->page_notices();
        $this->page_content();
    }

    /**
     * The page navigation.
     *
     * @return void
     */
    protected function page_navigation() {
        $output = $this->get_renderer();
        echo $output->course_world_navigation($this->world, $this->get_route_name());
    }

    /**
     * The page notices.
     *
     * @return void
     */
    protected function page_notices() {
        $output = $this->get_renderer();
        echo $output->notices($this->world);
    }

    /**
     * The page content.
     *
     * Echo the page content from here.
     *
     * @return void
     */
    abstract protected function page_content();

}
