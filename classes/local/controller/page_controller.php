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
 * Page controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;

use coding_exception;
use block_xp\di;
use block_xp\local\course_world;
use block_xp\local\utils\text_utils;
use core\output\notification;
use html_writer;
use moodle_url;

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
    /** @var string The nav name. */
    protected $navname = null;
    /** @var string The route name. */
    protected $routename = null;
    /** @var bool Whether manage permissions ar required. */
    protected $requiremanage = true;
    /** @var bool Whether view permissions ar required. */
    protected $requireview = true;
    /** @var bool Whether the page is public. */
    protected $ispublic = false;

    /** @var ?array The nav items. */
    protected $navitems;

    /**
     * Permissions checks.
     *
     * @return void
     */
    protected function permissions_checks() {
        if ($this->world instanceof course_world) {
            $contextmode = di::get('config')->get('context');
            $issiteid = $this->get_param('courseid') == SITEID;

            if ($contextmode == CONTEXT_COURSE && $issiteid) {
                throw new \moodle_exception('errorcontextcoursemismatchpercourse', 'block_xp', (new moodle_url('/'))->out(false));
            } else if ($contextmode == CONTEXT_SYSTEM && !$issiteid) {
                $nexturl = $this->urlresolver->reverse($this->get_route_name(), ['courseid' => SITEID]);
                throw new \moodle_exception(
                    'errorcontextcoursemismatchforwholesite',
                    'block_xp',
                    $nexturl->get_compatible_url()->out(false)
                );
            }
        }

        // We only need one of, ordered in such a way that the most important check is done first.
        $accessperms = $this->world->get_access_permissions();
        if ($this->requiremanage) {
            $accessperms->require_manage();
        } else if ($this->requireview) {
            $accessperms->require_access();
        } else if (!$this->ispublic) {
            throw new coding_exception('Misconfigured controller. Is page public, or are permissions required?');
        }

        // Check whether the page is visible to viewers.
        if (!$accessperms->can_manage() && !$this->is_visible_to_viewers()) {
            throw new \moodle_exception('nopermissions', '', '', 'view_' . $this->get_route_name() . '_page');
        }
    }

    /**
     * The heading to display.
     *
     * @return string
     */
    abstract protected function get_page_heading();

    /**
     * The route name for the purpose of navigation.
     *
     * @return string
     */
    protected function get_navigation_route_name() {
        if ($this->navname === null) {
            return $this->get_route_name();
        }
        return $this->navname;
    }

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
     * Return the navigation items.
     *
     * @return array
     */
    protected function get_navigation_items() {
        if (!isset($this->navitems)) {
            $this->navitems = array_map(static function($item) {
                return $item->as_array();
            }, $this->navigator->get_navigation());
        }
        return $this->navitems;
    }

    /**
     * Return the sub navigation items.
     *
     * @return array
     */
    protected function get_sub_navigation_items() {
        $routename = $this->get_navigation_route_name();
        $links = $this->get_navigation_items();
        foreach ($links as $link) {
            if ($link['id'] === $routename) {
                return $link['children'] ?? [];
            }
        }
        return [];
    }

    /**
     * Whether the page has a sub navigation.
     *
     * @return bool
     */
    protected function has_sub_navigation() {
        return count($this->get_sub_navigation_items()) > 1;
    }

    /**
     * Whether the page is currently visible to viewers.
     *
     * This acts as a secondary check to determine whether viewers, that is the
     * users with the view permission, can view this page. This is not relevant
     * when the page requires manage access.
     *
     * Typically, this would be based on a config setting that would determine
     * whether a feature is enabled or not.
     *
     * @return bool
     */
    protected function is_visible_to_viewers() {
        return true;
    }

    /**
     * The content of the page.
     *
     * You probably want to look at {@see self::page_content} instead.
     *
     * @return void
     */
    protected function content() {
        $output = $this->get_renderer();

        // Warn users if the addon was deactivated.
        if ($this->world->get_access_permissions()->can_manage() && di::get('addon')->is_deactivated()) {
            echo $output->notification_without_close(text_utils::markdown_light(
                get_string('erroraddondeactivated', 'block_xp', [
                    'docsurl' => (new \moodle_url('https://docs.levelup.plus/xp/docs/addon-deactivated'))->out(false),
                ])
            ), notification::NOTIFY_ERROR);
        }

        $config = $this->world->get_config();
        $context = $this->world->get_context();
        $blocktitle = $config->get('blocktitle');
        if (empty($blocktitle)) {
            $blocktitle = get_string('levelup', 'block_xp');
        }
        echo $output->heading(format_string($blocktitle, true, ['context' => $context]));

        $this->page_navigation();

        echo html_writer::start_div('xp-w-full xp-flex xp-flex-col lg:xp-flex-row xp-gap-6');
        if ($this->has_sub_navigation()) {
            $this->page_sub_navigation();
        }
        echo html_writer::start_div('xp-flex-1 xp-w-full xp-min-w-px');
        $this->page_notices();
        $this->page_content();
        echo html_writer::end_div();
        echo html_writer::end_div();
    }

    /**
     * The page navigation.
     *
     * @return void
     */
    protected function page_navigation() {
        $output = $this->get_renderer();
        $items = $this->get_navigation_items();
        if (count($items) > 1) {
            echo $output->tab_navigation($items, $this->get_navigation_route_name());
        }
    }

    /**
     * The page sub navigation.
     *
     * @return void
     */
    protected function page_sub_navigation() {
        $output = $this->get_renderer();
        echo html_writer::start_div('xp-w-full lg:xp-w-36 xp-max-w-full');
        echo $output->sub_navigation($this->get_sub_navigation_items(), $this->get_route_name());
        echo html_writer::end_div();
    }

    /**
     * The page notices.
     *
     * @return void
     */
    protected function page_notices() {
        $output = $this->get_renderer();
        if ($this->world instanceof course_world) {
            echo $output->notices($this->world);
        }
    }

    /**
     * The page content.
     *
     * Echo the page content from here.
     *
     * @return void
     */
    abstract protected function page_content();

    /**
     * Footer.
     *
     * @return void
     */
    final protected function footer() {
        $world = $this->world;
        $accessperms = $world->get_access_permissions();
        $canmanageish = $accessperms->can_manage()
            || ($accessperms instanceof \block_xp\local\permission\access_logs_permissions && $accessperms->can_access_logs())
            || ($accessperms instanceof \block_xp\local\permission\access_report_permissions && $accessperms->can_access_report());
        if ($canmanageish) {
            echo $this->get_renderer()->render_from_template('block_xp/xp-footer', []);
        }
    }
}
