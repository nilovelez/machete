<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MACHETE_MAINTENANCE_MODULE extends MACHETE_MODULE {

	function __construct() {
		$this->init( array(
			'slug'        => 'maintenance',
			'title'       => __( 'Maintenance Mode', 'machete' ),
			'full_title'  => __( 'Maintenance Mode', 'machete' ),
			'description' => __( 'Customizable maintenance page to close your site during updates or development. It has a "magic link" to grant temporary access.', 'machete' ),
			'role'        => 'publish_posts', // targeting Author role.
		));
		$this->default_settings = array(
			'page_id'     => '',
			'site_status' => 'online',
			'token'       => strtoupper( substr( MD5( rand() ), 0, 12 ) ),
		);
	}

	public function frontend() {
		$this->read_settings();
		if ( count( $this->settings ) > 0 ) {
			require $this->path . 'frontend-functions.php';
			if ( is_admin_bar_showing() ) {
				require_once $this->path . 'admin-bar.php';
			}
			$machete_maintenance = new machete_maintenance_page( $this->settings );
		}
	}

	public function admin() {

		$this->read_settings();
		// the maintenance token should be saved as soon as possible
		//to keep it from changing on every page load
		if ( ! get_option( 'machete_' . $this->params['slug'] . '_settings' ) ) {
			// default option values saved WITHOUT autoload
			update_option( 'machete_' . $this->params['slug'] . '_settings', $this->default_settings, 'no' );
		}

		if ( isset( $_POST['machete-maintenance-saved'] ) ) {
			check_admin_referer( 'machete_save_maintenance' );
			$this->save_settings( $_POST );
		}

		$this->preview_base_url = home_url( '/?mct_preview=' . wp_create_nonce( 'maintenance_preview_nonce' ) );

		if ( $this->settings['page_id'] ) {
			$this->preview_url = $this->preview_base_url . '&mct_page_id=' . $this->settings['page_id'];
		} else {
			$this->preview_url = $this->preview_base_url;
		}

		$this->magic_base_url = home_url( '/?mct_token=' );
		$this->magic_url      = home_url( '/?mct_token=' . $this->settings['token'] );

		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );

		if ( is_admin_bar_showing() ) {
			require_once $this->path . 'admin-bar.php';
		}
	}

	protected function save_settings( $options = array(), $silent = false ) {

		/*
		page_id: int
		site_status: online | coming_soon | maintenance
		token: string
		*/
		$settings = $this->read_settings();

		if ( ! empty( $options['site_status'] ) && ( in_array( $options['site_status'], array( 'online', 'coming_soon', 'maintenance' ), true ) ) ) {
			$settings['site_status'] = $options ['site_status'];
		}

		if ( ! empty( $options['token'] ) ) {
			$settings['token'] = sanitize_text_field( $options['token'] );
		}

		if ( ! empty( $options['page_id'] ) ) {
			$settings['page_id'] = (int) sanitize_text_field( $options['page_id'] );
			if ( empty( $options['page_id'] ) ) {
				if ( ! $silent ) {
					$this->notice( __( 'Content page id is not a valid page id', 'machete' ), 'warning' );
				}
				return false;
			}
		}

		if ( $this->is_equal_array( $this->settings, $settings ) ) {
			if ( ! $silent ) {
				$this->save_no_changes_notice();
			}
			return true;
		}

		// option saved WITH autoload
		if ( update_option( 'machete_maintenance_settings', $settings, 'yes' ) ) {
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

}
$machete->modules['maintenance'] = new MACHETE_MAINTENANCE_MODULE();
