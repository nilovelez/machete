<?php
/**
 * Machete code only usable in the WordPress admin

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
};

add_action( 'plugins_loaded', 'machete_load_plugin_textdomain' );

// Manages welcome redirect to About page.
add_action( 'admin_init', function() {
	// Bail if no activation redirect.
	if ( 'pending' === get_option( 'machete_activation_welcome' ) ) {
		delete_option( 'machete_activation_welcome' );

		// Bail if activating from network, or bulk.
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		// Redirect to about page.
		wp_safe_redirect( add_query_arg( array( 'page' => 'machete' ), admin_url( 'admin.php' ) ) );
	}
});

// Content especific to Machete admin pages.
add_action( 'current_screen', function() {
	if ( false === strpos( get_current_screen()->id, 'machete' ) ) {
		return;
	}

	// Machete pages footer credits.
	add_filter('admin_footer_text', function() {
		/* translators: %s: five stars */
		return ' ' . sprintf( __( 'If you like <strong>Machete</strong>, please %1$sleave us a rating of %2$s. Thank you!', 'machete' ), '<a href="https://wordpress.org/support/plugin/machete/reviews/#new-post" target="_blank">', '5&starf;</a>' ) . ' ';
	});

	// Enqueue admin styles.
	add_action( 'admin_enqueue_scripts', function() {
		wp_register_style( 'machete_admin_css', plugin_dir_url( __FILE__ ) . 'css/admin_v4.css', false, '4.0.0' );
		wp_enqueue_style( 'machete_admin_css' );
	});
});

// Add "settings" link to Machete in the plugin list.
add_filter( 'plugin_action_links', function( $plugin_actions, $plugin_file ) {
	$new_actions = array();
	if ( basename( dirname( __FILE__ ) ) . '/machete.php' === $plugin_file ) {
		/* translators: %s: url of plugin settings page */
		$new_actions['sc_settings'] = sprintf( __( '<a href="%s">Settings</a>', 'machete' ), esc_url( add_query_arg( array( 'page' => 'machete' ), admin_url( 'admin.php' ) ) ) );
	}
	return array_merge( $new_actions, $plugin_actions );
}, 10, 2 );

// Add machete to the Admin sidemenu.
add_action('admin_menu', function() {
	global $machete;
	add_menu_page(
		'Machete',
		'Machete',
		'publish_posts', // targeting Author role.
		'machete',
		function() {
			global $machete;
			require MACHETE_BASE_PATH . 'inc/about/admin_content.php';
		},
		plugin_dir_url( __FILE__ ) . 'img/machete.svg'
	);
});

// Call to admin() method of all active modules.
foreach ( $machete->modules as $module ) {
	if ( ! $module->params['is_active'] ) {
		continue;
	};
	$module->admin();
}
