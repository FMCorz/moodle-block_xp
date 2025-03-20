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
 * Resource selector.
 *
 * @module     block_xp/resource-selector
 * @copyright  2018 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'block_xp/throttler', 'core/pending'], function($, Throttler, Pending) {
    /**
     * Resource selector.
     *
     * @param {String|jQuery} container The container of the contents.
     * @param {Function} searchFunction The search function.
     * @param {jQuery} searchTermFieldNode The input field in which the use searches.
     */
    function ResourceSelector(container, searchFunction, searchTermFieldNode) {
        this._eventNode = $('<div>');
        this.container = $(container);

        this.searchId = 0;
        this.searchTermNode = searchTermFieldNode;

        this.resultsContainer = container.find('tbody');
        this.emptyResultsNode = container.find('.results-empty');
        this.searchingResultsNode = container.find('.searching-results');
        this.searchResultsNode = container.find('.search-results');

        this.resourceTemplateNode = container.find('.resource-template');
        this.resourceTemplateNode.hide();
        this.resourceTemplate = this.resourceTemplateNode.clone();
        this.resourceTemplate.removeClass('resource-template');
        this.resourceTemplate.show();

        this.throttler = new Throttler(300);
        this.searchFunction = searchFunction;
        this._setEventListeners();
        this.minChars = 3;
    }

    ResourceSelector.prototype.clear = function() {
        this.resultsContainer.empty();
    };

    ResourceSelector.prototype.displayEmptyResults = function() {
        this.searchingResultsNode.hide();
        this.emptyResultsNode.show();
        this.searchResultsNode.hide();
    };

    ResourceSelector.prototype.displayNothing = function() {
        this.searchingResultsNode.hide();
        this.emptyResultsNode.hide();
        this.searchResultsNode.hide();
    };

    ResourceSelector.prototype.displayResults = function(resources) {
        if (!resources || !resources.length) {
            this.displayEmptyResults();
            return;
        }

        this._publishResults(resources);
        this.searchingResultsNode.hide();
        this.emptyResultsNode.hide();
        this.searchResultsNode.show();
    };

    ResourceSelector.prototype.displaySearching = function() {
        this.searchingResultsNode.show();
        this.emptyResultsNode.hide();
        this.searchResultsNode.hide();
    };

    ResourceSelector.prototype.flagPendingSearch = function() {
        if (this._pendingSearch) {
            this._pendingSearch.resolve();
        }
        this._pendingSearch = new Pending('resource-selector-search');
    };

    ResourceSelector.prototype.flagPendingSearchComplete = function() {
        if (!this._pendingSearch) {
            return;
        }
        this._pendingSearch.resolve();
        this._pendingSearch = null;
    };

    /**
     * Get resources.
     *
     * @param {String} term The term to get the results from.
     * @return {Promise}
     */
    ResourceSelector.prototype.getResources = function(term) {
        return $.when(this.searchFunction(term));
    };

    ResourceSelector.prototype.onResourceSelected = function(callback) {
        this._eventNode.on('resource-selected', callback);
    };

    ResourceSelector.prototype.search = function(term) {
        this.searchId += 1;
        this._performSearch(term, this.searchId);
    };

    ResourceSelector.prototype.setMinChars = function(minChars) {
        this.minChars = minChars;
    };

    ResourceSelector.prototype._onSearchTermKeyUp = function(e) {
        this.flagPendingSearch();
        var term = e.target.value;
        if (typeof term !== 'string' || term.length < this.minChars) {
            this.searchingResultsNode.hide();
            this.throttler.cancel();
            this.flagPendingSearchComplete();
            return;
        }

        this.displaySearching();
        this.searchId += 1;
        this.throttler.schedule(this._performSearchFactory(term, this.searchId));
    };

    ResourceSelector.prototype._onSelect = function(e) {
        e.preventDefault();
        var resource = $(e.target)
            .closest('.resource-node')
            .data('resource');
        if (!resource) {
            return;
        }
        this._eventNode.trigger('resource-selected', resource);
    };

    ResourceSelector.prototype._performSearchFactory = function(term, searchId) {
        return function() {
            this._performSearch(term, searchId).then(function() {
                this.flagPendingSearchComplete();
                return;
            }.bind(this)).catch(function() {
                this.flagPendingSearchComplete();
            }.bind(this));
        }.bind(this);
    };

    ResourceSelector.prototype._performSearch = function(term, scheduledSearchId) {
        return this.getResources(term)
            .then(
                function(data) {
                    if (this.searchId != scheduledSearchId) {
                        return;
                    }
                    this.displayResults(data);
                }.bind(this)
            )
            .fail(
                function() {
                    this.displayEmptyResults();
                }.bind(this)
            );
    };

    ResourceSelector.prototype._publishResults = function(resources) {
        this.clear();

        resources.forEach(
            function(resource) {
                var node = this.resourceTemplate.clone();
                node.find('.resource-name').text(resource.name);
                if (resource.subname) {
                    node.find('.resource-subname').text(resource.subname);
                } else {
                    node.find('.resource-subname').hide();
                }
                node.data('resource', resource);
                this.resultsContainer.append(node);
            }.bind(this)
        );
    };

    ResourceSelector.prototype._setEventListeners = function() {
        this.searchTermNode.on('keyup', this._onSearchTermKeyUp.bind(this));
        this.searchResultsNode.on('click', 'button', this._onSelect.bind(this));
    };

    return ResourceSelector;
});
