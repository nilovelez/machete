<?php
/**
 * Machete Cookies Module class
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete Cookies Module class
 */
class MACHETE_COOKIES_MODULE extends MACHETE_MODULE {
	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug'        => 'cookies',
				'title'       => __( 'Cookie Law', 'machete' ),
				'full_title'  => __( 'Cookie Law Warning', 'machete' ),
				'description' => __( 'Light and responsive cookie law warning bar that won\'t affect your PageSpeed score and plays well with static cache plugins.', 'machete' ),
				'role'        => 'publish_posts', // targeting Author role.
			)
		);
		$this->default_settings = array(
			'bar_status'      => 'disabled',
			'warning_text'    => __( 'By continuing to browse the site, you are agreeing to our use of cookies as described in our <a href="/cookies/" style="color: #007FFF">cookie policy</a>.', 'machete' ),
			'accept_text'     => __( 'Accept cookies', 'machete' ),
			'bar_theme'       => 'light',
			'cookie_filename' => '',
		);

		$this->themes = array(
			'light'     => array(
				'name'      => __( 'Classic Light', 'machete' ),
				'template'  => $this->path . 'templates/light.html',
				'extra_css' => '',
			),
			'dark'      => array(
				'name'      => __( 'Classic Dark', 'machete' ),
				'template'  => $this->path . 'templates/dark.html',
				'extra_css' => '',
			),
			'new_light' => array(
				'name'      => __( 'Modern Light', 'machete' ),
				'template'  => $this->path . 'templates/new_light.html',
				'extra_css' => '',
			),
			'new_dark'  => array(
				'name'      => __( 'Modern Dark', 'machete' ),
				'template'  => $this->path . 'templates/new_dark.html',
				'extra_css' => '',
			),
			'cookie'    => array(
				'name'      => __( 'Cookie!', 'machete' ),
				'template'  => $this->path . 'templates/cookie.html',
				'extra_css' => '#machete_cookie_bar {background-image: url(' . MACHETE_BASE_URL . 'img/cookie_monster.svg);}',
			),
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
				if ( filter_input( INPUT_POST, 'machete-cookies-saved' ) !== null ) {
					check_admin_referer( 'machete_save_cookies' );
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
		add_action( 'wp_footer', array( $this, 'render_cookie_bar' ) );
	}

	/**
	 * Saves options to database
	 *
	 * @param array $options options array, normally $_POST.
	 * @param bool  $silent prevent the function from generating admin notices.
	 */
	protected function save_settings( $options = array(), $silent = false ) {

		// $options : values from _POST or import script
		// $settings : values to save
		$settings      = $this->read_settings();
		$html_replaces = array();

		if ( ! is_dir( MACHETE_DATA_PATH ) ) {
			if ( ! wp_mkdir_p( MACHETE_DATA_PATH ) ) {
				if ( ! $silent ) {
					// translators: %s path of data dir.
					$this->notice( sprintf( __( 'Error creating data dir %s please check file permissions', 'machete' ), MACHETE_RELATIVE_DATA_PATH ), 'error' );
				}
				return false;
			}
		}

		$cookies_bar_js = $this->get_contents( $this->path . 'templates/cookies_bar_js.min.js' );
		if ( false === $cookies_bar_js ) {
			if ( ! $silent ) {
				// translators: %s path to template file.
				$this->notice( sprintf( __( 'Error reading cookie bar template %s', 'machete' ), $this->path . 'templates/cookies_bar_js.js' ), 'error' );
			}
			return false;
		}

		if ( ! empty( $options['bar_theme'] ) && ( array_key_exists( $options['bar_theme'], $this->themes ) ) ) {
			$settings['bar_theme'] = $options['bar_theme'];
		}

		$cookies_bar_html = $this->get_contents( $this->themes[ $options['bar_theme'] ]['template'] );
		if ( false === $cookies_bar_html ) {
			if ( ! $silent ) {
				// translators: %s path to template file.
				$this->notice( sprintf( __( 'Error reading cookie bar template %s', 'machete' ), $cookies_bar_themes[ $options['bar_theme'] ]['template'] ), 'error' );
			}
			return false;
		}
		$cookies_bar_html = "var machete_cookies_bar_html = '" . addslashes( $cookies_bar_html ) . "'; \n";

		if ( empty( $options['bar_status'] ) || ( 'disabled' === $options['bar_status'] ) ) {
			$settings['bar_status'] = 'disabled';
		} else {
			$settings['bar_status'] = 'enabled';
		}

		$options['warning_text'] = wp_kses_post( force_balance_tags( $options['warning_text'] ) );
		if ( empty( $options['warning_text'] ) ) {
			if ( ! $silent ) {
				$this->notice( __( 'Cookie warning text can\'t be blank', 'machete' ), 'warning' );
			}
			return false;
		}

		$html_replaces['{{warning_text}}'] = $options['warning_text'];
		$settings['warning_text']          = $options['warning_text'];

		$options['accept_text'] = sanitize_text_field( $options['accept_text'] );
		if ( empty( $options['accept_text'] ) ) {
			if ( ! $silent ) {
				$this->notice( __( 'Accept button text can\'t be blank', 'machete' ), 'warning' );
			}
			return false;
		}
		$html_replaces['{{accept_text}}'] = $options['accept_text'];
		$settings['accept_text']          = $options['accept_text'];

		$html_replaces['{{extra_css}}'] = $this->themes[ $options['bar_theme'] ]['extra_css'];

		$cookies_bar_js = str_replace(
			array_keys( $html_replaces ),
			array_values( $html_replaces ),
			$cookies_bar_html
		) . "\n" . $cookies_bar_js;

		// cheap and dirty pseudo-random filename generation.
		$settings['cookie_filename'] = 'cookies_' . strtolower( substr( MD5( time() ), 0, 8 ) ) . '.js';

		if ( 'enabled' === $settings['bar_status'] ) {
			if ( ! $this->put_contents( MACHETE_DATA_PATH . $settings['cookie_filename'], $cookies_bar_js ) ) {
				if ( ! $silent ) {
					// translators: %s path to machete data dir.
					$this->notice( sprintf( __( 'Error writing static javascript file to %s please check file permissions. Aborting to prevent inconsistent state.', 'machete' ), MACHETE_RELATIVE_DATA_PATH ), 'error' );
				}
				return false;
			}
		}

		// delete old .js file and generate a new one to prevent caching.
		if ( ! empty( $this->settings['cookie_filename'] ) && file_exists( MACHETE_DATA_PATH . $this->settings['cookie_filename'] ) ) {
			if ( ! $this->delete( MACHETE_DATA_PATH . $this->settings['cookie_filename'] ) ) {
				if ( ! $silent ) {
					// translators: %s path to machete data dir.
					$this->notice( sprintf( __( 'Could not delete old javascript file from %s please check file permissions . Aborting to prevent inconsistent state.', 'machete' ), MACHETE_RELATIVE_DATA_PATH ), 'warning' );
				}
				return false;
			}
		}

		// Option saved WITH autoload.
		if ( update_option( 'machete_cookies_settings', $settings, 'yes' ) ) {
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
	/**
	 * Echoes a cookie bar preview for the Machete cookie module config page.
	 */
	protected function preview_cookie_bar() {

		if ( ! isset( $this->settings['bar_status'] ) || ( 'enabled' !== $this->settings['bar_status'] ) ) {
				return false;
		}
		if ( ! isset( $this->settings['cookie_filename'] ) ) {
			return false;
		}
		if ( ! file_exists( MACHETE_DATA_PATH . $this->settings['cookie_filename'] ) ) {
			return false;
		}

		echo '<script>';
		require MACHETE_DATA_URL . $this->settings['cookie_filename'];
		echo '</script>';

	}
	/**
	 * Echoes the cookie bar for use in the front-end.
	 */
	public function render_cookie_bar() {

		if ( ! isset( $this->settings['bar_status'] ) || ( 'enabled' !== $this->settings['bar_status'] ) ) {
				return false;
		}
		if ( ! isset( $this->settings['cookie_filename'] ) ) {
			return false;
		}
		if ( ! file_exists( MACHETE_DATA_PATH . $this->settings['cookie_filename'] ) ) {
			return false;
		}

		?><script>
	if (!navigator.userAgent || (
		(navigator.userAgent.indexOf("Speed Insights") == -1) &&
		(navigator.userAgent.indexOf("Googlebot") == -1)
	)) {(function(){
		var s = document.createElement('script'); s.type = 'text/javascript';
		s.async = true; s.src = '<?php echo esc_url( MACHETE_DATA_URL . $this->settings['cookie_filename'] ); ?>';
		var body = document.getElementsByTagName('body')[0];
		body.appendChild(s);
	})()}
	</script>
		<?php
	}
}
$machete->modules['cookies'] = new MACHETE_COOKIES_MODULE();
