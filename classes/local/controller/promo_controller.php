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

use block_xp\di;
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
    const VERSION = 20220415;

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

    /**
     * The course page navigation.
     *
     * @return void
     */
    protected function page_course_navigation() {
        $output = $this->get_renderer();
        $items = di::get('course_world_navigation_factory')->get_course_navigation($this->world);
        if (count($items) > 1) {
            return $output->tab_navigation($items, $this->routename);
        }
        return '';
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
            $PAGE->add_body_class('limitedwidth');
        }
    }

    protected function content() {
        self::mark_as_seen();

        $addon = \block_xp\di::get('addon');
        if ($addon->is_activated()) {
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
            $config = $this->world->get_config();
            $context = $this->world->get_context();
            $blocktitle = $config->get('blocktitle');
            if (empty($blocktitle)) {
                $blocktitle = get_string('levelup', 'block_xp');
            }
            echo $output->heading(format_string($blocktitle, true, ['context' => $context]));
            echo $this->page_course_navigation();
            echo $output->notices($this->world);
        }

        echo $output->heading(get_string('discoverlevelupplus', 'block_xp'), 3);
        echo markdown_to_html(get_string('promointro', 'block_xp'));

        $new = '🆕';

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

<div style="text-center; margin: 1rem 0">
    <p><a class="btn btn-primary" href="{$siteurl}">
        Get Level Up XP+ now!
    </a></p>
</div>

<table class="block_xp-promo-table">
    <tr>
        <td><img src="{$output->pix_url('noun/checklist', 'block_xp')}" alt=""></td>
        <td>
            <h4>Additional reward methods $new</h4>
            <p>More control and methods to award points!</p>
            <ul>
            <li><strong>Drops</strong>: award points by placing code snippets anywhere $new</li>
            <li>Convert <strong>grades</strong> into points</li>
            <li>Reward <strong>activity</strong> and <strong>course completion</strong></li>
            </ul>
            <p>Plus convenient rules to:</p>
            <ul>
                <li>Target specific courses</li>
                <li>Target activities by name</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/manual', 'block_xp')}" alt=""></td>
        <td>
            <h4>Issue individual rewards</h4>
            <p>Manually award points to specific learners.</p>
            <ul>
                <li>A great way to <strong>reward offline</strong> or punctual <strong>actions</strong></li>
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
                <li>Make <strong>teams from groups</strong> and cohorts</li>
                <li>Collaboration and cohesion in a friendly competition</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/privacy', 'block_xp')}" alt=""></td>
        <td>
            <h4>Improved cheat guard</h4>
            <p>Get better control of learners' rewards.</p>
            <ul>
                <li><strong>Limit</strong> your learners' <strong>rewards</strong> per day (or other time frames)</li>
                <li>Get peace of mind with a more <strong>robust</strong> and resilient anti-cheat</li>
                <li><strong>Increase</strong> the <strong>time limits</strong> to greater values</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/export', 'block_xp')}" alt=""></td>
        <td>
            <h4>Import, export &amp; report $new</h4>
            <p>Better control and information about your learners' actions.</p>
            <ul>
                <li><strong>Export everything</strong>: leaderboards, logs and reports $new</li>
                <li>Allocate <strong>points in bulk</strong> from an imported CSV file</li>
                <li>Logs contain <strong>human-friendly</strong> descriptions and originating locations</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/carrots', 'block_xp')}" alt=""></td>
        <td>
            <h4>Change the meaning of points</h4>
            <p>Replace the "XP" symbol to give another meaning to the points awarded.</p>
            <ul>
                <li>Use carrots, gems, thumbs up, reputation points...</li>
                <li>It's your call, <strong>use any symbol</strong> you want!</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/favorite-mobile', 'block_xp')}" alt=""></td>
        <td>
            <h4>Mobile app support</h4>
            <p>Access Level Up XP in the official <strong>Mobile app</strong>.</p>
            <ul>
                <li>See current level and progress</li>
                <li>Access the leaderboard</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('level', 'block_xp')}" alt=""></td>
        <td>
            <h4>Additional level badges</h4>
            <p>Celebrate learners achievements with more badges.</p>
            <ul>
                <li><strong>Three new sets</strong> of level badges</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><img src="{$output->pix_url('noun/help', 'block_xp')}" alt=""></td>
        <td>
            <h4>Email support</h4>
            <p>Let us help if something goes wrong</p>
            <ul>
                <li>Get direct <strong>email support</strong> from our team.</li>
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

<div style="text-align: center; margin: 1rem 0">
    <p><a class="btn btn-primary btn-large btn-lg" href="{$siteurl}">
        Get Level Up XP+ now!
    </a></p>
</div>
EOT;

    }

    protected function content_installed() {
        $output = \block_xp\di::get('renderer');
        $addon = \block_xp\di::get('addon');
        $siteurl = new url('https://levelup.plus?ref=localxp_promopage');
        $docsurl = new url('https://levelup.plus/docs?ref=localxp_promopage');
        $recoverurl = new url('https://levelup.plus/recover?ref=localxp_promopage');
        $releasenotesurl = new url('https://levelup.plus/docs/topic/release-notes?ref=localxp_promopage');
        $upgradeurl = new url('https://levelup.plus/docs/article/upgrading-level-up?ref=localxp_promopage');
        $outofsyncurl = new url('https://levelup.plus/docs/article/plugins-out-of-sync?ref=localxp_promopage');

        if (!$this->is_admin_page()) {
            $config = $this->world->get_config();
            $context = $this->world->get_context();
            $blocktitle = $config->get('blocktitle');
            if (empty($blocktitle)) {
                $blocktitle = get_string('levelup', 'block_xp');
            }
            echo $output->heading(format_string($blocktitle, true, ['context' => $context]));
            echo $this->page_course_navigation();
        }

        if (!$addon->is_installed_and_upgraded()) {
            echo $output->notification_without_close(get_string('addoninstallationerror', 'block_xp'), 'error');
            echo html_writer::tag('p', get_string('version', 'core') . ' ' . $addon->get_release());
            return;
        }

        if ($addon->is_out_of_sync()) {
            echo $output->notification_without_close(markdown_to_html(get_string('pluginsoutofsync', 'block_xp', [
                'url' => $outofsyncurl->out(false)
            ])), 'error');
        }

        echo $output->heading(get_string('thankyou', 'block_xp'), 3);
        echo markdown_to_html(get_string('promointroinstalled', 'block_xp'));

        echo html_writer::tag('p', get_string('version', 'core') . ' ' . $addon->get_release());

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
        $addon = \block_xp\di::get('addon');
        $value = $indicator->get_user_flag($USER->id, self::SEEN_FLAG);

        return $value < self::VERSION || $addon->is_out_of_sync();
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
