<?php
/*
Plugin Name: Machete
Plugin URI: http://machete.nilovelez.com
Description: Machete is a lean and simple suite of tools that solve common WordPress anoyances: cookie bar, tracking codes, header cleanup
Version: 1.5.2
Author: Nilo Velez
Author URI: http://www.nilovelez.com
License: WTFPL
License URI: http://www.wtfpl.net/txt/copying/

Text Domain: machete
Domain Path: /languages
*/


if ( ! defined( 'ABSPATH' ) ) exit;


register_activation_hook( __FILE__, 'machete_screen_activate' );
function machete_screen_activate() {
	set_transient( '_machete_welcome_redirect', true, 30 );
}

function machete_load_plugin_textdomain() {
    load_plugin_textdomain( 'machete', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

function machete_init(){

	$machete_get_upload_dir = wp_upload_dir();
	define('MACHETE_BASE_PATH', plugin_dir_path( __FILE__ ));
	define('MACHETE_RELATIVE_BASE_PATH', substr(MACHETE_BASE_PATH, strlen(ABSPATH)-1));
	define('MACHETE_BASE_URL',  plugin_dir_url( __FILE__ ));

	define('MACHETE_DATA_PATH', $machete_get_upload_dir['basedir'].'/machete/');
	define('MACHETE_RELATIVE_DATA_PATH', substr(MACHETE_DATA_PATH, strlen(ABSPATH)-1));
	define('MACHETE_DATA_URL',  $machete_get_upload_dir['baseurl'].'/machete/');

	if ( ! is_admin() ) {

		require_once('machete_frontend.php');

		if ( is_user_logged_in() ){
			// only logged in users need l10n
			add_action( 'plugins_loaded', 'machete_load_plugin_textdomain' );
			require_once('inc/admin_bar.php');
		}

	}else if (current_user_can('manage_options')){
		
		define('MACHETE_ADMIN_INIT',true);
		add_action( 'plugins_loaded', 'machete_load_plugin_textdomain' );
		require_once('machete_admin.php');	
		require_once('inc/admin_bar.php');
	}
}
add_action('init','machete_init');