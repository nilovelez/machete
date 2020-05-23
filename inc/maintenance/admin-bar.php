<?php
/**
 * Shows the maintenance status in the WordPress admin bar.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$machete_maintenance_settings = get_option( 'machete_maintenance_settings' );
if ( $machete_maintenance_settings ) {
	if ( ! empty( $machete_maintenance_settings['site_status'] ) &&
		( 'online' !== $machete_maintenance_settings['site_status'] ) ) {

		/**
		 * Enqueues Maintenance status admin bar styles
		 */
		function machete_admin_bar_scripts() {
			wp_register_style(
				'machete-maintenance-styles',
				MACHETE_BASE_URL . 'css/maintenance-admin-bar.css',
				array(),
				MACHETE_VERSION
			);
			wp_enqueue_style( 'machete-maintenance-styles' );
		}
		add_action( 'wp_enqueue_scripts', 'machete_admin_bar_scripts' );
		add_action( 'admin_enqueue_scripts', 'machete_admin_bar_scripts' );

		/**
		 * Admin bar code specific to the coming_soon status.
		 */
		function machete_coming_soon_admin_bar() {
			global $wp_admin_bar;

			if ( current_user_can( 'manage_options' ) ) {
				$href = admin_url( 'admin.php?page=machete-maintenance' );
			} else {
				$href = admin_url( 'index.php' );
			}

			// Adds the main admin menu item.
			$wp_admin_bar->add_menu(
				array(
					'id'     => 'machete-maintenance-notice',
					'href'   => $href,
					'parent' => 'top-secondary',
					'title'  => __( 'Coming Soon', 'machete' ),
					'meta'   => array( 'class' => 'machete-coming-soon-active' ),
				)
			);
		}
		/**
		 * Admin bar code specific to the maintenance status.
		 */
		function machete_maintenance_admin_bar() {
			global $wp_admin_bar;

			if ( current_user_can( 'manage_options' ) ) {
				$href = admin_url( 'admin.php?page=machete-maintenance' );
			} else {
				$href = admin_url( 'index.php' );
			}

			// Adds the main admin menu item.
			$wp_admin_bar->add_menu(
				array(
					'id'     => 'machete-maintenance-notice',
					'href'   => $href,
					'parent' => 'top-secondary',
					'title'  => __( 'Maintenance', 'machete' ),
					'meta'   => array( 'class' => 'machete-maintenance-active' ),
				)
			);
		}

		if ( 'maintenance' === $machete_maintenance_settings['site_status'] ) {
			add_action( 'admin_bar_menu', 'machete_maintenance_admin_bar', 1000 );
		}
		if ( 'coming_soon' === $machete_maintenance_settings['site_status'] ) {
			add_action( 'admin_bar_menu', 'machete_coming_soon_admin_bar', 1000 );
		}
	}
}
