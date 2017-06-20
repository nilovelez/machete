<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( is_user_logged_in() ){
	// only logged in users need l10n
	add_action( 'plugins_loaded', 'machete_load_plugin_textdomain' );
}

foreach ($machete_active_modules as $machete_module => $machete_module_name) {
	@require_once('inc/'.$machete_module.'/frontend_functions.php');
}
/*
require_once('inc/cleanup/frontend_functions.php');

require_once('inc/utils/frontend_functions.php');

require_once('inc/cookies/frontend_functions.php');

require_once('inc/maintenance/frontend_functions.php');

require_once('inc/powertools/frontend_functions.php');
*/  
