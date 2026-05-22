<?php
/**
 * Machete code only usable in the WordPress admin

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Manages welcome redirect to About page.
add_action(
	'admin_init',
	function () {
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
	function () {
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
				( 'none' !== $tracking_options['tracking_format'] ) &&
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
	function () {
		if ( false === strpos( get_current_screen()->id, 'machete' ) ) {
			return;
		}

		// Machete pages footer credits.
		add_filter(
			'admin_footer_text',
			function () {
				/* translators: %s: five stars */
				return ' ' . sprintf( __( 'If you like <strong>Machete</strong>, please help us %1$sleaving a 5&starf; rating%2$s. Thank you!', 'machete' ), '<a href="https://wordpress.org/support/plugin/machete/reviews/#new-post" target="_blank">', '</a>' ) . ' ';
			}
		);

		// Enqueue admin styles.
		add_action(
			'admin_enqueue_scripts',
			function () {
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
	function ( $plugin_actions, $plugin_file ) {
		$new_actions = array();
		if ( basename( __DIR__ ) . '/machete.php' === $plugin_file ) {
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
	function () {
		global $machete;
		// Base64-encoded data URI of img/machete.svg (required by WordPress to recolor the admin menu icon).
		$machete_menu_icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgdmlld0JveD0iMTMuMDMgOCAxNiAxNiI+DQoJPHBhdGggZmlsbD0iYmxhY2siIGQ9Ik0xOS4yMDksMTYuMzY0Yy0wLjAzNC0wLjAxMi0wLjA3LTAuMDE0LTAuMTAyLTAuMDI4YzIuNzU4LTIuNDI5LDkuNTM5LTguMzIxLDkuNTU3LTguMzMzYzAuMTA4LTAuMDkxLDAuMzQzLDEuMzcyLDAuMzU3LDEuNTZjMC4xNzYsMy4yMjEtMi4yODEsNS4wNy00LjczNCw2LjU5Yy0yLjQ0LDEuNTEyLTIuMDAyLDAuOTU1LTMuMzM1LDIuMTIzQzIwLjYxOSwxNy40MjgsMTkuODk3LDE2LjYzOSwxOS4yMDksMTYuMzY0eiIvPg0KCTxwYXRoIGZpbGw9ImJsYWNrIiBkPSJNMTMuNzQyLDIwLjkwNGMwLjc1NC0wLjY3OCw0LjY2NS00LjA2Myw0LjY2NS00LjA2M2MwLjA1Ny0wLjA2NywwLjE0Ni0wLjA5NSwwLjI0NS0wLjExM2MwLjE0NiwwLjAxMSwwLjI4MywwLjAwOCwwLjQ1MSwwLjA3OGMwLjYxMiwwLjI0MiwxLjI4OCwwLjk5NSwxLjQyMSwxLjg3NWMwLjAwMiwwLjA1NiwwLjAxNSwwLjEwNSwwLjAxMiwwLjE2OGMtMC4xMTksMC44NjMtMC45NTIsMC4yMTMtMC45NTIsMC4yMTNzLTAuNDA4LTAuMjA4LTAuOTM4LDAuNjMzYy0wLjUzOCwwLjg0LTEuMzIxLDEuNDA0LTIuMTczLDEuODEzYzAsMC0wLjUwMiwwLjI3NC0wLjY2LDAuNDE3Yy0wLjE2MSwwLjE0My0wLjMyMSwwLjMyMy0wLjI3NCwwLjkzMmMwLjA0MiwwLjYwNy0wLjEyNywwLjkzOC0wLjQzNywxLjExMWMtMC4zMTIsMC4xNzItMS40MjQtMC4zODQtMS44NDEtMS4wNDhDMTIuODQyLDIyLjI1MSwxMi45OSwyMS41ODMsMTMuNzQyLDIwLjkwNHogTTE4LjA0OCwxOC4wMzhjLTAuMDEyLDAuMzMyLDAuMjQzLDAuNjE4LDAuNTc1LDAuNjM1YzAuMzMyLDAuMDE4LDAuNjEzLTAuMjQsMC42MzUtMC41NzJjMC4wMTgtMC4zMzQtMC4yNC0wLjYxNy0wLjU3Mi0wLjYzNUMxOC4zNTMsMTcuNDQ4LDE4LjA2NywxNy43MDMsMTguMDQ4LDE4LjAzOHogTTE2LjAyOSwxOS44NTljLTAuMDEyLDAuMzMyLDAuMjQzLDAuNjE5LDAuNTc2LDAuNjM2YzAuMzMxLDAuMDE4LDAuNjEyLTAuMjM3LDAuNjM0LTAuNTczYzAuMDE4LTAuMzM1LTAuMjQxLTAuNjE4LTAuNTcyLTAuNjM1QzE2LjMzMywxOS4yNywxNi4wNDYsMTkuNTI0LDE2LjAyOSwxOS44NTl6IE0xMy43NTEsMjEuOTFjLTAuMDIsMC4zNDEsMC4yNCwwLjYyNSwwLjU3LDAuNjQyYzAuMzM4LDAuMDE4LDAuNjI1LTAuMjM4LDAuNjQtMC41NzZjMC4wMjEtMC4zMzgtMC4yMzgtMC42MjItMC41NzEtMC42NEMxNC4wNTMsMjEuMzIsMTMuNzY2LDIxLjU3NSwxMy43NTEsMjEuOTF6Ii8+DQo8L3N2Zz4NCg==';
		add_menu_page(
			'Machete',
			'Machete',
			'publish_posts', // targeting Author role.
			'machete',
			function () {
				global $machete;
				require MACHETE_BASE_PATH . 'inc/about/admin-content.php';
			},
			$machete_menu_icon,
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
