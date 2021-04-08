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
 * Launch an app.
 *
 * @package    block_xp
 * @copyright  2021 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/notification'], function($, Notification) {

    /**
     * App launcher.
     *
     * @param {String} mod The module name.
     * @param {String} rootId The root ID.
     * @param {String} propsId The props ID.
     */
    function launcher(mod, rootId, propsId) {

        // Load the app module. By convension a module app needs to return
        // an object with two properties: `dependencies`, and `startApp`.
        require([mod], function(mod) {
            var dependencies = [];
            var dependenciesLoadedCallback = function() {
                return;
            };

            // If the module defines dependencies, set them up..
            if (mod.dependencies) {
                dependencies = mod.dependencies.list;
                dependenciesLoadedCallback = mod.dependencies.loader;
            }

            // Load the dependencies.
            var loader = $.Deferred();
            require(dependencies, function() {
                loader.resolve(arguments);
            });

            // Once the deps are loaded, pass them to the the app, and start the app.
            loader.then(function(depsArg) {
                var deps = [].slice.call(depsArg);
                dependenciesLoadedCallback(deps);
                var props = JSON.parse(document.getElementById(propsId).textContent);
                mod.startApp(document.getElementById(rootId), props);
                return;
            }).catch(Notification.exception);

        });
    }

    return launcher;
});
