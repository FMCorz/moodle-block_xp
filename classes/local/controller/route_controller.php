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
 * Route controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

use coding_exception;
use moodle_exception;
use moodle_url;

/**
 * Route controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class route_controller implements controller {

    /** @var context The context of the page. */
    protected $context;
    /** @var \block_xp\local\manager_interface The manager. */
    protected $manager;
    /** @var \block_xp\local\request The request. */
    protected $request;
    /** @var moodle_url The page URL, not relative to the router. */
    protected $pageurl;
    /** @var renderer_base A renderer. */
    protected $renderer;
    /** @var url_resolver URL resolver. */
    protected $urlresolver;

    /** @var array The combined request and optional parameters. */
    private $params;
    /** @var array The optional parameters. */
    private $optionalparams;

    /**
     * Define the page url.
     *
     * @return void
     */
    protected function define_pageurl() {
        $this->pageurl = new moodle_url($this->request->get_url());
        $this->pageurl->params($this->optionalparams);
    }

    /**
     * Authentication.
     *
     * @return void
     */
    protected function require_login() {
        $courseid = $this->get_param('courseid');
        if (!$courseid) {
            throw new coding_exception('Excepted a course ID parameter but got none.');
        }
        require_login($courseid);
    }

    /**
     * Post authentication.
     *
     * Use this to initialise objects which you'll need throughout the request.
     *
     * @return void
     */
    protected function post_login() {
        $this->manager = \block_xp\di::get('manager_factory')->get_manager($this->get_param('courseid'));
        $this->context = $this->manager->get_context();
        $this->urlresolver = \block_xp\di::get('url_resolver');
    }

    /**
     * Moodle page specifics.
     *
     * @return void
     */
    protected function page_setup() {
        global $PAGE;
        $PAGE->set_context($this->context);
        $PAGE->set_url($this->pageurl);
        $PAGE->set_pagelayout($this->get_page_layout());
        $PAGE->set_title($this->get_page_html_head_title());
        $PAGE->set_heading($this->get_page_title());
    }

    /**
     * Add here all permissions checks related to accessing the page.
     *
     * @throws moodle_exception When the conditions are not met.
     * @return void
     */
    abstract protected function permissions_checks();

    /**
     * The page layout to use.
     *
     * @return string
     */
    protected function get_page_layout() {
        return 'course';
    }

    /**
     * The page title (in <head>).
     *
     * @return string
     */
    abstract protected function get_page_html_head_title();

    /**
     * The page title.
     *
     * @return string
     */
    protected function get_page_title() {
        global $COURSE;
        return format_string($COURSE->fullname);
    }

    /**
     * Collect the parameters.
     *
     * @return void
     */
    final private function collect_params() {
        $this->collect_optional_params();
        $this->params = $this->request->get_route()->get_params() + $this->optionalparams;
    }

    /**
     * The optional params expected.
     *
     * Using this format:
     * [
     *     ['paramname', $defaultvalue, PARAM_TYPE],
     *     ...
     * ]
     *
     * @return array
     */
    protected function define_optional_params() {
        return [];
    }

    /**
     * Read and compute the optional params.
     *
     * This should only be used once, to read the parameters, refer to {@link self::get_param}.
     *
     * @return array
     */
    final private function collect_optional_params() {
        $this->optionalparams = array_reduce($this->define_optional_params(), function($carry, $data) {
            $carry[$data[0]] = optional_param($data[0], $data[1], $data[2]);
            return $carry;
        }, []);
    }

    /**
     * Read one of the parameters.
     *
     * This includes request, and GET/POST parameters.
     *
     * @param string $name The parameter name.
     * @return mixed
     */
    final protected function get_param($name) {
        if (!array_key_exists($name, $this->params)) {
            throw new \coding_exception('Unknown parameter: ' . $name);
        }
        return $this->params[$name];
    }

    /**
     * Get the renderer.
     *
     * @return renderer_base.
     */
    protected function get_renderer() {
        if (!$this->renderer) {
            $this->renderer = \block_xp\di::get('renderer');
        }
        return $this->renderer;
    }

    /**
     * Handle the request.
     *
     * @param \block_xp\local\routing\routed_request $request The request.
     * @return void
     */
    final public function handle(\block_xp\local\routing\request $request) {
        if (!$request instanceof \block_xp\local\routing\routed_request) {
            throw new coding_exception('Routed request must be used here...');
        }
        $this->request = $request;
        $this->collect_params();
        $this->define_pageurl();
        $this->require_login();
        $this->post_login();
        $this->permissions_checks();
        $this->page_setup();
        $this->pre_content();
        $this->start();
        $this->content();
        $this->end();
    }

    /**
     * What needs to be done prior to any output.
     *
     * This is the place you want to initiate redirections from.
     *
     * @return void
     */
    protected function pre_content() {
    }

    /**
     * Start the output.
     *
     * @return void
     */
    final protected function start() {
        echo $this->get_renderer()->header();
    }

    /**
     * Echo the content.
     *
     * @return void
     */
    abstract protected function content();

    /**
     * Finalise the output.
     *
     * @return void
     */
    final protected function end() {
        echo $this->get_renderer()->footer();
    }

    /**
     * Helper method to redirect.
     *
     * @param moodle_url $url The URL to go to.
     * @param string $message The redirect message.
     * @return void
     */
    final protected function redirect(moodle_url $url = null, $message = '') {
        if ($url === null) {
            $url = $this->pageurl;
        }
        redirect($url, $message);
    }
}
