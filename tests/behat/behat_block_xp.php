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
     * Step to edit a student from the report.
     *
     * @Given /^I follow edit for "(?P<student>(?:[^"]|\\")*)" in XP report$/
     * @param string $studentname
     * @deprecated
     */
    public function i_follow_edit_for_in_xp_report($studentname) {
        return $this->i_follow_foo_for_in_xp_report('Edit', $studentname);
    }

    /**
     * Step to follow a link in the XP report.
     *
     * @Given /^I follow "(?P<text>(?:[^"]|\\")*)" for "(?P<student>(?:[^"]|\\")*)" in XP report$/
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
     * @Given /^I follow "(?P<text>(?:[^"]|\\")*)" in the XP page menu$/
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
