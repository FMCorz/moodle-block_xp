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
     *
     * @param string $eventname The event name.
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
        $infos = self::get_event_infos($class);

        if ($infos !== false) {
            list($type, $plugin) = core_component::normalize_component($infos['component']);
            if ($type == 'core') {
                $displayname = get_string('coresystem');
            } else {
                $pluginmanager = core_plugin_manager::instance();
                $plugininfo = $pluginmanager->get_plugin_info($infos['component']);
                $displayname = $infos['component'];
                if (!empty($plugininfo)) {
                    $displayname = $plugininfo->displayname;
                }
            }
            $name = get_string('colon', 'block_xp', (object) [
                'a' => $displayname,
                'b' => $infos['name']
            ]);
        } else {
            $name = get_string('unknowneventa', 'block_xp', $this->value);
        }

        return get_string('ruleeventdesc', 'block_xp', ['eventname' => $name]);
    }

    /**
     * Return the info about an event.
     *
     * The key 'name' is added to contain the readable name of the event.
     * It is done here because debugging is turned off and some events use
     * deprecated strings.
     *
     * We also add the key 'isdeprecated' which indicates whether the event
     * is obsolete or not.
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
                $infos['name'] = method_exists($class, 'get_name_with_info') ? $class::get_name_with_info() : $class::get_name();
                $infos['isdeprecated'] = method_exists($class, 'is_deprecated') ? $class::is_deprecated() : false;
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
            $list = [];

            // Add some system events.
            $eventclasses = [
                '\\core\\event\\course_viewed'
            ];
            $list[] = [get_string('coresystem') => array_reduce($eventclasses, function($carry, $eventclass) {
                $infos = self::get_event_infos($eventclass);
                if ($infos) {
                    $carry[$infos['eventname']] = $infos['name'];
                }
                return $carry;
            }, [])];

            // Get module events.
            $list = array_merge($list, self::get_events_list_from_plugintype('mod'));

            // Save to cache.
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
        $eventslist = self::get_events_list();

        // Append the value to the list if we cannot find it any more.
        if (!empty($this->value) && !$this->value_in_list($this->value, $eventslist)) {
            $eventslist[] = [get_string('other') => [$this->value => get_string('unknowneventa', 'block_xp', $this->value)]];
        }

        $modules = html_writer::select($eventslist, $basename . '[value]', $this->value, '',
            array('id' => '', 'class' => ''));
        $o .= get_string('eventis', 'block_xp', $modules);
        return $o;
    }

    /**
     * Get the events list from a plugin.
     *
     * From 3.1 we could be using core_component::get_component_classes_in_namespace().
     *
     * @param string $component The plugin's component name.
     * @return array
     */
    protected static function get_events_list_from_plugin($component) {
        $directory = core_component::get_component_directory($component);
        $plugindirectory = $directory . '/classes/event';
        if (!is_dir($plugindirectory)) {
            return [];
        }

        // Get the plugin's events.
        $eventclasses = [];
        $diriter = new DirectoryIterator($plugindirectory);
        foreach ($diriter as $file) {
            if ($file->isDot() || $file->isDir()) {
                continue;
            }

            // It's a good idea to use the leading slashes because the event's property
            // 'eventname' includes them as well, so for consistency sake... Also we do
            // not check if the class exists because that would cause the class to be
            // autoloaded which would potentially trigger debugging messages when
            // it is deprecated.
            $name = substr($file->getFileName(), 0, -4);
            $classname = '\\' . $component . '\\event\\' . $name;
            $eventclasses[] = $classname;
        }

        $pluginmanager = core_plugin_manager::instance();
        $plugininfo = $pluginmanager->get_plugin_info($component);

        // Reduce to the participating, non-deprecated event.
        $events = array_reduce($eventclasses, function($carry, $class) use ($plugininfo) {
            $infos = self::get_event_infos($class);
            if (empty($infos)) {
                // Skip rare case where infos aren't found.
                return $carry;
            } else if ($infos['edulevel'] != \core\event\base::LEVEL_PARTICIPATING) {
                // Skip events that are not of level 'participating'.
                return $carry;
            }

            $carry[$infos['eventname']] = get_string('colon', 'block_xp', [
                'a' => $plugininfo->displayname,
                'b' => $infos['name']
            ]);
            return $carry;
        }, []);

        // Order alphabetically.
        core_collator::asort($events, core_collator::SORT_NATURAL);

        return $events;
    }

    /**
     * Get events from plugin type.
     *
     * @param string $plugintype Plugin type.
     * @return array
     */
    protected static function get_events_list_from_plugintype($plugintype) {
        $list = [];

        // Loop over each plugin of the type.
        $pluginlist = core_component::get_plugin_list($plugintype);
        foreach ($pluginlist as $plugin => $directory) {
            $component = $plugintype . '_' . $plugin;
            $events = self::get_events_list_from_plugin($component);

            // If we found events for this plugin, we add them to the list.
            if (!empty($events)) {
                $pluginmanager = core_plugin_manager::instance();
                $plugininfo = $pluginmanager->get_plugin_info($component);
                $list[] = array($plugininfo->displayname => $events);
            }
        }

        return $list;
    }

    /**
     * Check if the value is in the list.
     *
     * @param mixed $value Value.
     * @param Traversable $list The list where the first level or keys does not count.
     * @return bool
     */
    protected static function value_in_list($value, $list) {
        foreach ($list as $optgroup) {
            foreach ($optgroup as $values) {
                if (array_key_exists($value, $values)) {
                    return true;
                }
            }
        }
        return false;
    }

}
