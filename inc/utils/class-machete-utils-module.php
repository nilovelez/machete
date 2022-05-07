<?php
/**
 * Machete Analytics&Code Module class

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete Analytics&Code Module class
 */
class MACHETE_UTILS_MODULE extends MACHETE_MODULE {
	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug'        => 'utils',
				'title'       => __( 'Analytics & Code', 'machete' ),
				'full_title'  => __( 'Analytics and Custom Code', 'machete' ),
				'description' => __( 'Google Analytics tracking code manager and a simple editor to insert HTML, CSS and JS snippets or site verification tags.', 'machete' ),
			)
		);
		$this->default_settings = array(
			'tracking_id'                      => '',
			'tracking_ga4'                     => '',
			'tracking_gtm'                     => '',
			'tracking_format'                  => 'none',
			'tacking_anonymize'                => 0,
			'tracking_filename'                => '',
			'alfonso_content_injection_method' => 'manual',
		);
	}
	/**
	 * Reads the modules settings to the settings proerty,
	 * also returns them in an array.
	 *
	 * @return array module settings array
	 */
	protected function read_settings() {
		$this->settings = get_option(
			'machete_' . $this->params['slug'] . '_settings',
			$this->default_settings
		);
		// Overwrites old pagespeed optimized code.
		if ( 'machete' === $this->settings['tracking_format'] ) {
			$this->settings['tracking_format'] = 'standard';
		}
		$this->settings = array_merge( $this->default_settings, $this->settings );
		return $this->settings;
	}
	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {
		$this->read_settings();
		add_action(
			'admin_init',
			function() {
				if ( null !== filter_input( INPUT_POST, 'machete-utils-saved' ) ) {
					check_admin_referer( 'machete_save_utils' );
					$this->save_settings( filter_input_array( INPUT_POST ) );
				}
			}
		);
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}
	/**
	 * Function model to serve options to database
	 * real function must be defined in each module
	 *
	 * @param array $options options array, normally $_POST.
	 * * tracking id.
	 * * tracking_format: standard, machete, none.
	 * * header_content.
	 * * alfonso_content
	 * * footer_content.
	 * @param bool  $silent  prevent the function from generating admin notices.
	 */
	protected function save_settings( $options = array(), $silent = false ) {

		$settings           = $this->read_settings();
		$tracking_script_js = '';

		if ( ! is_dir( MACHETE_DATA_PATH ) ) {
			if ( ! wp_mkdir_p( MACHETE_DATA_PATH ) ) {
				if ( ! $silent ) {
					// translators: %s path of data dir.
					$this->notice( sprintf( __( 'Error creating data dir %s please check file permissions', 'machete' ), MACHETE_RELATIVE_DATA_PATH ), 'error' );
				}
				return false;
			}
		}

		if ( ! in_array( $options['tracking_format'], array( 'standard', 'machete', 'none' ), true ) ) {
			if ( ! $silent ) {
				$this->notice( __( 'Something went wrong. Unknown tracking code format requested.', 'machete' ), 'warning' );
			}
			return false;
		}
		$settings['tracking_format'] = $options['tracking_format'];

		if ( isset( $options['tacking_anonymize'] ) ) {
			$settings['tacking_anonymize'] = 1;
		} else {
			$settings['tacking_anonymize'] = 0;
		}

		/* Start GTAG (universal &y GA4) */
		if ( ! empty( $options['tracking_ga4'] ) || ! empty( $options['tracking_id'] ) ) {

			/* Start Universal Analytics */
			if ( ! empty( $options['tracking_id'] ) ) {

				if ( ! preg_match( '/^(ua-\d{4,11}-\d{1,4})$/i', strval( $options['tracking_id'] ) ) ) {
					/*
					Invalid Tracking ID. Accepted format: UA-1234567-12
					*/
					if ( ! $silent ) {
						$this->notice( __( 'That doesn\'t look like a valid Universal Analytics Property ID', 'machete' ), 'warning' );
					}
					return false;
				}
				$settings['tracking_id'] = $options['tracking_id'];

				// let's generate the tracking code.
				if ( 'none' !== $settings['tracking_format'] ) {

					$js_replaces         = array(
						'{{tracking_id}}' => $options['tracking_id'],
					);
					$tracking_id_js      = str_replace(
						array_keys( $js_replaces ),
						array_values( $js_replaces ),
						$this->get_contents( $this->path . 'templates/gtag.tpl.html' )
					);
					$tracking_script_js .= $tracking_id_js . "\n";
					$tracking_script_js .= 'gtag("config", "' . $options['tracking_id'] . '"';
					if ( 1 === $settings['tacking_anonymize'] ) {
						$tracking_script_js .= ', { \'anonymize_ip\': true }';
					}
					$tracking_script_js .= ');' . "\n";
				}
			} else {
				$settings['tracking_id'] = '';
			}
			/* End Universal Analytics */

			/* Start Google Analytics 4 */
			if ( ! empty( $options['tracking_ga4'] ) ) {

				if ( ! preg_match( '/^(g-[a-z0-9]{4,11})$/i', strval( $options['tracking_ga4'] ) ) ) {
					/*
					Invalid Tracking ID. Accepted format: G-1234ABCD
					*/
					if ( ! $silent ) {
						$this->notice( __( 'That doesn\'t look like a valid Google Analytics 4 Property ID', 'machete' ), 'warning' );
					}
					return false;
				}
				$settings['tracking_ga4'] = $options['tracking_ga4'];

				// let's generate the tracking code.
				if ( 'none' !== $settings['tracking_format'] ) {

					// universal and GA4 share the same script.
					if ( '' === $tracking_script_js ) {
						$js_replaces         = array(
							'{{tracking_id}}' => $options['tracking_ga4'],
						);
						$tracking_gtag_js    = str_replace(
							array_keys( $js_replaces ),
							array_values( $js_replaces ),
							$this->get_contents( $this->path . 'templates/gtag.tpl.html' )
						);
						$tracking_script_js .= $tracking_gtag_js . "\n";
					}
					$tracking_script_js .= 'gtag("config", "' . $options['tracking_ga4'] . '");' . "\n";

				}
			} else {
				$settings['tracking_ga4'] = '';
			}
			/* End Google Analytics 4 */

		}
		/* Start GTAG (universal &y GA4) */

		/* Start Google Tag Manager */
		if ( ! empty( $options['tracking_gtm'] ) ) {

			if ( ! preg_match( '/^(gtm-[a-z0-9]{4,11})$/i', strval( $options['tracking_gtm'] ) ) ) {
				/*
				Invalid Tracking ID. Accepted format: GTM-1234ABCD
				*/
				if ( ! $silent ) {
					$this->notice( __( 'That doesn\'t look like a valid Google Tag Manager container ID', 'machete' ), 'warning' );
				}
				return false;
			}
			$settings['tracking_gtm'] = $options['tracking_gtm'];

			// let's generate the tracking code.
			if ( 'none' !== $settings['tracking_format'] ) {

				$js_replaces         = array(
					'{{tracking_id}}' => $options['tracking_gtm'],
				);
				$tracking_gtm_js     = str_replace(
					array_keys( $js_replaces ),
					array_values( $js_replaces ),
					$this->get_contents( $this->path . 'templates/gtm.tpl.html' )
				);
				$tracking_script_js .= $tracking_gtm_js . "\n";
			}
		} else {
			$settings['tracking_gtm'] = '';
		}
		/* End Google Tag Manager */

		if ( '' !== $tracking_script_js ) {
			// cheap and dirty pseudo-random filename generation.
			$settings['tracking_filename'] = 'tracking_mct4_' . strtolower( substr( MD5( time() ), 0, 8 ) ) . '.js';

			if ( ! $this->put_contents( MACHETE_DATA_PATH . $settings['tracking_filename'], $tracking_script_js ) ) {
				if ( ! $silent ) {
					// translators: %s path to machete data dir.
					$this->notice( sprintf( __( 'Error writing static javascript file to %s please check file permissions. Aborting to prevent inconsistent state.', 'machete' ), MACHETE_RELATIVE_DATA_PATH ), 'error' );
				}
				return false;
			}
		} else {
			$settings['tracking_format'] = 'none';
		}

		// delete old .js file and generate a new one to prevent caching.
		if ( ! empty( $this->settings['tracking_filename'] ) && file_exists( MACHETE_DATA_PATH . $this->settings['tracking_filename'] ) ) {
			if ( ! $this->delete( MACHETE_DATA_PATH . $this->settings['tracking_filename'] ) ) {
				if ( ! $silent ) {
					// translators: %s path to machete data dir.
					$this->notice( sprintf( __( 'Could not delete old javascript file from %s please check file permissions. Aborting to prevent inconsistent state.', 'machete' ), MACHETE_RELATIVE_DATA_PATH ), 'warning' );
				}
				return false;
			}
		}

		if ( ! empty( $options['header_content'] ) ) {
			$header_content = stripslashes( wptexturize( $options['header_content'] ) );
			$this->put_contents( MACHETE_DATA_PATH . 'header.html', $header_content, LOCK_EX );
		} else {
			$this->delete( MACHETE_DATA_PATH . 'header.html' );
		}

		if (
			isset( $options['alfonso_content_injection_method'] ) &&
			in_array( $options['alfonso_content_injection_method'], array( 'auto', 'manual', 'wp_body_open' ), true )
		) {
			$settings['alfonso_content_injection_method'] = $options['alfonso_content_injection_method'];
		} else {
			$settings['alfonso_content_injection_method'] = $this->default_settings['alfonso_content_injection_method'];
		}

		if ( ! empty( $options['alfonso_content'] ) ) {
			$alfonso_content = stripslashes( wptexturize( $options['alfonso_content'] ) );
			$this->put_contents( MACHETE_DATA_PATH . 'body.html', $alfonso_content, LOCK_EX );
		} else {
			$this->delete( MACHETE_DATA_PATH . 'body.html' );
		}

		if ( ! empty( $options['footer_content'] ) ) {
			$footer_content = stripslashes( wptexturize( $options['footer_content'] ) );
			$this->put_contents( MACHETE_DATA_PATH . 'footer.html', $footer_content, LOCK_EX );
		} else {
			$this->delete( MACHETE_DATA_PATH . 'footer.html' );
		}
		if ( $this->is_equal_array( $this->settings, $settings ) ) {
			// no removes && no adds
			// ToDo: check for changes in the other sections give "no changes" notice only if no changes at all
			// if (!$silent) $this->save_no_changes_notice(); .
			return true;
		}

		// option saved WITHOUT autoload.
		if ( update_option( 'machete_utils_settings', $settings, 'no' ) ) {
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
	 * Called from frontend-functions.php via add_action .
	 */
	public function read_header_html() {
		return $this->readfile( MACHETE_DATA_PATH . 'header.html' );
	}
	/**
	 * Called from frontend-functions.php via add_action .
	 */
	public function enqueue_tracking_if_no_cookies() {
		global $machete;
		if ( true === $machete->modules['cookies']->params['is_active'] ) {
			$machete_cookie_settings = $machete->modules['cookies']->read_settings();
			if ( 'enabled' === $machete_cookie_settings['bar_status'] ) {
				add_action(
					'wp_enqueue_scripts',
					array( $this, 'enqueue_tracking_waiting_cookies' )
				);
				return false;
			}
		}
		add_action(
			'wp_head',
			function() {
				echo '<script>' . "\n";
				$this->readfile( MACHETE_DATA_PATH . $this->settings['tracking_filename'] );
				echo '</script>' . "\n";
			},
			10002
		);
	}
	/**
	 * Called from enqueue_tracking_if_no_cookies() if cookies are active
	 */
	public function enqueue_tracking_waiting_cookies() {
		wp_enqueue_script(
			'machete-load-tracking',
			$this->baseurl . 'js/gdpr_load_tracking.min.js',
			array(),
			MACHETE_VERSION,
			false
		);
		wp_add_inline_script(
			'machete-load-tracking',
			'var machete_tracking_script_url = "' . MACHETE_DATA_URL . $this->settings['tracking_filename'] . '";',
			'before'
		);
	}
	/**
	 * Called from frontend-functions.php via add_action .
	 */
	public function read_body_html() {
		return $this->readfile( MACHETE_DATA_PATH . 'body.html' );
	}
	/**
	 * Called from frontend-functions.php via add_filter
	 *
	 * @param array $classes <body> tag classes.
	 */
	public function inject_body_html( $classes ) {
		$alfonso_content = $this->get_contents( MACHETE_DATA_PATH . 'body.html' );
		if ( $alfonso_content ) {
			$classes[] = '">' . $alfonso_content . '<br style="display: none';
		}
		return $classes;
	}
	/**
	 * Called from frontend-functions.php via add_action .
	 */
	public function read_footer_html() {
		return $this->readfile( MACHETE_DATA_PATH . 'footer.html' );
	}

	/**
	 * Restores module settings from a backup
	 *
	 * @param array $settings modules settings array.
	 * @return string success/error message.
	 */
	protected function import( $settings = array() ) {

		$encoded_fields = array( 'header_content', 'alfonso_content', 'footer_content' );

		foreach ( $encoded_fields as $encoded_field ) {
			if ( array_key_exists( $encoded_field, $settings ) && ! empty( $settings[ $encoded_field ] ) ) {
				// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
				$settings[ $encoded_field ] = base64_decode( $settings[ $encoded_field ] );
			}
		}

		if ( $this->save_settings( $settings, true ) ) {
			return __( 'Settings successfully restored from backup', 'machete' ) . "\n";
		} else {
			return __( 'Error restoring settings backup', 'machete' ) . "\n";
		}
	}
	/**
	 * Returns a module settings array to use for backups.
	 *
	 * @return array modules settings array.
	 */
	protected function export() {

		$export = $this->settings;

		$machete_header_content = $this->get_contents( MACHETE_DATA_PATH . 'header.html' );
		if ( ! empty( $machete_header_content ) ) {

			$machete_header_content = explode( '<!-- Machete Header -->', $machete_header_content );
			switch ( count( $machete_header_content ) ) {
				case 1:
					$machete_header_content = $machete_header_content[0];
					break;
				case 2:
					$machete_header_content = $machete_header_content[1];
					break;
				default:
					$machete_header_content = implode( '', array_slice( $machete_header_content, 1 ) );
			}
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			$export['header_content'] = base64_encode( $machete_header_content );
		}
		if ( file_exists( MACHETE_DATA_PATH . 'body.html' ) ) {
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			$export['alfonso_content'] = base64_encode( $this->get_contents( MACHETE_DATA_PATH . 'body.html' ) );
		}

		if ( file_exists( MACHETE_DATA_PATH . 'footer.html' ) ) {
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			$export['footer_content'] = base64_encode( $this->get_contents( MACHETE_DATA_PATH . 'footer.html' ) );
		}

		return $export;
	}

}
$machete->modules['utils'] = new MACHETE_UTILS_MODULE();
