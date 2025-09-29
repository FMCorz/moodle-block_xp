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
    class cache_application extends core_cache\application_cache {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache extends core_cache\cache {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_lock_interface extends core_cache\cache_lock_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cacheable_object_array extends core_cache\cacheable_object_array {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cacheable_object extends core_cache\cacheable_object_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_cached_object extends core_cache\cached_object {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_config extends core_cache\config {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_config_writer extends core_cache\config_writer {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_is_configurable extends core_cache\configurable_cache_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_data_source extends core_cache\data_source_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_definition extends core_cache\definition {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_disabled extends core_cache\disabled_cache {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_config_disabled extends core_cache\disabled_config {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_factory_disabled extends core_cache\disabled_factory {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cachestore_dummy extends core_cache\dummy_cachestore {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_factory extends core_cache\factory {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_helper extends core_cache\helper {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_is_key_aware extends core_cache\key_aware_cache_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_loader extends core_cache\loader_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_loader_with_locking extends core_cache\loader_with_locking_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_is_lockable extends core_cache\lockable_cache_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_request extends core_cache\request_cache {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_is_searchable extends core_cache\searchable_cache_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_session extends core_cache\session_cache {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_store extends core_cache\store {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_store_interface extends core_cache\store_interface {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    class cache_ttl_wrapper extends core_cache\ttl_wrapper {
    }

    /**
     * Fake class to mitigate IDE's failure to identify class_alias.
     */
    abstract class cache_data_source_versionable extends core_cache\versionable_data_source_interface {
    }
}
