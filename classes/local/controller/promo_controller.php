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
    const VERSION = 20200430;

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

        $pluginman = \core_plugin_manager::instance();
        $localxp = $pluginman->get_plugin_info('local_xp');
        if ($localxp) {
            $this->content_installed();
            return;
        }

        $this->content_not_installed();
    }

    /**
     * Content when not installed.
     *
     * @return void
     */
    protected function content_not_installed() {
        $output = \block_xp\di::get('renderer');
        $siteurl = "https://levelup.plus?ref=plugin_promopage";

        if (!$this->is_admin_page()) {
            echo $output->heading(get_string('levelupplus', 'block_xp'));
            echo $output->course_world_navigation($this->world, $this->routename);
            echo $output->notices($this->world);
        }

        echo $output->heading(get_string('discoverlevelupplus', 'block_xp'), 3);
        echo markdown_to_html(get_string('promointro', 'block_xp'));

        $new = '<span class="label label-info">New</span>';

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
            <h4>Additional rules</h4>
            <p>Reward your students for completing their tasks and courses.</p>
            <ul>
                <li>Support for activity completion</li>
                <li>Support for course completion</li>
                <li>Support for targeting specific courses</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/grade', 'block_xp')}" alt=""></td>
        <td>
            <h4>Grade-based rewards</h4>
            <p>Reward students for their performance.</p>
            <ul>
                <li>Grades can directly contribute to students' points</li>
                <li>Our powerful interface helps you define which grades count</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/manual', 'block_xp')}" alt=""></td>
        <td>
            <h4>Issue individual rewards $new</h4>
            <p>Manually award points to specific students.</p>
            <ul>
                <li>A great way to reward offline or punctual actions</li>
                <li>Use our import feature to award points from a spreadsheet</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/group', 'block_xp')}" alt=""></td>
        <td>
            <h4>Team leaderboards</h4>
            <p>Rank groups of learners based on their combined points.</p>
            <ul>
                <li>Compatible with groups and cohorts</li>
                <li>Collaboration and cohesion in a friendly competition</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/favorite-mobile', 'block_xp')}" alt=""></td>
        <td>
            <h4>Mobile app support</h4>
            <p>Access Level up! in the official Moodle Mobile app.</p>
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
            <p>Get better control of students' rewards.</p>
            <ul>
                <li>Limit your students' rewards per day (or any other time limit you want to set)</li>
                <li>Get peace of mind with a more robust and resilient anti-cheat</li>
                <li>Increase the time limits to greater values</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/export', 'block_xp')}" alt=""></td>
        <td>
            <h4>Import, export &amp; report $new</h4>
            <p>Better control and information about your students' actions.</p>
            <ul>
                <li>Export the report to look at it in more details</li>
                <li>Allocate points in bulk from an imported CSV file</li>
                <li>Track the events with logs containing human-friendly descriptions and originating locations</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('level', 'block_xp')}" alt=""></td>
        <td>
            <h4>Additional badges</h4>
            <p>Celebrate learners achievements with more badges.</p>
            <ul>
                <li>Three new sets of level badges</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/carrots', 'block_xp')}" alt=""></td>
        <td>
            <h4>Dangle a carrot!</h4>
            <p>Motivate & reward your students with something other than experience points.</p>
            <ul>
                <li>Carrots, gems, thumbs up, reputation points...</li>
                <li>It's your call, use any symbol you want!</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/help', 'block_xp')}" alt=""></td>
        <td>
            <h4>Email support</h4>
            <p>Let us help if something goes wrong</p>
            <ul>
                <li>Get direct email support from our team.</li>
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
                <li>Requested features will be added</li>
            </ul>
        </td>
    </tr>
</table>

<div style="text-align: center; margin-top: 2em">
    <p><a class="btn btn-success btn-large btn-lg" href="{$siteurl}">
        Get Level up! Plus now!
    </a></p>
</div>
EOT;

    }

    protected function content_installed() {
        $output = \block_xp\di::get('renderer');
        $siteurl = new url('https://levelup.plus?ref=localxp_promopage');
        $docsurl = new url('https://levelup.plus/docs?ref=localxp_promopage');
        $recoverurl = new url('https://levelup.plus/recover?ref=localxp_promopage');
        $releasenotesurl = new url('https://levelup.plus/docs/topic/release-notes?ref=localxp_promopage');
        $upgradeurl = new url('https://levelup.plus/docs/article/upgrading-level-up?ref=localxp_promopage');
        $outofsyncurl = new url('https://levelup.plus/docs/article/plugins-out-of-sync?ref=localxp_promopage');
        $pluginman = \core_plugin_manager::instance();
        $localxp = $pluginman->get_plugin_info('local_xp');


        if (!$this->is_admin_page()) {
            echo $output->heading(get_string('levelupplus', 'block_xp'));
            echo $output->course_world_navigation($this->world, $this->routename);
        }

        if (!$localxp->is_installed_and_upgraded()) {
            echo $output->notification_without_close(get_string('addoninstallationerror', 'block_xp'), 'error');
            return;
        }

        if (self::versions_out_of_sync()) {
            echo $output->notification_without_close(markdown_to_html(get_string('pluginsoutofsync', 'block_xp', [
                'url' => $outofsyncurl->out(false)
            ])), 'error');
        }

        echo $output->heading(get_string('thankyou', 'block_xp'), 3);
        echo markdown_to_html(get_string('promointroinstalled', 'block_xp'));

        echo html_writer::tag('p', get_string('version', 'core') . ' ' . $localxp->release);

        echo $output->heading(get_string('additionalresources', 'block_xp'), 4);
        echo html_writer::start_tag('ul');
        echo html_writer::tag('li', html_writer::link($docsurl, get_string('documentation', 'block_xp')));
        echo html_writer::tag('li', html_writer::link($releasenotesurl, get_string('releasenotes', 'block_xp')));
        echo html_writer::tag('li', html_writer::link($upgradeurl, get_string('upgradingplugins', 'block_xp')));

        echo html_writer::end_tag('ul');
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

        return $value < self::VERSION || self::versions_out_of_sync();
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

    /**
     * Check whether the versions are out of sync.
     *
     * @return bool
     */
    protected static function versions_out_of_sync() {
        global $USER;

        if (!isloggedin() || isguestuser()) {
            return false;
        }

        $pluginman = \core_plugin_manager::instance();
        $blockxp = $pluginman->get_plugin_info('block_xp');
        $localxp = $pluginman->get_plugin_info('local_xp');
        if (!$localxp || !$localxp->is_installed_and_upgraded()) {
            return false;
        } else if (!$blockxp || !$blockxp->is_installed_and_upgraded()) {
            return false;
        }

        // Versions should have the same date.
        $blockxpversion = floor($blockxp->versiondb / 100);
        $localxpversion = floor($localxp->versiondb / 100);
        return $blockxpversion > $localxpversion;
    }
}
