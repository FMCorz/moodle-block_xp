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
 * Rule event.
 *
 * @package    block_xp
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Rule event class.
 *
 * Option to filter by most common events.
 *
 * @package    block_xp
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp_rule_event extends block_xp_rule_property {

    /**
     * Constructor.
     */
    public function __construct($eventname = '') {
        parent::__construct(self::EQ, $eventname, 'eventname');
    }

    /**
     * Returns a string describing the rule.
     *
     * @return string
     */
    public function get_description() {
        $class = $this->value;
        $name = get_string('errorunknownevent', 'block_xp');
        $infos = self::get_event_infos($class);

        if ($infos !== false) {
            $pluginmanager = core_plugin_manager::instance();
            $plugininfo = $pluginmanager->get_plugin_info($infos['component']);
            $name = get_string('colon', 'block_xp', (object) array(
                'a' => $plugininfo->displayname,
                'b' => $infos['name']
            ));
        }

        return get_string('ruleeventdesc', 'block_xp', (object)array('eventname' => $name));
    }

    /**
     * Return the info about an event.
     *
     * The key 'name' is added to contain the readable name of the event.
     * It is done here because debugging is turned off and some events use
     * deprecated strings.
     *
     * @param  string $class The name of the event class.
     * @return array|false
     */
    public static function get_event_infos($class) {
        global $CFG;
        $infos = false;

        // We need to disable debugging as some events can be deprecated.
        $debuglevel = $CFG->debug;
        $debugdisplay = $CFG->debugdisplay;
        set_debugging(0, false);

        // Check that the event exists, and is not an abstract event.
        if (method_exists($class, 'get_static_info')) {
            $ref = new \ReflectionClass($class);
            if (!$ref->isAbstract()) {
                $infos = $class::get_static_info();
                $infos['name'] = $class::get_name();
            }
        }

        // Restore debugging.
        set_debugging($debuglevel, $debugdisplay);

        return $infos;
    }

    /**
     * Return the list of events that we want to display.
     *
     * @return array Array of array of array. Example:
     *               $list = array(
     *                   'Component Name' => array(
     *                       'className' => 'Event name'
     *                   )
     *               );
     */
    public static function get_events_list() {
        global $CFG;
        $cache = cache::make('block_xp', 'ruleevent_eventslist');
        $key = 'list';

        if (false === ($list = $cache->get($key))) {
            $list = array();
            $pluginmanager = core_plugin_manager::instance();

            $plugintype = 'mod';
            $pluginlist = core_component::get_plugin_list($plugintype);

            // Loop over each plugin of the type.
            foreach ($pluginlist as $plugin => $directory) {
                $events = array();
                $plugindirectory = $directory . '/classes/event';
                $plugininfo = $pluginmanager->get_plugin_info($plugintype . '_' . $plugin);

                // Get the plugin's files.
                $finalfiles = array();
                if (is_dir($plugindirectory)) {
                    if ($handle = opendir($plugindirectory)) {
                        $files = scandir($plugindirectory);
                        foreach ($files as $file) {
                            if ($file != '.' && $file != '..') {
                                $location = substr($plugindirectory, strlen($CFG->dirroot));
                                $name = substr($file, 0, -4);
                                $finalfiles[$name] = $location  . '/' . $file;
                            }
                        }
                    }
                }

                // Loop over each file to construct, and double check the event.
                foreach ($finalfiles as $eventname => $notused) {
                    $class = '\\' . $plugintype . '_' . $plugin . '\\event\\' . $eventname;
                    $infos = self::get_event_infos($class);

                    // Only keep events that are of level 'participating'.
                    if ($infos !== false && $infos['edulevel'] == \core\event\base::LEVEL_PARTICIPATING) {
                        $events[$infos['eventname']] = get_string('colon', 'block_xp', (object) array(
                            'a' => $plugininfo->displayname,
                            'b' => $infos['name']
                        ));
                    }
                }

                // If we found events for this plugin, we add them to the list.
                if (!empty($events)) {
                    $plugininfo = $pluginmanager->get_plugin_info($plugintype . '_' . $plugin);
                    $list[] = array($plugininfo->displayname => $events);
                }
            }

            $cache->set($key, $list);
        }

        return $list;
    }

    /**
     * Returns a form element for this rule.
     *
     * @param string $basename The form element base name.
     * @return string
     */
    public function get_form($basename) {
        $o = block_xp_rule::get_form($basename);
        $modules = html_writer::select(self::get_events_list(), $basename . '[value]', $this->value, '', array('id' => '', 'class' => ''));
        $o .= get_string('eventis', 'block_xp', $modules);
        return $o;
    }

}
