<?php
/**
 * Uninstall script
 *
 * This file contains all the logic required to uninstall the plugin
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// machete module manager options.
delete_option( 'machete_disabled_modules' );

// machete cleanup options.
delete_option( 'machete_cleanup_settings' );

// machete utils options.
delete_option( 'machete_utils_settings' );

// machete cookie bar options.
delete_option( 'machete_cookies_settings' );

// machete maintenance mode options.
delete_option( 'machete_maintenance_settings' );
