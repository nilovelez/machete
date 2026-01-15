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

	public $woo_array;
	public $all_woo_checked;

	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug'        => 'woocommerce',
				'role'        => 'publish_posts', // targeting Author role.
				'is_active'   => true,
			)
		);
		$this->woo_array = array(
			'free_shipping'  => array(),
			'price_from'     => array(),
			'trailing_zeros' => array(),
			'no_unique_sku'  => array(),
			'disable_skus'   => array(),
		);

		$this->default_settings = array();
	}
	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {

		require $this->path . 'i18n.php';

		$this->read_settings();

		if ( filter_input( INPUT_POST, 'machete-woo-saved' ) !== null ) {
			check_admin_referer( 'machete_save_woo' );
			$this->save_settings(
				filter_input( INPUT_POST, 'optionEnabled', FILTER_DEFAULT, FILTER_FORCE_ARRAY )
			);
			$this->read_settings();
		}

		if ( count( $this->settings ) > 0 ) {
			require $this->path . 'actions.php';
		}

		$this->all_woo_checked = ( count( array_intersect( array_keys( $this->woo_array ), $this->settings ) ) === count( $this->woo_array ) );

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
}
$machete->modules['woocommerce'] = new MACHETE_WOOCOMMERCE_MODULE();
