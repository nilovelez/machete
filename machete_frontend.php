<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( is_user_logged_in() ){
	// only logged in users need l10n
	add_action( 'plugins_loaded', 'machete_load_plugin_textdomain' );
}

if(($machete_cleanup_settings = get_option('machete_cleanup_settings')) && (count($machete_cleanup_settings) > 0)){
	require_once('inc/cleanup/frontend_functions.php');
	machete_optimize($machete_cleanup_settings);
}


require_once('inc/utils/frontend_functions.php');

require_once('inc/cookies/frontend_functions.php');

if($machete_maintenance_settings = get_option('machete_maintenance_settings')){
	require_once('inc/maintenance/admin_bar.php');
    require_once( 'inc/maintenance/frontend_functions.php' );
	$machete_maintenance = new MACHETE_MAINTENANCE($machete_maintenance_settings);
}

if (defined ('MACHETE_POWERTOOLS_INIT') ) {
	if(($machete_powertools_settings = get_option('machete_powertools_settings')) && (count($machete_powertools_settings) > 0)){
		require_once('inc/powertools/frontend_functions.php');
		machete_powertools($machete_powertools_settings);
	}
}