// This file is part of Level Up XP.
//
// Level Up XP is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Level Up XP is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Level Up XP.  If not, see <https://www.gnu.org/licenses/>.
//
// https://levelup.plus

/**
 * Course selector.
 *
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Modal from 'core/modal';
import ModalEvents from 'core/modal_events';
import {asyncRender} from 'block_xp/compat';
import CourseResourceSelector from 'block_xp/course-resource-selector';
import Config from 'core/config';
import $ from 'jquery';

export const openCourseSelector = async(onSelected) => {
    const {html} = await asyncRender('block_xp/modal-course-selector', {});
    const modal = new Modal(html);
    modal.setRemoveOnClose(true);

    const rootJq = modal.getRoot();
    const root = rootJq[0];

    rootJq.addClass('block_xp');
    rootJq.on(ModalEvents.shown, () => {
        const container = root.querySelector('.search-result-contents');
        const termField = root.querySelector('.search-term-course');
        const cs = new CourseResourceSelector($(container), $(termField));
        const recent = getRecentlyUsed();
        if (recent.length) {
            cs.displayResults(recent.slice(0, 10));
        }
        cs.onResourceSelected(function(e, resource) {
            saveRecentlyUsed(resource);
            onSelected(resource.course);
            modal.hide();
        });
    });

    modal.show();
};

const getRecentlyUsedCacheKey = () => {
    const userId = Config.userId;
    if (!userId) {
        return null;
    }
    return 'block_xp_course_selector_recents_' + userId;
};

const getRecentlyUsed = () => {
    const key = getRecentlyUsedCacheKey();
    if (!key) {
        return [];
    }

    const data = readArrayFromStorage(key);
    const threshold = Date.now() - 5 * 24 * 60 * 60 * 1000; // 2 days ago.
    return data.filter(d => d.time > threshold).sort((a, b) => b.time - a.time).map(d => d.resource);
};

const readFromStorage = (key, fallback = null) => {
    try {
        const data = sessionStorage.getItem(key);
        return data !== null ? JSON.parse(data) : fallback;
    } catch (e) {
        return fallback;
    }
};

const readArrayFromStorage = (key) => {
    const data = readFromStorage(key, []);
    return Array.isArray(data) ? data : [];
};

const saveRecentlyUsed = (resource) => {
    const key = getRecentlyUsedCacheKey();
    if (!key) {
        return;
    }

    let data = readArrayFromStorage(key);
    data = data.filter(d => d.resource.course.id !== resource.course.id);
    data.push({
        time: Date.now(),
        resource: resource,
    });

    sessionStorage.setItem(key, JSON.stringify(data));
};
