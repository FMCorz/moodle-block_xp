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

namespace block_xp\local\controller;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/adminlib.php');

use block_xp\di;
use html_writer;
use block_xp\local\routing\url;
use block_xp\local\utils\text_utils;
use core\output\notification;
use moodle_url;

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
    const VERSION = 20260818;

    /** @var string The normal route name. */
    protected $routename = 'promo';
    /** @var string The admin section name. */
    protected $sectionname = 'block_xp_promo';
    /** @var url_resolver The URL resolver. */
    protected $urlresolver;
    /** @var world The world. */
    protected $world;

    /**
     * Define optional parameters.
     *
     * @return array
     */
    protected function define_optional_params() {
        return [
            ['sent', 0, PARAM_INT, false],
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

    /**
     * Require login.
     *
     * @return void
     */
    protected function require_login() {
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

    /**
     * Handle post-login.
     *
     * @return void
     */
    protected function post_login() {
        $this->urlresolver = \block_xp\di::get('url_resolver');
        if (!$this->is_admin_page()) {
            $this->world = \block_xp\di::get('course_world_factory')->get_world($this->get_param('courseid'));
        }
    }

    /**
     * Permission checks.
     *
     * @throws \moodle_exception When the conditions are not met.
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

    /**
     * Get content.
     *
     * @return void
     */
    protected function content() {
        global $USER;

        $indicator = \block_xp\di::get('user_generic_indicator');
        $seenflag = $indicator->get_user_flag($USER->id, self::SEEN_FLAG);
        $hasnewcontent = $seenflag !== null && $seenflag < static::VERSION;
        self::mark_as_seen();

        // Warn users if the addon was deactivated.
        if (di::get('addon')->is_deactivated()) {
            echo di::get('renderer')->notification_without_close(text_utils::markdown_light(
                get_string('erroraddondeactivated', 'block_xp', [
                    'docsurl' => (new \moodle_url('https://docs.levelup.plus/xp/docs/addon-deactivated'))->out(false),
                ])
            ), notification::NOTIFY_ERROR);
        }

        $addon = \block_xp\di::get('addon');
        if ($addon->is_activated()) {
            $this->content_installed($hasnewcontent);
            return;
        }

        $this->content_not_installed();
    }

    /**
     * Get the call-to-action URL.
     *
     * @return moodle_url|null
     */
    protected function get_cta_url(): ?moodle_url {
        if ($this->get_cta_mode() < 8) {
            return null;
        } else if ($this->get_cta_mode() < 16) {
            return new moodle_url('https://docs.levelup.plus/xp/docs#xp-plus', ['ref' => 'plugin_promopage']);
        }
        return new moodle_url('https://www.levelup.plus/xp/', ['ref' => 'plugin_promopage']);
    }

    /**
     * Get the call-to-action label.
     *
     * @return string|null
     */
    protected function get_cta_label(): ?string {
        if ($this->get_cta_mode() < 8) {
            return null;
        } else if ($this->get_cta_mode() < 16) {
            return get_string('learnmore', 'block_xp');
        }
        return get_string('promogetnow', 'block_xp');
    }

    /**
     * Get the call-to-action message.
     *
     * @return string|null
     */
    protected function get_cta_message(): ?string {
        if ($this->get_cta_mode() < 4) {
            return null;
        } else if ($this->get_cta_mode() < 8) {
            return get_string('promoaskadmin', 'block_xp');
        }
        return null;
    }

    /**
     * Get the call-to-action mode.
     *
     * @return int
     */
    protected function get_cta_mode(): int {
        return (int) di::get('config')->get('promoctamode');
    }

    /**
     * Content when not installed.
     *
     * @return void
     */
    protected function content_not_installed() {
        $output = \block_xp\di::get('renderer');

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

        $ctaurl = $this->get_cta_url();
        $ctalabel = $this->get_cta_label();
        $hascta = $ctaurl !== null && $ctalabel !== null;
        $ctamessage = $this->get_cta_message();

        echo $output->render_from_template('block_xp/promo', [
            'title' => get_string('discoverlevelupplus', 'block_xp'),
            'intro' => get_string('promointro', 'block_xp'),
            'hascta' => $hascta,
            'hasfooter' => $hascta || $ctamessage !== null,
            'ctaurl' => $hascta ? $ctaurl->out(false) : null,
            'ctalabel' => $hascta ? $ctalabel : null,
            'ctamessage' => $ctamessage,
        ]);
    }

    /**
     * Output installed content.
     *
     * @param bool $hasnewcontent Whether there is new content.
     * @return void
     */
    protected function content_installed(bool $hasnewcontent = false) {
        $output = \block_xp\di::get('renderer');
        $addon = \block_xp\di::get('addon');

        $pluginman = \core_plugin_manager::instance();
        $blockxp = $pluginman->get_plugin_info('block_xp');
        $localxp = $pluginman->get_plugin_info('local_xp');

        $docsurl = new url('https://docs.levelup.plus/xp/docs?ref=plugin_promopage');
        $releasenotesurl = new url('https://docs.levelup.plus/xp/release-notes', [
            'ref' => 'plugin_promopage',
            'xp' => $blockxp->release,
            'xpp' => $localxp->release,
        ]);
        $upgradeurl = new url('https://docs.levelup.plus/xp/docs/upgrade?ref=plugin_promopage');
        $outofsyncurl = new url('https://docs.levelup.plus/xp/docs/requirements-compatibility?ref=plugin_promopage#out-of-sync');

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
                'url' => $outofsyncurl->out(false),
            ])), 'error');
        }

        if ($hasnewcontent) {
            $notification = new notification(strip_tags(markdown_to_html(get_string('newversioninstallednotice', 'block_xp', [
                'releasenotesurl' => $releasenotesurl->out(false),
            ])), '<a>'), notification::NOTIFY_INFO, false);
            echo $output->render($notification);
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
     * Whether the page should be visible.
     *
     * It should be visible when:
     * - Promo is enabled, or
     * - The addon is activated, or
     * - The addon should be activated but isn't.
     *
     * @return bool
     */
    public static function is_visible() {
        $addon = di::get('addon');
        return $addon->is_activated() || $addon->is_deactivated() || $addon->is_promo_allowed();
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
