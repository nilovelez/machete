<?php

/**
 *
 * Uninstall script
 *
 * This file contains all the logic required to uninstall the plugin
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