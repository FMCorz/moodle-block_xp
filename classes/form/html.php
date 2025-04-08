<?php
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

namespace block_xp\form;

use HTML_QuickForm_html;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/pear/HTML/QuickForm/html.php');

/**
 * Form field.
 *
 * Support lazily loading an arbitrary HTML value.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class html extends HTML_QuickForm_html {

    /** @var object|string|callable The lazy string. */
    protected $content;

    /**
     * Constructor.
     *
     * @param string|null $elementname The name.
     * @param object|string|callable|null $content The lazy HTML.
     */
    public function __construct($elementname = null, $content = null) {
        $this->content = $content;
        parent::__construct('');
        if (!empty($elementname)) {
            $this->setName($elementname);
        }
    }

    public function toHtml() { // @codingStandardsIgnoreLine
        $content = $this->content;
        if (is_callable($content)) {
            $content = $content();
        }
        if (is_object($content)) {
            return (string) $content;
        }
        return (string) ($content ?? '');
    }

    /**
     * Register.
     */
    public static function name() {
        \MoodleQuickForm::registerElementType('block_xp_html', __FILE__, html::class); // @codingStandardsIgnoreLine
        return 'block_xp_html';
    }

}
