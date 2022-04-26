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
				'description' => __( 'Utilities and fixes that make your life a little easier when working with WooCommerce.', 'machete' ),
				'role'        => 'publish_posts', // targeting Author role.
				'is_active'   => true,
			)
		);
		$this->woo_array = array(
			'free_shipping'  => array(
				'title'       => __( 'Fix free shipping', 'machete' ),
				'description' => __( 'Hides all paid shipping methods from checkout when free shipping is available. Keeps "Free shipping" and "local Pickup".', 'machete' ),
			),
			'price_from'     => array(
				'title'       => __( 'Variable price from', 'machete' ),
				'description' => __( 'Replaces the price interval on variable products with a "Price from" label.', 'machete' ),
			),
			'trailing_zeros' => array(
				'title'       => __( 'Hide trailing zeros', 'machete' ),
				'description' => __( 'Hides trailing zeros on prices. Shows $5.00 as $5', 'machete' ),
			),
		);

		$this->default_settings = array();

	}
	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {
		add_action(
			'admin_init',
			function() {
				$this->read_settings();
				if ( filter_input( INPUT_POST, 'machete-woo-saved' ) !== null ) {
					check_admin_referer( 'machete_save_woo' );
					$this->save_settings(
						filter_input( INPUT_POST, 'optionEnabled', FILTER_DEFAULT, FILTER_FORCE_ARRAY )
					);
					$this->read_settings();
				}

				$this->all_woo_checked = ( count( array_intersect( array_keys( $this->woo_array ), $this->settings ) ) === count( $this->woo_array ) );
			}
		);
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}
	/**
	 * Executes code related to the front-end.
	 * Loads woocommerce actions if there is any option active.
	 */
	public function frontend() {
		$this->read_settings();
		if ( count( $this->settings ) > 0 ) {
			require $this->path . 'actions.php';
		}
	}

	/**
	 * Saves options to database
	 *
	 * @param array $options options array, normally $_POST.
	 * @param bool  $silent  prevent the function from generating admin notices.
	 */
	protected function save_settings( $options = array(), $silent = false ) {
		if ( null === $options ) {
			$options = array();
		}

		$options = array_intersect( $options, array_keys( $this->woo_array ) );

		$this->read_settings();

		if ( count( $options ) > 0 ) {
			$num_options = count( $options );
			for ( $i = 0; $i < $num_options; $i++ ) {
				$options[ $i ] = sanitize_text_field( $options[ $i ] );
			}
			if ( $this->is_equal_array( $this->settings, $options ) ) {
				if ( ! $silent ) {
					$this->save_no_changes_notice();
				}
				return true;
			}

			if ( update_option( 'machete_woocommerce_settings', $options ) ) {
				$this->settings = $options;
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
		} elseif ( count( $this->settings ) > 0 ) {
			if ( delete_option( 'machete_woocommerce_settings' ) ) {
				$this->settings = array();
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

		if ( ! $silent ) {
			$this->save_no_changes_notice();
		}
		return true;
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
