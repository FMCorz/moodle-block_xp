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
 * Compat.
 *
 * @module     block_xp/compat
 * @copyright  2024 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import DynamicForm from 'core_form/dynamicform';
import Modal from 'core/modal';
import ModalFactory from 'core/modal_factory';
import ModalForm from 'core_form/modalform';
import ModalRegistry from 'core/modal_registry';
import Templates from 'core/templates';

const IS_MODAL_TYPE_DEPRECATED = 'create' in Modal;

/**
 * Render a template asynchronously.
 *
 * @param {String} name
 * @param {Object} context
 */
export const asyncRender = (name, context) => {
    if ('renderForPromise' in Templates) {
        return Templates.renderForPromise(name, context);
    }
    return new Promise((resolve, reject) => {
        Templates.render(name, context).then((html, js) => {
            resolve({html, js});
            return;
        }).catch((err) => {
            reject(err);
            return;
        });
    });
};

/**
 * Create a modal.
 *
 * Compatibility function until we drop support for Moodle <4.3.
 *
 * @param {Object} config
 * @param {Function} [ModalClass]
 * @returns {Promise<Modal>}
 */
export function createModal(config, ModalClass = Modal) {
    if (IS_MODAL_TYPE_DEPRECATED) {
        delete config.type;
        return ModalClass.create(config);
    }

    const typeName = config.type ?? config.template;
    let type = ModalRegistry.get(typeName);
    if (!type) {
        ModalRegistry.register(typeName, ModalClass, config.template);
    }

    return ModalFactory.create({
        ...config,
        type: typeName,
    });
}

/**
 * Get form node.
 *
 * @param {ModalForm|DynamicForm} form The form.
 * @returns {Node}
 */
export function getFormNode(form) {
    try {
        return form.getFormNode();
    } catch (e) {
        if (form instanceof ModalForm) {
            return form.modal.getRoot().find('form')[0];
        } else if (form instanceof DynamicForm) {
            return form.container.querySelector('form');
        }
        return document.createElement('form');
    }
}

/**
 * Mark the form as submitted.
 *
 * @param {Node} node A DOM node.
 */
export function markFormSubmitted(node) {
    try {
        require('core_form/changechecker', function(ChangeChecker) {
            ChangeChecker.markFormSubmitted(node);
        });
    } catch (e) {
        if (typeof M.core_formchangechecker !== 'undefined') {
            M.core_formchangechecker.set_form_submitted();
        }
    }
}
