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
 * Utils.
 *
 * @module     block_xp/utils
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Copy to clipboard.
 *
 * @param {String} str
 * @param {Node} [parentNode] The parent node for fallback logic.
 * @returns
 */
export async function copyToClipboard(str, parentNode) {
    if (navigator.clipboard) {
        try {
            await navigator.clipboard.writeText(str);
        } catch (e) {
            return;
        }
        return;
    }
    var el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-99999px';
    const parent = parentNode || document.body;
    parent.appendChild(el);
    el.select();
    document.execCommand('copy');
    parent.removeChild(el);
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
export function extractNodeData(node, prefix) {
    return Object.keys(node.dataset).filter(k => k.indexOf(prefix) === 0).reduce((carry, k) => {
        let value = node.dataset[k];
        if (value === 'true' || value === 'false') {
            value = value === 'true' ? true : false;
        }
        let key = k.charAt(prefix.length).toLocaleLowerCase() + k.substring(prefix.length + 1);

        if (key.indexOf('__') > -1) {
            return setAtDepth(carry, key.split('__'), value);
        }

        return {...carry, [key]: value};
    }, {});
}

/**
 * Get data from a JSON script tag.
 *
 * @param {String} scriptId The script ID.
 * @returns {Object}
 */
export function getDataFromJsonScript(scriptId) {
    const script = document.getElementById(scriptId);
    if (!script || script.tagName !== 'SCRIPT' || script.type !== 'application/json') {
        return {};
    }
    try {
        return JSON.parse(script.textContent);
    } catch (e) {
        return {};
    }
}

/**
 * Set a value at a specific depth in an object.
 *
 * @param {Object} obj
 * @param {String[]} keys
 * @param {Any} value
 * @returns {Object}
 */
function setAtDepth(obj, keys, value) {
    let currentObj = obj;

    for (let i = 0; i < keys.length - 1; i++) {
        const key = keys[i];
        currentObj[key] = typeof currentObj[key] === 'undefined' ? {} : currentObj[key];
        currentObj = currentObj[key];
    }

    const lastKey = keys[keys.length - 1];
    currentObj[lastKey] = value;

    return obj;
}