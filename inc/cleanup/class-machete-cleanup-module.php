<?php
/**
 * Machete Cleanup Module class
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete Cleanup Module class
 */
class MACHETE_CLEANUP_MODULE extends MACHETE_MODULE {

	public $cleanup_array;
	public $optimize_array;
	public $tweaks_array;

	public $all_cleanup_checked;
	public $all_optimize_checked;
	public $all_tweaks_checked;

	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug'            => 'cleanup',
				'is_active'       => true,
				'has_config'      => true,
				'can_be_disabled' => true,
				'role'            => 'manage_options',
			)
		);

		$this->cleanup_array = array(
			'rsd_link'          => array(),
			'wlwmanifest'       => array(),
			'feed_links'        => array(),
			'feed_generator'    => array(),
			'next_prev'         => array(),
			'shortlink'         => array(),
			'wp_generator'      => array(),
			'ver'               => array(),
			'wp_resource_hints' => array(),
		);

		$this->optimize_array = array(
			'emojicons'              => array(),
			'pdf_thumbnails'         => array(),
			'limit_revisions'        => array(),
			'slow_heartbeat'         => array(),
			'comments_reply_feature' => array(),
			'empty_trash_soon'       => array(),
			'capital_P_dangit'       => array(),
			'medium_large_size'      => array(),
			'1536x1536_size'         => array(),
			'2048x2048_size'         => array(),
			'comment_autolinks'      => array(),
			'disable_login_langs'    => array(),
			'disable_editor'         => array(),
		);

		$this->tweaks_array = array(
			'json_api'           => array(),
			'xmlrpc'             => array(),
			'jquery-migrate'     => array(),
			'oembed_scripts'     => array(),
			'jpeg_quality'       => array(),
			'big_image_scaling'  => array(),
			'gutenberg_css'      => array(),
			'disable_global_css' => array(),
		);
	}
	/**
	 * Executes code related to the front-end.
	 * Loads optimization code if there is any option active.
	 */
	public function frontend() {
		$this->read_settings();
		if ( count( $this->settings ) > 0 ) {
			require $this->path . 'optimization.php';
		}
	}
	/**
	 * Executes code related to the WordPress admin.
	 * Loads optimization code if there is any option active.
	 */
	public function admin() {

		require $this->path . 'i18n.php';

		$this->read_settings();

		if ( filter_input( INPUT_POST, 'machete-cleanup-saved' ) !== null ) {
			check_admin_referer( 'machete_save_cleanup' );
			$this->save_settings(
				filter_input( INPUT_POST, 'optionEnabled', FILTER_DEFAULT, FILTER_FORCE_ARRAY )
			);
		}

		if ( count( $this->settings ) > 0 ) {
			require $this->path . 'optimization.php';
		}

		$this->all_cleanup_checked = ( count( array_intersect( array_keys( $this->cleanup_array ), $this->settings ) ) === count( $this->cleanup_array ) );

		$this->all_optimize_checked = ( count( array_intersect( array_keys( $this->optimize_array ), $this->settings ) ) === count( $this->optimize_array ) );

		$this->all_tweaks_checked = ( count( array_intersect( array_keys( $this->tweaks_array ), $this->settings ) ) === count( $this->tweaks_array ) );

		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
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

		$this->read_settings();
		$valid_options = array_merge(
			array_keys( $this->cleanup_array ),
			array_keys( $this->optimize_array ),
			array_keys( $this->tweaks_array )
		);
		$options       = array_intersect( $options, $valid_options );

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

			if ( update_option( 'machete_cleanup_settings', $options ) ) {
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
			if ( delete_option( 'machete_cleanup_settings' ) ) {
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
$machete->modules['cleanup'] = new MACHETE_CLEANUP_MODULE();
