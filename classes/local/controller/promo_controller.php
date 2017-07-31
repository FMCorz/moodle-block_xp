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
 * @copyright  2017 Branch Up Pty Ltd
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
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class promo_controller extends route_controller {

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
            $PAGE->set_url($this->pageurl);
            $PAGE->set_pagelayout('course');
            $PAGE->set_title(get_string('levelupplus', 'block_xp'));
            $PAGE->set_heading(format_string($COURSE->fullname));
        }
    }

    /**
     * Whether we can send e-mails.
     *
     * @return bool
     */
    protected function supports_email() {
        global $CFG, $USER;
        return empty($CFG->noemailever) && !empty($USER->email) && validate_email($USER->email);
    }

    protected function pre_content() {
        global $USER, $CFG;

        $this->form = new \block_xp\form\promo();
        if (($data = $this->form->get_data()) && $this->supports_email()) {

            // Get a fake user account.
            $dummyuser = new \stdClass();
            $dummyuser->id = \core_user::NOREPLY_USER;
            $dummyuser->email = $this->email;
            $dummyuser->firstname = 'Level';
            $dummyuser->username = 'levelup';
            $dummyuser->lastname = 'Up';
            $dummyuser->confirmed = 1;
            $dummyuser->suspended = 0;
            $dummyuser->deleted = 0;
            $dummyuser->picture = 0;
            $dummyuser->auth = 'manual';
            $dummyuser->firstnamephonetic = '';
            $dummyuser->lastnamephonetic = '';
            $dummyuser->middlename = '';
            $dummyuser->alternatename = '';
            $dummyuser->imagealt = '';

            $message = "";
            $message .= $data->message;
            $message .= "\n\n";
            $message .= "----";
            $message .= "\nName: " . fullname($USER);
            $message .= "\nEmail: " . $USER->email;
            $message .= "\nSite: " . $CFG->wwwroot;

            $result = email_to_user($dummyuser, $USER->email, 'Level Up! Plus enquiry', $message);
            $url = new url($this->pageurl, ['sent' => $result ? 1 : -1]);

            $this->redirect($url);
        }
    }

    protected function content() {
        $output = \block_xp\di::get('renderer');

        $sent = $this->get_param('sent');
        if ($sent < 0) {
            echo $output->notification_without_close(get_string('promoerrorsendingemail', 'block_xp', $this->email), 'error');
        } else if ($sent > 0) {
            echo $output->notification_without_close(get_string('promoyourmessagewassent', 'block_xp'), 'success');
        }

        if (!$this->is_admin_page()) {
            echo $output->heading(get_string('levelupplus', 'block_xp'));
            echo $output->course_world_navigation($this->world, $this->routename);
        }

        echo $output->heading(get_string('discoverlevelupplus', 'block_xp'), 3);
        echo markdown_to_html(get_string('promointro', 'block_xp'));

        echo <<<EOT
<table>
    <tr>
        <td>
            <h4>New rules</h4>
            <p>Reward your students for completing their tasks.</p>
            <ul>
                <li>Support for activity completion.</li>
                <li>Support for course completion.</li>
            </ul>
        </td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <h4>Improved cheat guard</h4>
            <p>Better control over your students' earnings.</p>
            <ul>
                <li>Limit your students' earnings per day (or any other time window).</li>
                <li>Automatically stop sneaky students from abusing the system.</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>
            <h4>More styles</h4>
            <p>Make your learners feel at home with our brand new looks.</p>
            <ul>
                <li>Three brand new badges.</li>
            </ul>
        </td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <h4>Give them carrots!</h4>
            <p>Sick of experience points? Reward your students with something else!</p>
            <ul>
                <li>Carrots, gems, reputation points, ...</li>
                <li>Use any symbol you want!</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>
            <h4>Reporting at home</h4>
            <p>Export the report to look at it at ease.</p>
            <ul>
                <li>Various formats supported: Excel, CSV, OpenDocument, ...</li>
            </ul>
        </td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <h4>E-mail support</h4>
            <p>Get support by e-mail for up to 12 months.</p>
            <ul>
                <li>Report bugs for us to fix</li>
                <li>Get help with setting up the rules and settings</li>
                <li>Troubleshoot if issues were to arise</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>
            <h4>Support us</h4>
            <p>Purchasing the add-on directly contributes to the plugin.</p>
            <ul>
                <li>Bugs will be fixed</li>
                <li>Features will be added</li>
                <li>Feedback will be heard</li>
            </ul>
        </td>
        <td></td>
    </tr>
</table>
EOT;

        echo $output->heading(get_string('promocontactus', 'block_xp'), 3);
        echo html_writer::tag('p', get_string('promocontactintro', 'block_xp'));

        if ($this->supports_email()) {
            $this->form->display();
            echo html_writer::tag('p', markdown_to_html(get_string('promoifpreferemailusat', 'block_xp', $this->email)));

        } else {
            echo html_writer::tag('p', markdown_to_html(get_string('promoemailusat', 'block_xp', $this->email)));
        }

    }

}
