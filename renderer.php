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
 * Block XP renderer.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use block_xp\local\course_world;
use block_xp\local\activity\activity;
use block_xp\local\routing\url_resolver;
use block_xp\local\xp\level;
use block_xp\local\xp\level_with_badge;
use block_xp\local\xp\level_with_name;
use block_xp\local\xp\state;
use block_xp\output\xp_widget;

/**
 * Block XP renderer class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_renderer extends plugin_renderer_base {

    /** @var string Notices flag. */
    protected $noticesflag = 'block_xp_notices';

    /**
     * Print a level's badge.
     *
     * @param level $level The level.
     * @return string
     */
    public function level_badge(level $level) {
        return $this->level_badge_with_options($level);
    }

    /**
     * Small level badge.
     *
     * @param level $level The level.
     * @return string.
     */
    public function small_level_badge(level $level) {
        return $this->level_badge_with_options($level, ['small' => true]);
    }

    /**
     * Print a level's badge.
     *
     * @param level $level The level.
     * @return string
     */
    protected function level_badge_with_options(level $level, array $options = []) {
        $levelnum = $level->get_level();
        $classes = 'block_xp-level level-' . $levelnum;
        $label = get_string('levelx', 'block_xp', $levelnum);

        if (!empty($options['small'])) {
            $classes .= ' small';
        }

        $html = '';
        if ($level instanceof level_with_badge && ($badgeurl = $level->get_badge_url()) !== null) {
            $html .= html_writer::tag(
                'div',
                html_writer::empty_tag('img', ['src' => $badgeurl,
                    'alt' => $label]),
                ['class' => $classes . ' level-badge']
            );
        } else {
            $html .= html_writer::tag('div', $levelnum, ['class' => $classes, 'aria-label' => $label]);
        }
        return $html;
    }

    /**
     * Level name.
     *
     * @param level $level The level.
     * @param bool $force When forced, there will always be an name displayed.
     * @return string
     */
    public function level_name(level $level, $force = false) {
        $name = $level instanceof level_with_name ? $level->get_name() : null;
        if (empty($name) && $force) {
            $name = get_string('levelx', 'block_xp', $level->get_level());
        }
        if (empty($name)) {
            return '';
        }
        return html_writer::tag('div', $name, ['class' => 'level-name']);
    }


    /**
     * Levels grid.
     *
     * @param array $levels The levels.
     * @return string
     */
    public function levels_grid(array $levels) {

        // If at least one level has a custom name, we will always show the name.
        $alwaysshowname = array_reduce($levels, function($carry, $level) {
            $name = $level instanceof \block_xp\local\xp\level_with_name ? $level->get_name() : '';
            return $carry + !empty($name) ? 1 : 0;
        }, 0) > 0;

        $o = '';
        $o .= html_writer::start_div('block_xp-level-grid');
        foreach ($levels as $level) {
            $desc = $level instanceof \block_xp\local\xp\level_with_description ? $level->get_description() : '';
            $classes = ['block_xp-level-boxed'];
            if ($desc) {
                $classes[] = 'block_xp-level-boxed-with-desc';
            }

            $o .= html_writer::start_div(implode(' ', $classes));
            $o .= html_writer::start_div('block_xp-level-box');
            $o .= html_writer::start_div('block_xp-level-no');
            $o .= '#' . $level->get_level();
            $o .= html_writer::end_div();
            $o .= html_writer::start_div();
            $o .= $this->level_badge($level);
            $o .= html_writer::end_div();
            $o .= $this->level_name($level, $alwaysshowname);
            $o .= html_writer::start_div();
            $o .= $this->xp($level->get_xp_required());
            $o .= html_writer::end_div();
            $o .= html_writer::start_div('block_xp-level-desc');
            $o .= $desc;
            $o .= html_writer::end_div();
            $o .= html_writer::end_div();
            $o .= html_writer::end_div();
        }
        $o .= html_writer::end_div();
        return $o;
    }

    /**
     * Levels preview.
     *
     * @param level[] $levels The levels.
     * @return string
     */
    public function levels_preview(array $levels) {
        $o = '';

        $o .= html_writer::start_div('block_xp-level-preview');
        foreach ($levels as $level) {
            $o .= html_writer::start_div('previewed-level');
            $o .= html_writer::div('#' . $level->get_level(), 'level-name');
            $o .= $this->small_level_badge($level);
            $o .= html_writer::end_div();
        }
        $o .= html_writer::end_div();

        return $o;
    }

    /**
     * Return the notices.
     *
     * @param \block_xp\local\course_world $world The world.
     * @return string The notices.
     */
    public function notices(\block_xp\local\course_world $world) {
        global $CFG;
        $o = '';

        if (!$world->get_access_permissions()->can_manage()) {
            return $o;
        }

        if (!get_user_preferences($this->noticesflag, false)) {
            require_once($CFG->libdir . '/ajax/ajaxlib.php');
            user_preference_allow_ajax_update($this->noticesflag, PARAM_BOOL);

            $moodleorgurl = new moodle_url('https://moodle.org/plugins/view.php?plugin=block_xp');
            $githuburl = new moodle_url('https://github.com/FMCorz/moodle-block_xp');
            $text = get_string('likenotice', 'block_xp', (object) array(
                'moodleorg' => $moodleorgurl->out(),
                'github' => $githuburl->out()
            ));

            $this->page->requires->js_init_call("Y.one('.block-xp-rocks').on('click', function(e) {
                e.preventDefault();
                M.util.set_user_preference('" . $this->noticesflag . "', 1);
                Y.one('.block-xp-notices').hide();
            });");

            $icon = new pix_icon('t/close', get_string('dismissnotice', 'block_xp'), 'block_xp');
            $actionicon = $this->action_icon(new moodle_url($this->page->url), $icon, null, array('class' => 'block-xp-rocks'));
            $text .= html_writer::div($actionicon, 'dismiss-action');
            $o .= html_writer::div($this->notification_without_close($text, 'success'),
                'block_xp-dismissable-notice block-xp-notices');
        }

        return $o;
    }

    /**
     * Outputs the navigation.
     *
     * This specifically requires a course_world and not a world.
     *
     * @param course_world $world The world.
     * @param string $page The page we are on.
     * @return string The navigation.
     */
    public function course_world_navigation(course_world $world, $page) {
        $factory = \block_xp\di::get('course_world_navigation_factory');
        $links = $factory->get_course_navigation($world);

        // If there is only one page, then that is the page we are on.
        if (count($links) <= 1) {
            return '';
        }

        $tabs = array_map(function($link) {
            return new tabobject($link['id'], $link['url'], $link['text'], clean_param($link['text'], PARAM_NOTAGS));
        }, $links);

        return html_writer::div($this->tabtree($tabs, $page), 'block_xp-page-nav');
    }

    /**
     * New dot.
     *
     * @return string
     */
    public function new_dot() {
        return html_writer::div(html_writer::tag('span', get_string('new'), ['class' => 'accesshide']), 'has-new');
    }

    /**
     * Print a notification without a close button.
     *
     * @param string|lang_string $message The message.
     * @param string $type The notification type.
     * @return string
     */
    public function notification_without_close($message, $type) {
        if (class_exists('core\output\notification')) {

            // Of course, it would be too easy if they didn't add and change constants
            // between two releases... Who reads the upgrade.txt, seriously?
            if (defined('core\output\notification::NOTIFY_INFO')) {
                $info = core\output\notification::NOTIFY_INFO;
            } else {
                $info = core\output\notification::NOTIFY_MESSAGE;
            }
            if (defined('core\output\notification::NOTIFY_SUCCESS')) {
                $success = core\output\notification::NOTIFY_SUCCESS;
            } else {
                $success = core\output\notification::NOTIFY_MESSAGE;
            }
            if (defined('core\output\notification::NOTIFY_WARNING')) {
                $warning = core\output\notification::NOTIFY_WARNING;
            } else {
                $warning = core\output\notification::NOTIFY_REDIRECT;
            }
            if (defined('core\output\notification::NOTIFY_ERROR')) {
                $error = core\output\notification::NOTIFY_ERROR;
            } else {
                $error = core\output\notification::NOTIFY_PROBLEM;;
            }

            $typemappings = [
                'success'           => $success,
                'info'              => $info,
                'warning'           => $warning,
                'error'             => $error,
                'notifyproblem'     => $error,
                'notifytiny'        => $error,
                'notifyerror'       => $error,
                'notifysuccess'     => $success,
                'notifymessage'     => $info,
                'notifyredirect'    => $info,
                'redirectmessage'   => $info,
            ];
        } else {
            // Old-style notifications.
            $typemappings = [
                'success'           => 'notifysuccess',
                'info'              => 'notifymessage',
                'warning'           => 'notifyproblem',
                'error'             => 'notifyproblem',
                'notifyproblem'     => 'notifyproblem',
                'notifytiny'        => 'notifyproblem',
                'notifyerror'       => 'notifyproblem',
                'notifysuccess'     => 'notifysuccess',
                'notifymessage'     => 'notifymessage',
                'notifyredirect'    => 'notifyredirect',
                'redirectmessage'   => 'redirectmessage',
            ];
        }

        $type = $typemappings[$type];

        if (class_exists('core\output\notification')) {
            $notification = new \core\output\notification($message, $type);
            if (method_exists($notification, 'set_show_closebutton')) {
                $notification->set_show_closebutton(false);
            }
            return $this->render($notification);
        }

        return $this->notification($message, $type);
    }

    /**
     * Page size selector.
     *
     * @param array $options Array of [(int) $perpage, (moodle_url) $url].
     * @param int $current The current selectin.
     * @return string
     */
    public function pagesize_selector($options, $current) {
        $o = '';
        $o .= html_writer::start_div('text-right');
        $o .= html_writer::start_tag('small');
        $o .= get_string('perpagecolon', 'block_xp') . ' ';

        $options = array_values($options);
        $lastindex = count($options) - 1;

        foreach ($options as $i => $option) {
            list($perpage, $url) = $option;
            $o .= $current == $perpage ? $current : html_writer::link($url, (string) $perpage);
            $o .= $i < $lastindex ? ' - ' : '';
        }

        $o .= html_writer::end_tag('small');
        $o .= html_writer::end_div();
        return $o;
    }

    /**
     * Override pix_url to auto-handle deprecation.
     *
     * It's just simpler than having to deal with differences between
     * Moodle < 3.3, and Moodle >= 3.3.
     *
     * @param string $image The file.
     * @param string $component The component.
     * @return string
     */
    public function pix_url($image, $component = 'moodle') {
        if (method_exists($this, 'image_url')) {
            return $this->image_url($image, $component);
        }
        return parent::pix_url($image, $component);
    }

    /**
     * Override render method.
     *
     * @param renderable $renderable The renderable.
     * @param array $options Options.
     * @return string
     */
    public function render(renderable $renderable, $options = array()) {
        if ($renderable instanceof block_xp_ruleset) {
            return $this->render_block_xp_ruleset($renderable, $options);
        } else if ($renderable instanceof block_xp_rule) {
            return $this->render_block_xp_rule($renderable, $options);
        }
        return parent::render($renderable);
    }

    /**
     * Renders a block XP filter.
     *
     * Not very proud of the way I implement this... The HTML is tied to Javascript
     * and to the rule objects themselves. Careful when changing something!
     *
     * @param block_xp_filter $filter The filter.
     * @return string
     */
    public function render_block_xp_filter($filter) {
        static $i = 0;
        $o = '';
        $basename = 'filters[' . $i++ . ']';

        $o .= html_writer::start_tag('li', array('class' => 'filter', 'data-basename' => $basename));

        if ($filter->is_editable()) {

            $content = $this->render(new pix_icon('i/dragdrop', get_string('moverule', 'block_xp'), '',
                array('class' => 'iconsmall filter-move')));
            $content .= get_string('awardaxpwhen', 'block_xp',
                html_writer::empty_tag('input', array(
                    'type' => 'text',
                    'value' => $filter->get_points(),
                    'size' => 3,
                    'name' => $basename . '[points]',
                    'class' => 'form-control block_xp-form-control-inline'))
            );
            $content .= $this->action_link('#', '', null, array('class' => 'filter-delete'),
                new pix_icon('t/delete', get_string('deleterule', 'block_xp'), '', array('class' => 'iconsmall')));

            $o .= html_writer::tag('p', $content);
            $o .= html_writer::empty_tag('input', array(
                    'type' => 'hidden',
                    'value' => $filter->get_id(),
                    'name' => $basename . '[id]'));
            $o .= html_writer::empty_tag('input', array(
                    'type' => 'hidden',
                    'value' => $filter->get_sortorder(),
                    'name' => $basename . '[sortorder]'));
            $basename .= '[rule]';

        } else {
            $o .= html_writer::tag('p', get_string('awardaxpwhen', 'block_xp', $filter->get_points()));
        }
        $o .= html_writer::start_tag('ul', array('class' => 'filter-rules'));
        $o .= $this->render($filter->get_rule(), array('iseditable' => $filter->is_editable(), 'basename' => $basename));
        $o .= html_writer::end_tag('ul');
        $o .= html_writer::end_tag('li');
        return $o;
    }

    /**
     * Renders a block XP rule.
     *
     * @param block_xp_rule_base $rule The rule.
     * @param array $options
     * @return string
     */
    public function render_block_xp_rule($rule, $options) {
        static $i = 0;
        $iseditable = !empty($options['iseditable']);
        $basename = isset($options['basename']) ? $options['basename'] : '';
        if ($iseditable) {
            $content = $this->render(new pix_icon('i/dragdrop', get_string('movecondition', 'block_xp'), '',
                array('class' => 'iconsmall rule-move')));
            $content .= $rule->get_form($basename);
            $content .= $this->action_link('#', '', null, array('class' => 'rule-delete'),
                new pix_icon('t/delete', get_string('deletecondition', 'block_xp'), '', array('class' => 'iconsmall')));
        } else {
            $content = s($rule->get_description());
        }
        $o = '';
        $o .= html_writer::start_tag('li', array('class' => 'rule rule-type-rule'));
        $o .= html_writer::tag('p', $content, array('class' => 'rule-definition', 'data-basename' => $basename));
        $o .= html_writer::end_tag('li');
        return $o;
    }

    /**
     * Renders a block XP ruleset.
     *
     * @param block_xp_ruleset $ruleset The ruleset.
     * @param array $options The options
     * @return string
     */
    public function render_block_xp_ruleset($ruleset, $options) {
        static $i = 0;
        $iseditable = !empty($options['iseditable']);
        $basename = isset($options['basename']) ? $options['basename'] : '';
        $o = '';
        $o .= html_writer::start_tag('li', array('class' => 'rule rule-type-ruleset'));
        if ($iseditable) {
            $content = $this->render(new pix_icon('i/dragdrop', get_string('movecondition', 'block_xp'), '',
                array('class' => 'iconsmall rule-move')));
            $content .= $ruleset->get_form($basename);
            $content .= $this->action_link('#', '', null, array('class' => 'rule-delete'),
                new pix_icon('t/delete', get_string('deletecondition', 'block_xp'), '', array('class' => 'iconsmall')));
        } else {
            $content = s($ruleset->get_description());
        }
        $o .= html_writer::tag('p', $content, array('class' => 'rule-definition', 'data-basename' => $basename));
        $o .= html_writer::start_tag('ul', array('class' => 'rule-rules', 'data-basename' => $basename . '[rules]'));
        foreach ($ruleset->get_rules() as $rule) {
            if ($iseditable) {
                $options['basename'] = $basename . '[rules][' . $i++ . ']';
            }
            $o .= $this->render($rule, $options);
        }
        if ($iseditable) {
            $o .= html_writer::start_tag('li', array('class' => 'rule-add'));
            $o .= $this->action_link('#', get_string('addacondition', 'block_xp'), null, null,
                new pix_icon('t/add', '', '', array('class' => 'iconsmall')));
            $o .= html_writer::end_tag('li');
        }
        $o .= html_writer::end_tag('ul');
        $o .= html_writer::end_tag('li');
        return $o;
    }

    /**
     * Render a dismissable notice.
     *
     * Yes, we cannot use CSS IDs in there because they are stripped out... turns out they
     * are considered dangerous. Oh well, we use a class instead. Not pretty, but it works...
     *
     * @param renderable $notice The notice.
     * @return string
     */
    public function render_dismissable_notice(renderable $notice) {
        $id = html_writer::random_id();

        // Tell the indicator that it should be expecing this notice.
        $indicator = \block_xp\di::get('user_notice_indicator');
        if ($indicator instanceof \block_xp\local\indicator\user_indicator_with_acceptance) {
            $indicator->set_acceptable_user_flag($notice->name);
        }

        $url = \block_xp\di::get('ajax_url_resolver')->reverse('notice/dismiss', ['name' => $notice->name]);
        $this->page->requires->js_init_call(<<<EOT
            Y.one('.$id .dismiss-action a').on('click', function(e) {
                e.preventDefault();
                Y.one('.$id').hide();
                var url = '$url';
                var cfg = {
                    method: 'POST'
                };
                Y.io(url, cfg);
            });
EOT
        );

        $icon = new pix_icon('t/close', get_string('dismissnotice', 'block_xp'), 'block_xp');
        $actionicon = $this->action_icon('#', $icon, null);
        $text = html_writer::div($actionicon, 'dismiss-action') . $notice->message;

        return html_writer::div($this->notification_without_close($text, $notice->type),
            'block_xp-dismissable-notice ' . $id);
    }

    /**
     * Render the filters widget.
     *
     * /!\ We only support one editable widget per page!
     *
     * @param renderer_base $widget The widget.
     * @return string
     */
    public function render_filters_widget(renderable $widget) {
        if ($widget->editable) {
            $templatefilter = $this->render($widget->filter);

            $templatetypes = [];
            foreach ($widget->rules as $rule) {
                $templatetypes[uniqid()] = [
                    'name' => $rule->name,
                    'template' => $this->render($rule->rule, ['iseditable' => true, 'basename' => 'XXXXX'])
                ];
            }

            // Prepare Javascript.
            $this->page->requires->yui_module('moodle-block_xp-filters', 'Y.M.block_xp.Filters.init', [[
                'filter' => $templatefilter,
                'rules' => $templatetypes
            ]]);
            $this->page->requires->strings_for_js(array('pickaconditiontype'), 'block_xp');
        }

        echo html_writer::start_div('block-xp-filters-wrapper');

        $addlink = '';
        if ($widget->editable) {
            echo html_writer::start_tag('form', ['method' => 'POST', 'class' => 'block-xp-filters']);
            echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);

            $addlink = html_writer::start_tag('li', ['class' => 'filter-add']);
            $addlink .= $this->action_link('#', get_string('addarule', 'block_xp'), null, null,
                new pix_icon('t/add', '', '', ['class' => 'iconsmall']));
            $addlink .= html_writer::end_tag('li');
        }

        $class = $widget->editable ? 'filters-editable' : 'filters-readonly';
        echo html_writer::start_tag('ul', ['class' => 'filters-list ' . $class]);
        echo $addlink;

        foreach ($widget->filters as $filter) {
            echo $this->render($filter);
            echo $addlink;
        }

        echo html_writer::end_tag('ul');

        if ($widget->editable) {
            echo html_writer::start_tag('p');
            echo html_writer::empty_tag('input', ['value' => get_string('savechanges'), 'type' => 'submit', 'name' => 'save',
                'class' => 'btn btn-primary']);
            echo ' ';
            echo html_writer::empty_tag('input', ['value' => get_string('cancel'), 'type' => 'submit', 'name' => 'cancel',
                'class' => 'btn btn-default']);
            echo html_writer::end_tag('p');
            echo html_writer::end_tag('form');
        }

        echo html_writer::end_div();
    }

    /**
     * Render a notice.
     *
     * @param renderable $notice The notice.
     * @return string
     */
    public function render_notice(renderable $notice) {
        return $this->notification_without_close($notice->message, $notice->type);
    }

    /**
     * Returns the progress bar rendered.
     *
     * @param state $state The renderable object.
     * @param bool $showpercentagetogo Show the percentage to go.
     * @return string HTML produced.
     */
    public function progress_bar(state $state, $percentagetogo = false) {
        global $CFG;

        $classes = ['block_xp-level-progress'];
        $pc = $state->get_ratio_in_level() * 100;
        if ($pc != 0) {
            $classes[] = 'progress-non-zero';
        }

        $html = '';

        $html .= html_writer::start_tag('div', ['class' => implode(' ', $classes)]);

        $html .= html_writer::start_tag('div', ['class' => 'xp-bar-wrapper', 'role' => 'progressbar',
            'aria-valuenow' => round($pc, 1), 'aria-valuemin' => 0, 'aria-valuemax' => 100]);
        $html .= html_writer::tag('div', '', ['style' => "width: {$pc}%;", 'class' => 'xp-bar']);
        $html .= html_writer::end_tag('div');

        $thingtogo = $this->xp($state->get_total_xp_in_level() - $state->get_xp_in_level());
        if ($percentagetogo) {
            $value = format_float(max(0, 100 - $pc), 1);
            // Quick hack to support localisation of percentages without having to define a new language
            // string for older versions. When the string is not available, we provide a sensible fallback.
            if ($CFG->branch >= 36) {
                $thingtogo = get_string('percents', 'core', $value);
            } else {
                $thingtogo = $value . '%';
            }
        }
        $togo = get_string('xptogo', 'block_xp', $thingtogo);

        $span = html_writer::start_tag('span', ['class' => 'xp-togo-txt']);
        if (strpos($togo, '[[') !== false && strpos($togo, ']]')) {
            $togo = $span . $togo . '</span>';
            $togo = str_replace('[[', '</span>', $togo);
            $togo = str_replace(']]', $span, $togo);
        }

        $html .= html_writer::tag('div', $togo, ['class' => 'xp-togo']);

        $html .= html_writer::end_tag('div');
        return $html;
    }

    /**
     * Recent activity.
     *
     * @param activity[] $activity The activity entries.
     * @param moodle_url $moreurl The URL to view more.
     * @return string
     */
    public function recent_activity(array $activity, moodle_url $moreurl = null) {
        $o = '';

        $o .= html_writer::start_tag('div', ['class' => 'block_xp-recent-activity']);

        $title = get_string('recentrewards', 'block_xp');
        if ($moreurl) {
            $title .= ' ' . html_writer::link($moreurl, get_string('viewmore'));
        }
        $o .= html_writer::tag('h5', $title, ['class' => "clearfix"]);

        $o .= implode('', array_map(function(activity $entry) {
            $tinyago = $this->tiny_time_ago($entry->get_date());
            $date = userdate($entry->get_date()->getTimestamp());
            $xp = $entry instanceof \block_xp\local\activity\activity_with_xp ? $entry->get_xp() : '';
            $o = '';
            $o .= html_writer::start_div('activity-entry');
            $o .= html_writer::div($tinyago, 'date', ['title' => $date]);
            $o .= html_writer::div($this->xp($xp), 'xp');
            $o .= html_writer::div(s($entry->get_description()), 'desc');
            $o .= html_writer::end_div();
            return $o;
        }, $activity));

        if (!$activity) {
            $o .= html_writer::tag('p', '-');
        }

        $o .= html_writer::end_tag('div');

        return $o;
    }

    /**
     * Render XP widget.
     *
     * @param renderable $widget The widget.
     * @return string
     */
    public function render_xp_widget(xp_widget $widget) {
        $o = '';

        foreach ($widget->managernotices as $notice) {
            $o .= $this->notification_without_close($notice, 'warning');
        }

        // Badge.
        $o .= $this->level_badge($widget->state->get_level());

        // Level name.
        $o .= $this->level_name($widget->state->get_level());

        // Total XP.
        $xp = $widget->state->get_xp();
        $o .= html_writer::tag('div', $this->xp($xp), ['class' => 'xp-total']);

        // Progress bar.
        $o .= $this->progress_bar($widget->state);

        // Intro.
        if (!empty($widget->intro)) {
            $o .= html_writer::div($this->render($widget->intro), 'introduction');
        }

        // Recent rewards.
        if (!empty($widget->recentactivity) || $widget->forcerecentactivity) {
            $o .= $this->recent_activity($widget->recentactivity, $widget->recentactivityurl);
        }

        // Navigation.
        if (!empty($widget->actions)) {
            $o .= $this->xp_widget_navigation($widget->actions);
        }

        return $o;
    }

    /**
     * Tiny time ago string.
     *
     * @param DateTime $dt The date object.
     * @return string
     */
    public function tiny_time_ago(DateTime $dt) {
        $now = new \DateTime();
        $diff = $now->getTimestamp() - $dt->getTimestamp();
        $ago = '?';

        if ($diff < 15) {
            $ago = get_string('tinytimenow', 'block_xp');
        } else if ($diff < 45) {
            $ago = get_string('tinytimeseconds', 'block_xp', $diff);
        } else if ($diff < HOURSECS * 0.7) {
            $ago = get_string('tinytimeminutes', 'block_xp', round($diff / 60));
        } else if ($diff < DAYSECS * 0.7) {
            $ago = get_string('tinytimehours', 'block_xp', round($diff / HOURSECS));
        } else if ($diff < DAYSECS * 7 * 0.7) {
            $ago = get_string('tinytimedays', 'block_xp', round($diff / DAYSECS));
        } else if ($diff < DAYSECS * 30 * 0.7) {
            $ago = get_string('tinytimeweeks', 'block_xp', round($diff / (DAYSECS * 7)));
        } else if ($diff < DAYSECS * 365) {
            $ago = userdate($dt->getTimestamp(), get_string('tinytimewithinayearformat', 'block_xp'));
        } else {
            $ago = userdate($dt->getTimestamp(), get_string('tinytimeolderyearformat', 'block_xp'));
        }

        return $ago;
    }

    /**
     * Format an amount of XP.
     *
     * @param int $amount The XP.
     * @return string
     */
    public function xp($amount) {
        $xp = (int) $amount;
        if ($xp > 999) {
            $thousandssep = get_string('thousandssep', 'langconfig');
            $xp = number_format($xp, 0, '.', $thousandssep);
        }
        $o = '';
        $o .= html_writer::start_div('block_xp-xp');
        $o .= html_writer::div($xp, 'pts');
        $o .= html_writer::div('xp', 'sign sign-sup');
        $o .= html_writer::end_div();
        return $o;
    }

    /**
     * Render XP widget navigation.
     *
     * @param array $actions The actions.
     * @return string
     */
    public function xp_widget_navigation(array $actions) {
        $o = '';
        $o .= html_writer::start_tag('nav');
        $o .= implode('', array_map(function(action_link $action) {
            $content = html_writer::div($this->render($action->icon));
            $content .= html_writer::div($action->text);
            return html_writer::link($action->url, $content, ['class' => 'nav-button']);
        }, $actions));
        $o .= html_writer::end_tag('nav');
        return $o;
    }

}
