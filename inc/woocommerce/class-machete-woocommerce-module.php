<?php
/**
 * Machete WooCommerce Module class
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete WooCommerce Module class
 */
class MACHETE_WOOCOMMERCE_MODULE extends MACHETE_MODULE {
	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug'        => 'woocommerce',
				'title'       => __( 'WooCommerce', 'machete' ),
				'full_title'  => __( 'WooCommerce Utils', 'machete' ),
				'description' => __( 'Utilities and fixes that make your live a little easier when working with WooCommerce', 'machete' ),
				'role'        => 'publish_posts', // targeting Author role.
			)
		);
		$this->tweaks_array = array(
			'json_api'       => array(
				'title'       => __( 'JSON API', 'machete' ),
				'description' => __( 'Remove the JSON-API links from page headers. Also require that API consumers be authenticated.', 'machete' ) . ' <br><span style="color: #d94f4f">' . __( 'Be careful. Breaks the block editor and many plugins that use the REST API.', 'machete' ) . '</span>',
			),
		);

		$this->default_settings = array(
			'bar_status' => 'disabled',
		);

	}
	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {
		$this->read_settings();
		add_action(
			'admin_init',
			function() {
				if ( filter_input( INPUT_POST, 'machete-woo-saved' ) !== null ) {
					check_admin_referer( 'machete_save_woo' );
					$this->save_settings( filter_input_array( INPUT_POST ) );
				}
			}
		);
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}
	/**
	 * Executes code related to the front-end.
	 *
	 * @todo Hook render_cookie_bar function only if bar is active.
	 */
	public function frontend() {
		$this->read_settings();
		// add_action( 'wp_footer', array( $this, 'render_cookie_bar' ) );
	}

	/**
	 * Saves options to database
	 *
	 * @param array $options options array, normally $_POST.
	 * @param bool  $silent  prevent the function from generating admin notices.
	 */
	protected function save_settings( $options = array(), $silent = false ) {

		// $options : values from _POST or import script
		// $settings : values to save
		$settings = $this->read_settings();

		// Option saved WITH autoload.
		if ( update_option( 'machete_woo_settings', $settings, 'yes' ) ) {
			$this->settings = $settings;
			if ( ! $silent ) {
				$this->save_success_notice();
			}
			return true;
		} else {
			if ( ! $silent ) {
				$this->save_error_notice();
			}
			return false;
		}
	}

	/**
	 * Returns a module settings array to use for backups.
	 *
	 * @return array modules settings array.
	 */
	protected function export() {
		$export = $this->read_settings();
		if ( ! empty( $export['warning_text'] ) ) {
			$export['warning_text'] = stripslashes( $export['warning_text'] );
		}
		return $export;
	}
}
$machete->modules['woocommerce'] = new MACHETE_WOOCOMMERCE_MODULE();
