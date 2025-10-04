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
// https://levelup.plus

namespace block_xp\local\backup;

use block_xp\local\shortcode\handler;
use context;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/util/helper/restore_decode_rule.class.php');

/**
 * Decode rule.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class xpladder_decode_rule extends \restore_decode_rule {

    /**
     * Constructor.
     */
    public function __construct($placeholder = 'BLOCKXPSHORTCODEXPLADDER') {
        parent::__construct($placeholder, '', ['context', 'top', 'hidelink', 'withprogress']);
    }

    /**
     * Nasty override to get things done.
     *
     * @param string $content The content.
     * @return string
     */
    public function decode($content) {
        if (preg_match_all($this->cregexp, $content, $matches) === 0) {
            return $content;
        }

        foreach ($matches[0] as $key => $tosearch) {
            $context = null;
            $top = null;
            $hidelink = null;
            $withprogress = null;
            foreach ($this->mappings as $mappingkey => $mappingsource) {
                $oldvalue = $matches[$mappingkey][$key];
                if ($mappingsource === 'context') {
                    $newid = (int) $this->get_mapping('context', $oldvalue);
                    if ($newid) {
                        $context = context::instance_by_id($newid);
                    }
                } else if ($mappingsource === 'top') {
                    $top = (int) $oldvalue;
                } else if ($mappingsource === 'hidelink') {
                    $hidelink = (bool) $oldvalue;
                } else if ($mappingsource === 'withprogress') {
                    $withprogress = (bool) $oldvalue;
                }
            }

            $shortcode = "[xpladder";
            if ($context) {
                $shortcode .= " ctx={$context->id} secret=" . handler::get_xpladder_secret($context);
            }
            if ($top) {
                $shortcode .= " top={$top}";
            }
            if ($hidelink) {
                $shortcode .= " hidelink";
            }
            if ($withprogress) {
                $shortcode .= " withprogress";
            }
            $shortcode .= "]";
            $content = str_replace($tosearch, $shortcode, $content);
        }

        return $content;
    }

    /**
     * Encodes the content.
     *
     * @param string $content The content.
     * @return string The content.
     */
    public static function encode_content($content) {
        global $CFG;

        if (!class_exists('filter_shortcodes\shortcodes')) {
            return $content;
        }

        require_once($CFG->dirroot . '/filter/shortcodes/lib/helpers.php');
        $content = filter_shortcodes_process_text($content, function ($tag) {
            if ($tag !== 'xpladder') {
                return null;
            }
            return (object) ['hascontent' => false, 'contentprocessor' => function ($args, $content) {
                $top = $args['top'] ?? 0;
                return '$@BLOCKXPSHORTCODEXPLADDER'
                    . '*' . ($args['ctx'] ?? '0')
                    . '*' . ($top === true ? '10' : $top)
                    . '*' . (($args['hidelink'] ?? false) ? '1' : '0')
                    . '*' . (($args['withprogress'] ?? false) ? '1' : '0')
                    . '@$';
            }];
        });

        return $content;
    }

    /**
     * Bypass the validation.
     *
     * @param string $linkname The link name.
     * @param string $urltemplate The URL template.
     * @param string $mappings The mapping.
     * @return array
     */
    protected function validate_params($linkname, $urltemplate, $mappings) {
        $mappings = (array) $mappings;
        return array_combine(range(1, count($mappings)), $mappings);
    }

}
