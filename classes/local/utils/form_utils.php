<?php
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
// See <https://levelup.plus>.

namespace block_xp\local\utils;

/**
 * Form utils.
 *
 * @package    block_xp
 * @copyright  2026 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class form_utils {
    /**
     * Freeze the form field locked by config.
     *
     * @param \MoodleQuickForm $mform
     * @return bool
     */
    public static function freeze_config_locked_fields($mform) {
        foreach (array_keys($mform->_elements) as $elementindex) {
            $element =& $mform->_elements[$elementindex] ?? null;
            if (!$element || !method_exists($element, 'getName')) {
                continue;
            }
            $name = $element->getName();
            if (static::is_field_config_locked($mform, $name)) {
                $mform->hardFreeze($name);
            }
        }
    }

    /**
     * Whether a form field is locked by config.
     *
     * This returns false when the field does not exist.
     *
     * @param \MoodleQuickForm $mform
     * @param string $name
     * @return bool
     */
    public static function is_field_config_locked($mform, $name) {
        $configlocked = \block_xp\di::get('config_locked');
        return $configlocked->has($name)
            && (bool) $configlocked->get($name)
            && $mform->elementExists($name);
    }
}
