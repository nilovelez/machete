<?php

/**
 *
 * Uninstall script
 *
 * This file contains all the logic required to uninstall the plugin
 *
 *
 * @package 	WordPress.com Stats Smiley Remover
 * @copyright	Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		WordPress.com Stats Smiley Remover 15.01
 *
 *
 */


if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit;

// machete cleanup options:
delete_option('machete_cleanup_settings');

// machete utils options:
delete_option('machete_utils_settings');


// machete cookie bar options:
delete_option('machete_cookies_settings');