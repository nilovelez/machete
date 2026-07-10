<?php
/**
 * Plugin Name: Machete
 * Plugin URI: https://machetewp.com
 * Description: Machete is a lean and simple suite of tools that makes WordPress development easier: cookie bar, tracking codes, custom code editor, header cleanup, post and page cloner
 * Version: 5.3
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Author: Nilo Velez
 * Author URI: https://www.nilovelez.com
 * License: WTFPL
 * License URI: http://www.wtfpl.net/txt/copying/

 * Text Domain: machete
 * Domain Path: /languages

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MACHETE_VERSION', '5.3' );

$machete_get_upload_dir = wp_upload_dir();
define( 'MACHETE_BASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'MACHETE_RELATIVE_BASE_PATH', substr( MACHETE_BASE_PATH, strlen( ABSPATH ) - 1 ) );
define( 'MACHETE_BASE_URL', plugin_dir_url( __FILE__ ) );

define( 'MACHETE_DATA_PATH', $machete_get_upload_dir['basedir'] . '/machete/' );
define( 'MACHETE_RELATIVE_DATA_PATH', substr( MACHETE_DATA_PATH, strlen( ABSPATH ) - 1 ) );
define( 'MACHETE_DATA_URL', $machete_get_upload_dir['baseurl'] . '/machete/' );

register_activation_hook(
	__FILE__,
	function () {
		add_option( 'machete_activation_welcome', 'pending' );
	}
);

// Include main Machete classes.
require MACHETE_BASE_PATH . 'inc/class-machete.php';
require MACHETE_BASE_PATH . 'inc/class-machete-module.php';

// Include Machete modules.
global $machete;
$machete = new MACHETE();
require MACHETE_BASE_PATH . 'inc/about/class-machete-about-module.php';
require MACHETE_BASE_PATH . 'inc/cleanup/class-machete-cleanup-module.php';
require MACHETE_BASE_PATH . 'inc/cookies/class-machete-cookies-module.php';
require MACHETE_BASE_PATH . 'inc/utils/class-machete-utils-module.php';
require MACHETE_BASE_PATH . 'inc/maintenance/class-machete-maintenance-module.php';
require MACHETE_BASE_PATH . 'inc/clone/class-machete-clone-module.php';
require MACHETE_BASE_PATH . 'inc/social/class-machete-social-module.php';
require MACHETE_BASE_PATH . 'inc/woocommerce/class-machete-woocommerce-module.php';
require MACHETE_BASE_PATH . 'inc/powertools/class-machete-powertools-module.php';

// Management of enabled modules.
if ( null === get_option( 'machete_enabled_modules', null ) ) {
	$machete_enabled_modules = array();
	foreach ( $machete->modules as $machete_module ) {
		if ( ! $machete_module->params['can_be_disabled'] ) {
			continue;
		}
		if ( $machete_module->params['is_active_default'] ) {
			$machete_enabled_modules[] = $machete_module->params['slug'];
		}
	}
	add_option( 'machete_enabled_modules', $machete_enabled_modules );
}

$machete_enabled_modules = get_option( 'machete_enabled_modules', array() );
foreach ( $machete->modules as $machete_module ) {
	if ( ! $machete_module->params['can_be_disabled'] ) {
		continue;
	}
	$machete_module->params['is_active'] = in_array(
		$machete_module->params['slug'],
		$machete_enabled_modules,
		true
	);
}

// Main init.
add_action(
	'init',
	function () {
		global $machete;

		load_plugin_textdomain( 'machete', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Disable WooCommerce module if WooCommece is active.
		if ( ! function_exists( 'is_woocommerce' ) ) {
			$machete->modules['woocommerce']->params['is_active']      = false;
			$machete->modules['woocommerce']->params['can_be_enabled'] = false;
			$machete->modules['woocommerce']->params['description']   .= ' ' . __( 'You have to install and activate WooCommerce to use this module.', 'machete' );
		}

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			define( 'MACHETE_CLI_INIT', true );
		} elseif ( ! is_admin() ) {
			define( 'MACHETE_FRONT_INIT', true );
			require_once 'machete-frontend.php';
		} else {
			define( 'MACHETE_ADMIN_INIT', true );
			require_once 'machete-admin.php';
		}
	}
);
