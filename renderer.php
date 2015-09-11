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

/**
 * Block XP renderer class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_renderer extends plugin_renderer_base {

    /**
     * Administration links.
     *
     * @param int $courseid The course ID.
     * @return string HTML produced.
     */
    public function admin_links($courseid) {
        return html_writer::tag('p',
            html_writer::link(
                new moodle_url('/blocks/xp/report.php', array('courseid' => $courseid)),
                get_string('navreport', 'block_xp'))
            . ' - '
            . html_writer::link(
                new moodle_url('/blocks/xp/config.php', array('courseid' => $courseid)),
                get_string('navsettings', 'block_xp'))
            , array('class' => 'admin-links')
        );
    }

    /**
     * Returns the current level rendered.
     *
     * @param renderable $progress The renderable object.
     * @return string HTML produced.
     */
    public function current_level(renderable $progress) {
        $html = '';
        $html .= html_writer::tag('div', $progress->level, array('class' => 'current-level level-' . $progress->level));
        return $html;
    }

    /**
     * Returns the current level rendered with custom badges.
     *
     * @param renderable $progress The renderable object.
     * @return string HTML produced.
     */
    public function custom_current_level(renderable $progress) {
        $html = '';
        $html .= html_writer::tag('div',
            html_writer::empty_tag('img', array('src' => $progress->levelimgsrc)),
            array('class' => 'level-badge current-level level-' . $progress->level)
        );
        return $html;
    }

    /**
     * The description to display in the field.
     *
     * @param string $string The text to display.
     * @return string HTML producted.
     */
    public function description($string) {
        if (empty($string)) {
            return '';
        }
        return html_writer::tag('p', $string, array('class' => 'description'));
    }

    /**
     * Return the notices.
     *
     * @param block_xp_manager $manager The manager.
     * @return string The notices.
     */
    public function notices($manager) {
        global $CFG;
        $o = '';

        if (!$manager->can_manage()) {
            return $o;
        }

        if (!get_user_preferences(block_xp_manager::USERPREF_NOTICES, false)) {
            require_once($CFG->libdir . '/ajax/ajaxlib.php');
            user_preference_allow_ajax_update(block_xp_manager::USERPREF_NOTICES, PARAM_BOOL);

            $moodleorgurl = new moodle_url('https://moodle.org/plugins/view.php?plugin=block_xp');
            $githuburl = new moodle_url('https://github.com/FMCorz/moodle-block_xp');
            $text = get_string('likenotice', 'block_xp', (object) array(
                'moodleorg' => $moodleorgurl->out(),
                'github' => $githuburl->out()
            ));

            $id = html_writer::random_id();
            $this->page->requires->js_init_call("Y.one('.block-xp-rocks').on('click', function(e) {
                e.preventDefault();
                M.util.set_user_preference('" . block_xp_manager::USERPREF_NOTICES . "', 1);
                Y.one('.block-xp-notices').hide();
            });");

            $icon = new pix_icon('t/delete', get_string('dismissnotice', 'block_xp'));
            $actionicon = $this->action_icon(new moodle_url($this->page->url), $icon, null, array('class' => 'block-xp-rocks'));
            $text .= html_writer::div($actionicon, 'dismiss-action');
            $o .= html_writer::div($this->notification($text, 'notifysuccess'), 'block-xp-notices');
        }

        return $o;
    }

    /**
     * Outputs the navigation.
     *
     * @param block_xp_manager $manager The manager.
     * @param string $page The page we are on.
     * @return string The navigation.
     */
    public function navigation($manager, $page) {
        $tabs = array();
        $courseid = $manager->get_courseid();

        if ($manager->can_view_infos_page()) {
            $tabs[] = new tabobject(
                'infos',
                new moodle_url('/blocks/xp/infos.php', array('courseid' => $courseid)),
                get_string('navinfos', 'block_xp')
            );
        }
        if ($manager->can_view_infos_page()) {
            $tabs[] = new tabobject(
                'ladder',
                new moodle_url('/blocks/xp/ladder.php', array('courseid' => $courseid)),
                get_string('navladder', 'block_xp')
            );
        }

        if ($manager->can_manage()) {
            $tabs[] = new tabobject(
                'report',
                new moodle_url('/blocks/xp/report.php', array('courseid' => $courseid)),
                get_string('navreport', 'block_xp')
            );
            $tabs[] = new tabobject(
                'log',
                new moodle_url('/blocks/xp/log.php', array('courseid' => $courseid)),
                get_string('navlog', 'block_xp')
            );
            $tabs[] = new tabobject(
                'levels',
                new moodle_url('/blocks/xp/levels.php', array('courseid' => $courseid)),
                get_string('navlevels', 'block_xp')
            );
            $tabs[] = new tabobject(
                'rules',
                new moodle_url('/blocks/xp/rules.php', array('courseid' => $courseid)),
                get_string('navrules', 'block_xp')
            );
            $tabs[] = new tabobject(
                'visuals',
                new moodle_url('/blocks/xp/visuals.php', array('courseid' => $courseid)),
                get_string('navvisuals', 'block_xp')
            );
            $tabs[] = new tabobject(
                'config',
                new moodle_url('/blocks/xp/config.php', array('courseid' => $courseid)),
                get_string('navsettings', 'block_xp')
            );
        }

        // If there is only one page, then that is the page we are on.
        if (count($tabs) == 1) {
            return '';
        }

        return $this->tabtree($tabs, $page);
    }

    /**
     * Override render method.
     *
     * @return string
     */
    public function render(renderable $renderable, $options = array()) {
        if ($renderable instanceof block_xp_rule_base) {
            return $this->render_block_xp_rule($renderable, $options);
        } else if ($renderable instanceof block_xp_ruleset) {
            return $this->render_block_xp_ruleset($renderable, $options);
        }
        return parent::render($renderable);
    }

    /**
     * Renders a block XP filter.
     *
     * Not very proud of the way I implement this... The HTML is tied to Javascript
     * and to the rule objects themselves. Careful when changing something!
     *
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
                    'name' => $basename . '[points]'))
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
     * Renders a block XP ruleset.
     *
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
     * @param array $options
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
     * Returns the links for the students.
     *
     * @param int $courseid The course ID.
     * @param bool $showladder Show the ladder link
     * @param bool $showinfos Show the infos link
     * @return string HTML produced.
     */
    public function student_links($courseid, $showladder, $showinfos) {
        $html = '';
        $links = array();

        if ($showinfos) {
            $links[] = html_writer::link(
                new moodle_url('/blocks/xp/infos.php', array('courseid' => $courseid)),
                get_string('infos', 'block_xp')
            );
        }
        if ($showladder) {
            $links[] = html_writer::link(
                new moodle_url('/blocks/xp/ladder.php', array('courseid' => $courseid)),
                get_string('viewtheladder', 'block_xp')
            );
        }

        if (!empty($links)) {
            $html = html_writer::tag('p', implode(' - ', $links), array('class' => 'student-links'));
        }

        return $html;
    }

    /**
     * Returns the progress bar rendered.
     *
     * @param renderable $progress The renderable object.
     * @return string HTML produced.
     */
    public function progress_bar(renderable $progress) {
        $html = '';
        $html .= html_writer::start_tag('div', array('class' => 'block_xp-level-progress'));
        $html .= html_writer::tag('div', '', array('style' => 'width: ' . $progress->percentage . '%;', 'class' => 'bar'));
        $html .= html_writer::tag('div', $progress->xpinlevel . '/' . $progress->xpforlevel, array('class' => 'txt'));
        $html .= html_writer::end_tag('div');
        return $html;
    }

}
