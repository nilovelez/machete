<?php
/**
 * Machete code only usable in the WordPress admin

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
}

// Manages welcome redirect to About page.
add_action(
	'admin_init',
	function() {
		// Bail if no activation redirect.
		if ( 'pending' === get_option( 'machete_activation_welcome' ) ) {
			delete_option( 'machete_activation_welcome' );

			// Bail if activating from network, or bulk.
			if ( is_network_admin() || ( filter_input( INPUT_GET, 'activate-multi' ) !== null ) ) {
				return;
			}
			// Redirect to about page.
			wp_safe_redirect( add_query_arg( array( 'page' => 'machete' ), admin_url( 'admin.php' ) ) );
		}
	}
);

// Warning for Machete 3 users.
add_action(
	'admin_init',
	function() {
		global $machete;
		if ( $machete->modules['cookies']->params['is_active'] ) {

			$cookie_options = get_option( 'machete_cookies_settings' );
			// cookies_4943ac95.js .
			if (
				$cookie_options &&
				( isset( $cookie_options['cookie_filename'] ) ) &&
				( strpos( $cookie_options['cookie_filename'], '_mct4_' ) === false )
			) {
				$module_url = add_query_arg( 'page', 'machete-cookies', admin_url( 'admin.php' ) );
				/* Translators: 1: link open tag 2: link close tag */
				$machete->notice( sprintf( __( 'You are using Cookie settings from a previous Machete version. Go to the %1$sCookies Module page%2$s and <strong>Save Settings</strong> to remove this notice.', 'machete' ), '<a href="' . $module_url . '">', '</a>' ), 'warning', false );
			}
		}
		if ( $machete->modules['utils']->params['is_active'] ) {

			$tracking_options = get_option( 'machete_utils_settings' );
			if (
				$tracking_options &&
				( 'none' !== $settings['tracking_format'] ) &&
				( isset( $tracking_options['tracking_id'] ) ) &&
				( ! isset( $tracking_options['tracking_filename'] ) )
			) {
				$module_url = add_query_arg( 'page', 'machete-utils', admin_url( 'admin.php' ) );
				/* Translators: 1: link open tag 2: link close tag */
				$machete->notice( sprintf( __( 'You are using Tracking settings from a previous Machete version. Go to the %1$sAnalytics & Code Module page%2$s and <strong>Save Settings</strong> to remove this notice.', 'machete' ), '<a href="' . $module_url . '">', '</a>' ), 'warning', false );
			}
		}
	}
);


// Content specific to Machete admin pages.
add_action(
	'current_screen',
	function() {
		if ( false === strpos( get_current_screen()->id, 'machete' ) ) {
			return;
		}

		// Machete pages footer credits.
		add_filter(
			'admin_footer_text',
			function() {
				/* translators: %s: five stars */
				return ' ' . sprintf( __( 'If you like <strong>Machete</strong>, please %1$sleave us a rating of %2$s. Thank you!', 'machete' ), '<a href="https://wordpress.org/support/plugin/machete/reviews/#new-post" target="_blank">', '5&starf;</a>' ) . ' ';
			}
		);

		// Enqueue admin styles.
		add_action(
			'admin_enqueue_scripts',
			function() {
				wp_enqueue_style(
					'machete_admin_4',
					plugin_dir_url( __FILE__ ) . 'css/admin.css',
					array(),
					MACHETE_VERSION
				);
			}
		);
	}
);

// Add "settings" link to Machete in the plugin list.
add_filter(
	'plugin_action_links',
	function( $plugin_actions, $plugin_file ) {
		$new_actions = array();
		if ( basename( dirname( __FILE__ ) ) . '/machete.php' === $plugin_file ) {
			/* translators: %s: url of plugin settings page */
			$new_actions['sc_settings'] = sprintf( __( '<a href="%s">Settings</a>', 'machete' ), esc_url( add_query_arg( array( 'page' => 'machete' ), admin_url( 'admin.php' ) ) ) );
		}
		return array_merge( $new_actions, $plugin_actions );
	},
	10,
	2
);

// Add machete to the Admin sidemenu.
add_action(
	'admin_menu',
	function() {
		global $machete;
		add_menu_page(
			'Machete',
			'Machete',
			'publish_posts', // targeting Author role.
			'machete',
			function() {
				global $machete;
				require MACHETE_BASE_PATH . 'inc/about/admin-content.php';
			},
			plugin_dir_url( __FILE__ ) . 'img/machete.svg',
			57
		);
	}
);

// Call to admin() method of all active modules.
foreach ( $machete->modules as $machete_module ) {
	if ( ! $machete_module->params['is_active'] ) {
		continue;
	}
	$machete_module->admin();
}
