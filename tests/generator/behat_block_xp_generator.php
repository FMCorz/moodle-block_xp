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
 * Behat steps in plugin block_xp
 *
 * @package    block_xp
 * @category   test
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_block_xp_generator extends behat_generator_base {

    protected function get_creatable_entities(): array {
        return [
            'config' => [
                'datagenerator' => 'config',
                'required' => ['worldcontext', 'name', 'value'],
                'switchids' => [
                    'worldcontext' => 'contextid',
                ],
            ],
            'xp' => [
                'datagenerator' => 'xp',
                'required' => ['worldcontext', 'user'],
                'switchids' => [
                    'user' => 'userid',
                    'worldcontext' => 'contextid',
                ],
            ],
        ];
    }

    /**
     * Get the mission ID.
     *
     * @param string $mission The mission name
     * @return int The ID
     */
    protected function get_worldcontext_id(string $worldcontext): int {
        if ($worldcontext === 'sys' || $worldcontext === 'system') {
            return SYSCONTEXTID;
        }
        $courseid = $this->get_course_id($worldcontext);
        return context_course::instance($courseid)->id;
    }

}
