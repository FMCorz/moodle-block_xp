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
 * Modal.
 *
 * @module     block_xp/modal
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalCancel from 'core/modal_cancel';
import * as Str from 'core/str';
import Templates from 'core/templates';
import * as Compat from 'block_xp/compat';
import * as RoleButton from 'block_xp/role-button';
import {extractNodeData, getDataFromJsonScript} from 'block_xp/utils';

let simpleOpenModalActionObserverRegistered = false;
let simpleOpenModalActionObserverSelector = '[data-xp-action="open-modal"]';

/**
 * Open the modal.
 *
 * @param {Node} node The node.
 */
async function openSimpleModal(node) {
    const template = node.dataset.template;
    const templateContext = getDataFromJsonScript(node.dataset.templateData ?? '');
    const modalArgs = extractNodeData(node, 'modal');

    // We only want to render our templates.
    if (!template.startsWith('block_xp/') && !template.startsWith('local_xp/')) {
        return;
    }

    let modalConfig = {
        body: Templates.render(template, templateContext),
        type: 'CANCEL',
    };
    if ('title' in modalArgs) {
        modalConfig.title = modalArgs.title;
    }
    if ('large' in modalArgs) {
        modalConfig.large = Boolean(modalConfig.large);
    }

    const modal = await Compat.createModal(modalConfig, ModalCancel);
    modal.getRoot()[0].classList.add('block_xp');
    modal.setButtonText('cancel', Str.get_string('closebuttontitle', 'core'));
    modal.show();
}

/**
 * Open the modal from a selector.
 *
 * @param {String} selector
 */
export function openSimpleModalFromSelector(selector) {
    const node = document.querySelector(selector);
    if (!node) {
        return;
    }
    openSimpleModal(node);
}

/**
 * Register simple open modal action observer.
 */
export function registerSimpleOpenModalActionObserver() {
    if (simpleOpenModalActionObserverRegistered) {
        return;
    }
    simpleOpenModalActionObserverRegistered = true;
    RoleButton.delegateClick('body', simpleOpenModalActionObserverSelector, (node) => {
        openSimpleModal(node);
    });
}
