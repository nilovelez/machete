<?php
/*
Plugin Name: Machete
Plugin URI: https://machetewp.com
Description: Machete is a lean and simple suite of tools that makes WordPress development easier: cookie bar, tracking codes, custom code editor, header cleanup, post and page cloner
Version: 3.0.5
Author: Nilo Velez
Author URI: https://www.nilovelez.com
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

define('MACHETE_VERSION', '3.0.5');

register_activation_hook( __FILE__, function(){
	add_option( 'machete_activation_welcome', 'pending');
});

function machete_load_plugin_textdomain() {
    load_plugin_textdomain( 'machete', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

// include main Machete classes
require ('inc/class-machete.php');
require ('inc/class-machete-module.php');

// include Machete modules
$machete = new machete();
require ('inc/about/module.php');
require ('inc/cleanup/module.php');
require ('inc/cookies/module.php');
require ('inc/utils/module.php');
require ('inc/maintenance/module.php');
require ('inc/clone/module.php');
require ('inc/importexport/module.php');
require ('inc/powertools/module.php');

// manage disabled modules
if($machete_disabled_modules = get_option('machete_disabled_modules')){
	foreach ($machete_disabled_modules as $module) {
		
		if (isset($machete->modules[$module]) && $machete->modules[$module]->params['can_be_disabled']){
			$machete->modules[$module]->params['is_active'] = false;
		}
	}
}

// manage external modules
if (defined('MACHETE_POWERTOOLS_INIT')) {
	$machete->modules['powertools']->params['is_active'] = true;
	$machete->modules['powertools']->params['description'] = __('Machete PowerTools are now active! Enjoy your new toy!','machete');
}

// main init
add_action('init', function(){

	global $machete;

	if ( ! is_admin() ) {
		define('MACHETE_FRONT_INIT',true);
		require_once('machete_frontend.php');
	} else {
		define('MACHETE_ADMIN_INIT',true);
		require_once('machete_admin.php');	
	}
});