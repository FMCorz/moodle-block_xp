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

/**
 * Compatibility file for IDE support of aliased classes.
 *
 * @package    block_xp
 * @copyright  2025 Frédéric Massart
 * @author     Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// No declared namespace, on purpose!
defined('MOODLE_INTERNAL') || die();

// This file will never be autoloaded, and should never be included either. Its content
// will never be executed, even if it is loaded by accident. It is only here for compatibility
// reasons since Moodle has deprecated the top-level classes and Intelephense does not
// understand the class_alias function (https://github.com/bmewburn/vscode-intelephense/issues/600).
if (false) {

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class block_contents extends core_block\output\block_contents {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class block_move_target extends core_block\output\block_move_target {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class action_link extends core\output\action_link {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class action_menu extends core\output\action_menu {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class bootstrap_renderer extends core\output\bootstrap_renderer {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class context_header extends core\output\context_header {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class core_renderer extends core\output\core_renderer {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class core_renderer_ajax extends core\output\core_renderer_ajax {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class core_renderer_cli extends core\output\core_renderer_cli {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class core_renderer_maintenance extends core\output\core_renderer_maintenance {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class custom_menu extends core\output\custom_menu {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class custom_menu_item extends core\output\custom_menu_item {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class file_picker extends core\output\file_picker {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class help_icon extends core\output\help_icon {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class html_writer extends core\output\html_writer {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class image_icon extends core\output\image_icon {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class initials_bar extends core\output\initials_bar {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class js_writer extends core\output\js_writer {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class paging_bar extends core\output\paging_bar {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class pix_emoticon extends core\output\pix_emoticon {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class pix_icon extends core\output\pix_icon {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class pix_icon_font extends core\output\pix_icon_font {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class pix_icon_fontawesome extends core\output\pix_icon_fontawesome {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class plugin_renderer_base extends core\output\plugin_renderer_base {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class preferences_group extends core\output\preferences_group {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class preferences_groups extends core\output\preferences_groups {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class progress_bar extends core\output\progress_bar {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class progress_trace extends core\output\progress_trace {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class renderable extends core\output\renderable {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class renderer_base extends core\output\renderer_base {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class single_button extends core\output\single_button {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class single_select extends core\output\single_select {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class tabobject extends core\output\tabobject {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class tabtree extends core\output\tabtree {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class templatable extends core\output\templatable {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class theme_config extends core\output\theme_config {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class url_select extends core\output\url_select {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class user_picture extends core\output\user_picture {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class xhtml_container_stack extends core\output\xhtml_container_stack {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class action_menu_filler extends core\output\action_menu\filler {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class action_menu_link extends core\output\action_menu\link {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class action_menu_link_primary extends core\output\action_menu\link_primary {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class action_menu_link_secondary extends core\output\action_menu\link_secondary {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class subpanel extends core\output\action_menu\subpanel {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class component_action extends core\output\actions\component_action {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class confirm_action extends core\output\actions\confirm_action {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class popup_action extends core\output\actions\popup_action {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class combined_progress_trace extends core\output\progress_trace\combined_progress_trace {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class error_log_progress_trace extends core\output\progress_trace\error_log_progress_trace {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class html_list_progress_trace extends core\output\progress_trace\html_list_progress_trace {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class html_progress_trace extends core\output\progress_trace\html_progress_trace {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class null_progress_trace extends core\output\progress_trace\null_progress_trace {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class progress_trace_buffer extends core\output\progress_trace\progress_trace_buffer {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class text_progress_trace extends core\output\progress_trace\text_progress_trace {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class renderer_factory_base extends core\output\renderer_factory\renderer_factory_base {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class renderer_factory extends core\output\renderer_factory\renderer_factory_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class standard_renderer_factory extends core\output\renderer_factory\standard_renderer_factory {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class theme_overridden_renderer_factory extends core\output\renderer_factory\theme_overridden_renderer_factory {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class fragment_requirements_manager extends core\output\requirements\fragment_requirements_manager {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class page_requirements_manager extends core\output\requirements\page_requirements_manager {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class YUI_config extends core\output\requirements\yui {
    }
}
