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
 * Filters Drag and Drop.
 *
 * @module     moodle-block_xp-filters
 * @package    block_xp
 * @copyright  2015 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @main       moodle-block_xp-filters
 */

/**
 * @module moodle-block_xp-filters
 */

/**
 * Filters Drag and Drop.
 *
 * @namespace Y.M.block_xp.Filters.DnD
 * @class DnD
 * @constructor
 */
var DND = function() {
    DND.superclass.constructor.apply(this, arguments);
};
Y.namespace('M.block_xp.Filters').DnD = Y.extend(DND, M.core.dragdrop, {

    /**
     * Reference to the delegate.
     *
     * @type {Object}
     */
    delegate: null,

    /**
     * Initializer.
     */
    initializer: function() {
        this.groups = this.get('groups');
        this.samenodeclass = this.get('nodeClass');
        this.parentnodeclass = this.get('containerClass');

        // Note that the delegate creates DROP targets on each element of the list.
        // So we need to sync the targets on every new node that is added.
        var del = new Y.DD.Delegate({
            container: this.get('containerSelector'),
            nodes: this.get('nodeSelector'),
            target: true,
            handles: [this.get('handleSelector')],
            dragConfig: {
                groups: this.groups
            }
        });

        del.dd.plug(Y.Plugin.DDProxy, {
            // Don't move the node at the end of the drag.
            moveOnEnd: false,
            cloneNode: true
        });
        del.dd.plug(Y.Plugin.DDConstrained, {
            // Keep it inside the list.
            constrain: this.get('containerSelector')
        });
        del.dd.plug(Y.Plugin.DDWinScroll);

        // Allow nodes to be dropped on the container itself.
        var mainContainer = Y.one(this.get('containerSelector'));
        del.createDrop(mainContainer, this.groups);

        this.delegate = del;
        this.publish('drag:end');
        this.publish('drop:hit');
        this.publish('drop:over');

        // Sync the targets, just in case.
        this.syncTargets();
    },

    global_drop_over: function(e) { /* eslint-disable-line */

        // Check that drop object belong to correct group.
        if (!e.drop || !e.drop.inGroup(this.groups)) {
            return;
        }

        // Get a reference to our drag and drop nodes.
        var drag = e.drag.get('node'),
            drop = e.drop.get('node');

        // Save last drop target for the case of missed target processing.
        this.lastdroptarget = e.drop;

        if (this.get('dropBeforeSelector') && drop.test(this.get('dropBeforeSelector'))) {
            drop.get('parentNode').insertBefore(drag, drop);
            this.drop_over(e);
        } else {
            DND.superclass.global_drop_over.apply(this, arguments);
        }
    },

    /**
     * Called when dragging ends.
     *
     * @param {EventFacade} e
     */
    drag_end: function(e) { /* eslint-disable-line */
        this.fire('drag:end', e);
    },

    /**
     * Called when the drop happened.
     *
     * @param {EventFacade} e
     */
    drop_hit: function(e) { /* eslint-disable-line */
        this.fire('drop:hit', e);
    },

    /**
     * Called when over drop area.
     *
     * @param {EventFacade} e
     */
    drop_over: function(e) { /* eslint-disable-line */
        this.fire('drop:over', e);
    },

    /**
     * Sync the drop targets.
     */
    syncTargets: function() {
        this.delegate.syncTargets();

        if (this.get('additionalDropsSelector')) {
            Y.one(this.get('containerSelector')).all(this.get('additionalDropsSelector')).each(function(node) {
                this.delegate.createDrop(node, this.groups);
            }, this);
        }
    }

}, {
    NAME: 'block_xp-filters-dnd',
    ATTRS: {

        /**
         * Selector to find additional drop areas.
         *
         * @type {String}
         */
        additionalDropsSelector: {
            validator: Y.Lang.isString,
            value: null
        },

        /**
         * The class of the list.
         *
         * @type {String}
         */
        containerClass: {
            validator: Y.Lang.isString,
            value: null
        },

        /**
         * The selector to find the list.
         *
         * @type {String}
         */
        containerSelector: {
            validator: Y.Lang.isString,
            value: null
        },

        /**
         * Drop before selector.
         *
         * Selector of nodes which are drop targets but should not be drop onto but before.
         *
         * @type {String}
         */
        dropBeforeSelector: {
            validator: Y.Lang.isString,
            value: null
        },

        /**
         * The groups.
         *
         * @type {Array}
         */
        groups: {
            validator: Y.Lang.isArray,
            value: []
        },

        /**
         * The handle selector.
         *
         * @type {String}
         */
        handleSelector: {
            validator: Y.Lang.isString,
            value: ''
        },

        /**
         * The class of the nodes.
         *
         * @type {String}
         */
        nodeClass: {
            validator: Y.Lang.isString,
            value: null
        },

        /**
         * The selector to find the nodes in the list.
         *
         * @type {String}
         */
        nodeSelector: {
            validator: Y.Lang.isString,
            value: null
        },

    }
});

Y.namespace('M.block_xp.Filters.DnD').init = function(config) {
    return new DND(config);
};
