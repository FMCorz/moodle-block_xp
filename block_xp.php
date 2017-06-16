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

/**
 * Block XP.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Well, well, well... what have we got here? An evil eval!
// Don't you worry, this is safe! Yes, I know, it is very scary, but some
// arbitrary code could not possibly be a valid class name, and a subclass
// of block_base and still be an exploit.
//
// "OK, but why did you even do this Fred? Evals are evil!". Well, you see,
// I need to be able to substitude the class responsible for setting up
// the block, but as Moodle core is expecting a very specific class name, I
// don't have other options than to dynamically create that class. I have
// tried to find other solutions, but I didn't succeed. Let me know if you've
// got a better idea!
//
// By the way, this is not the first eval in Moodle. Did you know that each
// web service call triggers an eval? Check webservice::init_service_class().
// The calculated question type also uses eval.
$class = \block_xp\di::get('block_class');
if (!class_exists($class) || !is_subclass_of($class, 'block_base')) {
    throw new coding_exception('Block class does not pass validation, or does not exist.');
}
eval("class block_xp_block_class extends {$class} {}"); // @codingStandardsIgnoreLine.

/**
 * Block XP class.
 *
 * Typically we do not need to do this, but some automated checks want to
 * make sure that we're creating the right class here. Adding this
 * shows green instead of red, and nobody likes red.
 *
 * @package    block_xp
 * @copyright  2017 Branch Up Pty Ltd
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_xp extends block_xp_block_class {
}
