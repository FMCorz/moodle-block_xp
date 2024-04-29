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
 * Modal form.
 *
 * @module     block_xp/modal-form
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalForm from 'core_form/modalform';
import * as Compat from 'block_xp/compat';
import * as RoleButton from 'block_xp/role-button';

/**
 * Open the modal.
 *
 * @param {Node} node The node.
 */
function open(node) {
    const formClass = node.dataset.formClass;
    const formArgs = extractNodeData(node, 'formArgs');
    const modalConfig = extractNodeData(node, 'modal');

    var modalForm = new ModalForm({
        formClass: formClass,
        args: formArgs,
        returnFocus: node,
        modalConfig: {
            title: modalConfig.title ?? node.textContent.slice(0, 100),
        }
    });
    modalForm.addEventListener(modalForm.events.LOADED, () => {
        const root = modalForm.modal.getRoot();
        root.addClass('block_xp');
    });

    modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, (e) => {
        e.preventDefault();

        // We must mark the form as submitted because the core modalform sets it back
        // to not having been sent, and we'll reload the page so when the form only
        // has one field, this may be an issue.
        Compat.markFormSubmitted(Compat.getFormNode(modalForm));

        if (e.detail && e.detail.redirecturl) {
            window.location.href = M.cfg.wwwroot + e.detail.redirecturl;
        } else {
            window.location.reload();
        }
    });

    modalForm.show();
}

/**
 * Delegate open.
 * @param {String} rootSelector The root selector.
 * @param {String} selector The selector.
 */
export function delegateOpen(rootSelector, selector) {
    RoleButton.delegateClick(rootSelector, selector, (node) => {
        open(node);
    });
}

/**
 * Register open.
 * @param {String} selector The selector.
 */
export function registerOpen(selector) {
    RoleButton.registerClick(selector, (node) => {
        open(node);
    });
}

/**
 * Extract data from dataset.
 *
 * This extracts data at a prefix, and converts in nested objects if needed.
 *
 * @param {Node} node The HTML node.
 * @param {String} prefix The data prefix.
 * @returns {Object}
 */
function extractNodeData(node, prefix) {
    return Object.keys(node.dataset).filter(k => k.indexOf(prefix) === 0).reduce((carry, k) => {
        let value = node.dataset[k];
        if (value === 'true' || value === 'false') {
            value = value === 'true' ? true : false;
        }
        let key = k.charAt(prefix.length).toLocaleLowerCase() + k.substring(prefix.length + 1);

        if (key.indexOf('__') > -1) {
            let [parentKey, childKey] = key.split('__', 2);
            if (parentKey) {
                return {
                    ...carry,
                    [parentKey]: {
                        ...carry[parentKey] || {},
                        [childKey]: value
                    }
                };
            }
            key = childKey;
        }

        return {...carry, [key]: value};
    }, {});
}
