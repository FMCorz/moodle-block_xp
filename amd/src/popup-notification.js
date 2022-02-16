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
 * Notification popup.
 *
 * @copyright  2022 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Templates from 'core/templates';
import Modal from 'core/modal';
import Notification from 'core/notification';

// Trigger pre-loading.
Templates.render('block_xp/modal-popup-notification', []);

/**
 * Open the modal.
 *
 * @param {Object} options The options.
 */
function init(options) {
    Templates.render('block_xp/modal-popup-notification', options).then((html) => {
        const modal = new Modal(html);
        modal.show();
        return;
    }).catch(Notification.exception);
}

/**
 * Init with JSON node.
 *
 * @param {String} selector The JSON node selector.
 */
const initWithJson = (selector) => {
    try {
        const node = document.querySelector(selector);
        const data = node ? JSON.parse(node.textContent) : null;
        if (!data) {
            throw new Error('That\'s a bit strange.');
        }
        init(data);
    } catch (err) {
        // Nothing.
    }
};

export {init, initWithJson};
