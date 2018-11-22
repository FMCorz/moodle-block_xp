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
 * Promo controller.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/adminlib.php');

use html_writer;
use block_xp\local\routing\url;

/**
 * Promo controller class.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class promo_controller extends route_controller {

    /** Seen flag. */
    const SEEN_FLAG = 'promo-page-seen';
    /** Page version. */
    const VERSION = 20181122;

    /** @var string The normal route name. */
    protected $routename = 'promo';
    /** @var string The admin section name. */
    protected $sectionname = 'block_xp_promo';
    /** @var string The email. */
    protected $email = 'levelup@branchup.tech';
    /** @var url_resolver The URL resolver. */
    protected $urlresolver;
    /** @var world The world. */
    protected $world;

    protected function define_optional_params() {
        return [
            ['sent', 0, PARAM_INT, false]
        ];
    }

    /**
     * Whether we are in an admin page.
     *
     * @return bool
     */
    protected function is_admin_page() {
        $params = $this->request->get_route()->get_params();
        return empty($params['courseid']);
    }

    protected function require_login() {
        global $CFG, $PAGE, $USER, $SITE, $OUTPUT;
        if ($this->is_admin_page()) {
            admin_externalpage_setup($this->sectionname, '', null, $this->pageurl->get_compatible_url());
        } else {
            $courseid = intval($this->get_param('courseid'));
            require_login($courseid);
        }
    }

    protected function post_login() {
        $this->urlresolver = \block_xp\di::get('url_resolver');
        if (!$this->is_admin_page()) {
            $this->world = \block_xp\di::get('course_world_factory')->get_world($this->get_param('courseid'));
        }
    }

    /**
     * Permission checks.
     *
     * @throws moodle_exception When the conditions are not met.
     * @return void
     */
    protected function permissions_checks() {
        if (!$this->is_admin_page()) {
            $this->world->get_access_permissions()->require_manage();
        }
    }

    /**
     * Moodle page specifics.
     *
     * @return void
     */
    protected function page_setup() {
        global $COURSE, $PAGE;
        if (!$this->is_admin_page()) {
            // Note that the context was set by require_login().
            $PAGE->set_url($this->pageurl->get_compatible_url());
            $PAGE->set_pagelayout('course');
            $PAGE->set_title(get_string('levelupplus', 'block_xp'));
            $PAGE->set_heading(format_string($COURSE->fullname));
        }
    }

    protected function content() {
        self::mark_as_seen();

        $output = \block_xp\di::get('renderer');
        $siteurl = "http://levelup.branchup.tech?utm_source=blockxp&utm_medium=promopage&utm_campaign=xppromo";

        if (!$this->is_admin_page()) {
            echo $output->heading(get_string('levelupplus', 'block_xp'));
            echo $output->course_world_navigation($this->world, $this->routename);
            echo $output->notices($this->world);
        }

        echo $output->heading(get_string('discoverlevelupplus', 'block_xp'), 3);
        echo markdown_to_html(get_string('promointro', 'block_xp'));

        echo <<<EOT
<style>
.block_xp-promo-table td:first-of-type {
    text-align: center;
    vertical-align: top;
    width: 110px;
    margin-top: 40px;
}
.block_xp-promo-table td:first-of-type img {
    height: 50px;
}
.block_xp-promo-table h4 {
    margin-top: 0;
}
.block_xp-promo-table h4,
.block_xp-promo-table td:first-of-type img {
    margin-top: 20px;
}
.block_xp-promo-table h4 .label {
    font-size: 14px;
}
</style>
<table class="block_xp-promo-table">
    <tr>
        <td><img src="{$output->pix_url('noun/checklist', 'block_xp')}" alt=""></td>
        <td>
            <h4>Additional rules <span class="label label-info">New</span></h4>
            <p>Reward your students for completing their tasks and courses.</p>
            <ul>
                <li>Support for activity completion</li>
                <li>Support for course completion</li>
                <li>Support for targetting specific courses</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/group', 'block_xp')}" alt=""></td>
        <td>
            <h4>Group leaderboards <span class="label label-info">New</span></h4>
            <p>Rank groups of learners based on their combined points.</p>
            <ul>
                <li>Compatible with course groups</li>
                <li>Collaboration and cohesion in a friendly competition</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/favorite-mobile', 'block_xp')}" alt=""></td>
        <td>
            <h4>Mobile app support</h4>
            <p>Level up! in the official Moodle Mobile app.</p>
            <ul>
                <li>See current level and progress</li>
                <li>Access the leaderboard</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/privacy', 'block_xp')}" alt=""></td>
        <td>
            <h4>Improved cheat guard</h4>
            <p>Better control over your students' rewards.</p>
            <ul>
                <li>Limit your students' rewards per day (or any other time window)</li>
                <li>More resilient to students' trickeries</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('level', 'block_xp')}" alt=""></td>
        <td>
            <h4>Additional badges</h4>
            <p>Make your learners feel at home with new looks.</p>
            <ul>
                <li>Three new sets of level badges</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/carrots', 'block_xp')}" alt=""></td>
        <td>
            <h4>Give them carrots!</h4>
            <p>Reward your students with something other than experience points.</p>
            <ul>
                <li>Carrots, gems, thumbs up, reputation points...</li>
                <li>It's your call, use any symbol you want!</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/export', 'block_xp')}" alt=""></td>
        <td>
            <h4>Export the report</h4>
            <p>Export the report to look at it in more detail.</p>
            <ul>
                <li>Various formats supported: Excel, CSV, OpenDocument, and more</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/help', 'block_xp')}" alt=""></td>
        <td>
            <h4>Email support</h4>
            <p>Get direct email support from our team.</p>
            <ul>
                <li>Let us help if something goes wrong</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/heart', 'block_xp')}" alt=""></td>
        <td>
            <h4>Support us</h4>
            <p>Purchasing the add-on directly contributes to the plugin's development.</p>
            <ul>
                <li>Bugs will be fixed</li>
                <li>Features will be added</li>
            </ul>
        </td>
    </tr>
</table>

<div style="text-align: center; margin-top: 2em">
    <p>To find out more, order or contact us:</p>
    <p><a class="btn btn-success btn-large btn-lg" href="{$siteurl}">
        Visit our website
    </a></p>
</div>
EOT;

    }

    /**
     * Check whether there is new content for the user.
     *
     * @return bool
     */
    public static function has_new_content() {
        global $USER;
        if (!isloggedin() || isguestuser()) {
            return false;
        }

        $indicator = \block_xp\di::get('user_generic_indicator');
        $value = $indicator->get_user_flag($USER->id, self::SEEN_FLAG);

        return $value < self::VERSION;
    }

    /**
     * Mark as the page seen.
     *
     * @return void
     */
    protected static function mark_as_seen() {
        global $USER;
        if (!isloggedin() || isguestuser()) {
            return false;
        }

        $indicator = \block_xp\di::get('user_generic_indicator');
        $value = $indicator->set_user_flag($USER->id, self::SEEN_FLAG, self::VERSION);
    }

}
