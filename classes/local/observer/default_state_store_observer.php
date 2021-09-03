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
 * State store observer.
 *
 * @package    block_xp
 * @copyright  2021 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\observer;
defined('MOODLE_INTERNAL') || die();

use context;
use block_xp\local\config\config;
use block_xp\local\notification\course_level_up_notification_service;
use block_xp\local\xp\level;
use block_xp\local\xp\state_store;

/**
 * State store observer.
 *
 * @package    block_xp
 * @copyright  2021 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_state_store_observer implements level_up_state_store_observer, points_increased_state_store_observer {

    /** @var context The context. */
    protected $context;
    /** @var config The world config. */
    protected $config;
    /** @var course_level_up_notification_service The notification service. */
    protected $notificationservice;

    /**
     * Constructor.
     *
     * @param context $context The context.
     * @param config $config The world config.
     */
    public function __construct(context $context, config $config, course_level_up_notification_service $notificationservice) {
        $this->context = $context;
        $this->config = $config;
        $this->notificationservice = $notificationservice;
    }

    /**
     * The recipient leveled up.
     *
     * @param state_store $store The store.
     * @param int $id The recipient.
     * @param level $beforelevel The level before.
     * @param level $afterlevel The level after.
     * @return void
     */
    public function leveled_up(state_store $store, $id, level $beforelevel, level $afterlevel) {
        $params = [
            'context' => $this->context,
            'relateduserid' => $id,
            'other' => [
                'level' => $afterlevel->get_level()
            ]
        ];
        $lupevent = \block_xp\event\user_leveledup::create($params);
        $lupevent->trigger();

        if ($this->config->get('enablelevelupnotif')) {
            $this->notificationservice->notify($id);
        }

    }

    /**
     * The recipient points increased.
     *
     * @param state_store $store The store.
     * @param int $id The recipient.
     * @param int $pointsamount The amount of points, always greater than 0.
     * @return void
     */
    public function points_increased(state_store $store, $id, $pointsamount) {
        if ($pointsamount <= 0) {
            return;
        }

        $hooks = get_plugin_list_with_function('block', 'xp_points_increased');
        foreach ($hooks as $plugin => $fullfunctionname) {
            try {
                component_callback($plugin, 'xp_points_increased', [$this->context, $id, $pointsamount]);
            } catch (\Exception $e) {
                debugging("Error while calling $plugin's xp_points_increased callback: " . $e->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }

}
