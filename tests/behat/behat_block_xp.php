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
 * Additional behat steps definition.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use Behat\Mink\Exception\ElementNotFoundException;
use block_xp\di;

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

/**
 * Additional steps definition.
 *
 * @package    block_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_block_xp extends behat_base {

    /**
     * Resolve a page instance URL.
     *
     * @param string $type
     * @param string $identifier
     * @return moodle_url
     */
    protected function resolve_page_instance_url(string $type, string $identifier): moodle_url {
        if ($type === 'info') {
            $type = 'infos';
        } else if ($type === 'leaderboard') {
            $type = 'ladder';
        }

        $context = context_system::instance();
        if (!in_array(strtolower($identifier), ['sys', 'system'])) {
            $courseid = $this->get_course_id($identifier);
            $context = context_course::instance($courseid);
        }
        $world = di::get('context_world_factory')->get_world_from_context($context);
        return di::get('url_resolver')->reverse($type, ['courseid' => $world->get_courseid()]);
    }

    /**
     * Go to the front page.
     *
     * There are no standard definitions available from 2.7 so we use our own.
     *
     * @Given /^I am on front page$/
     */
    public function i_am_on_front_page() {
        $this->getSession()->visit($this->locate_path('/?redirect=0'));
    }

    /**
     * Delete all XP event rules.
     *
     * @Given /^I delete all XP event rules$/
     */
    public function i_delete_all_xp_event_rules() {
        global $CFG;

        $page = $this->getSession()->getPage();
        while (true) {
            [$selector, $locator] = $this->transform_selector('link', 'Delete rule');
            if (!$page->find($selector, $locator)) {
                break;
            }

            // The step only works from 4.3, see MDL-78199.
            if ($CFG->branch >= 403) {
                $this->execute('behat_general::i_hover_in_the', [
                    'Delete rule', 'link',
                    '.filters-list .filter', 'css_element',
                ]);
            } else {
                $this->execute('behat_general::i_hover', ['Delete rule', 'link',]);
            }
            $this->execute('behat_general::i_click_on', ['Delete rule', 'link']);

            [$selector, $locator] = $this->transform_selector('dialogue', 'Delete rule');
            if (!$page->find($selector, $locator)) {
                continue;
            }
            foreach ($this->find_all('dialogue', 'Delete rule', false, false) as $dialogue) {
                $button = $this->get_node_in_container('button', 'Yes', 'NodeElement', $dialogue);
                if ($button->isVisible()) {
                    $button->click();
                }
            }
        }

        $this->execute('behat_general::i_click_on', ['Save changes', 'button']);
    }

    /**
     * Step to follow a link in the XP nav.
     *
     * @Given /^I follow "(?P<text>(?:[^"]|\\")*)" in( the)? XP nav$/
     * @param string $text
     */
    public function i_follow_foo_in_xp_nav($text) {
        $this->execute('behat_general::i_click_on_in_the', [
            $text, "link",
            '.block_xp-page-nav', "css_element",
        ]);
    }

    /**
     * Step to follow a link in the XP secondary nav.
     *
     * @Given /^I follow "(?P<text>(?:[^"]|\\")*)" in( the)? XP secondary nav$/
     * @param string $text
     */
    public function i_follow_foo_in_xp_secondary_nav($text) {
        $this->execute('behat_general::i_click_on_in_the', [
            $text, "link",
            '#region-main :not(.block_xp-page-nav) nav', "css_element",
        ]);
    }

    /**
     * Step to edit a student from the report.
     *
     * @Given /^I follow edit for "(?P<student>(?:[^"]|\\")*)" in( the)? XP report$/
     * @param string $studentname
     * @deprecated
     */
    public function i_follow_edit_for_in_xp_report($studentname) {
        return $this->i_follow_foo_for_in_xp_report('Edit', $studentname);
    }

    /**
     * Step to follow a link in the XP report.
     *
     * @Given /^I follow "(?P<text>(?:[^"]|\\")*)" for "(?P<student>(?:[^"]|\\")*)" in( the)? XP report$/
     * @param string $text
     * @param string $studentname
     */
    public function i_follow_foo_for_in_xp_report($text, $studentname) {
        $rowxpath = "//tr[contains(normalize-space(.), '$studentname')]";

        $this->execute('behat_general::i_click_on_in_the', [
            '.action-menu [role="button"]', "css_element",
            $rowxpath, "xpath_element",
        ]);

        $this->execute('behat_general::i_click_on_in_the', [
            $text, "link",
            $rowxpath, "xpath_element",
        ]);
    }

    /**
     * Step to follow a page menu link.
     *
     * @Given /^I follow "(?P<text>(?:[^"]|\\")*)" in( the)? XP page menu$/
     * @param string $text
     */
    public function i_follow_foo_in_xp_page_menu($text) {
        $this->execute('behat_general::i_click_on', [
            "[data-region='block_xp-page_menu'] [data-toggle='dropdown']", "css_element",
        ]);

        $this->execute('behat_general::i_click_on_in_the', [
            "$text", "link",
            "[data-region='block_xp-page_menu'] .dropdown", "css_element",
        ]);
    }

    /**
     * Reset caches.
     *
     * @AfterScenario
     */
    public function reset_caches() {
        \block_xp\di::set_container(new \block_xp\local\default_container());
    }

}
