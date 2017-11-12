<?php
/*
Plugin Name: Machete
Plugin URI: https://machetewp.com
Description: Machete is a lean and simple suite of tools that solve common WordPress anoyances: cookie bar, tracking codes, header cleanup
Version: 2.1
Author: Nilo Velez
Author URI: http://www.nilovelez.com
License: WTFPL
License URI: http://www.wtfpl.net/txt/copying/

Text Domain: machete
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$machete_get_upload_dir = wp_upload_dir();
define('MACHETE_BASE_PATH', plugin_dir_path( __FILE__ ));
define('MACHETE_RELATIVE_BASE_PATH', substr(MACHETE_BASE_PATH, strlen(ABSPATH)-1));
define('MACHETE_BASE_URL',  plugin_dir_url( __FILE__ ));

define('MACHETE_DATA_PATH', $machete_get_upload_dir['basedir'].'/machete/');
define('MACHETE_RELATIVE_DATA_PATH', substr(MACHETE_DATA_PATH, strlen(ABSPATH)-1));
define('MACHETE_DATA_URL',  $machete_get_upload_dir['baseurl'].'/machete/');


register_activation_hook( __FILE__, 'machete_screen_activate' );
function machete_screen_activate() {
	add_option( 'machete_activation_welcome', 'pending');
}

function machete_load_plugin_textdomain() {
    load_plugin_textdomain( 'machete', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

if (!isset($machete_modules)) $machete_modules = array();


class machete {
	public $modules = array();

	public function __construct(){
		add_action('init',array($this,'init'));
	}

	public function init(){
		global $machete;
		global $machete_modules;

		require ('inc/machete_modules.php');


		if ( ! is_admin() ) {
			define('MACHETE_FRONT_INIT',true);
			require_once('machete_frontend.php');
		} else {
			define('MACHETE_ADMIN_INIT',true);
			require_once('machete_admin.php');	
		}
	}
}
$machete = new machete();