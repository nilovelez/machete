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

	public $themes;
	public $cookies_bar_html;
	public $cookies_configbar_html;

	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug' => 'cookies',
				'role' => 'unfiltered_html', // targeting Editor role.
			)
		);
		$this->default_settings = array(
			'bar_status'      => 'disabled',
			// l18n: 'warning_text'
			// l18n: 'accept_text'
			// l18n: 'partial_accept_text'
			// l18n: 'config_button_text'
			'bar_theme'       => 'new_light',
			'cookie_filename' => '',
			'accent_color'    => '#2271b1',
		);

		$this->themes = array(
			'new_light' => array(
				'stylesheet' => $this->baseurl . 'css/new_light.css',
			),
			'new_dark'  => array(
				'stylesheet' => $this->baseurl . 'css/new_dark.css',
			),
			'cookie'    => array(
				'stylesheet' => $this->baseurl . 'css/cookie.css',
			),
		);
	}
	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {

		$this->load_i18n();
		$this->read_settings();
		add_action(
			'admin_init',
			function () {
				if ( filter_input( INPUT_POST, 'machete-cookies-saved' ) !== null ) {
					check_admin_referer( 'machete_save_cookies' );
					if ( ! current_user_can( $this->params['role'] ) ) {
						return;
					}
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
		$this->load_i18n();
		$this->read_settings();

		if ( ! isset( $this->settings['bar_status'] ) || ( 'enabled' !== $this->settings['bar_status'] ) ) {
			return false;
		}

		add_action( 'wp_footer', array( $this, 'render_cookie_bar' ) );
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

		// Cookie bar theme.
		if ( ! empty( $options['bar_theme'] ) && ( array_key_exists( $options['bar_theme'], $this->themes ) ) ) {
			$settings['bar_theme'] = $options['bar_theme'];
		}
		if ( empty( $settings['bar_theme'] ) || ! array_key_exists( $settings['bar_theme'], $this->themes ) ) {
			$settings['bar_theme'] = $this->default_settings['bar_theme'];
		}
		$theme_stylesheet = $this->themes[ $settings['bar_theme'] ]['stylesheet'];

		// Cookie bar accent color.
		$accent_color = sanitize_hex_color( $options['accent_color'] ?? '' );
		if ( empty( $accent_color ) ) {
			$accent_color = $this->default_settings['accent_color'];
		}
		$settings['accent_color'] = $accent_color;

		// Cookie bar status.
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

		$options['partial_accept_text'] = sanitize_text_field( $options['partial_accept_text'] );
		if ( empty( $options['partial_accept_text'] ) ) {
			if ( ! $silent ) {
				$this->notice( __( 'Accept required cookies button text can\'t be blank', 'machete' ), 'warning' );
			}
			return false;
		}
		$html_replaces['{{partial_accept_text}}'] = $options['partial_accept_text'];
		$settings['partial_accept_text']          = $options['partial_accept_text'];

		$cookies_bar_js = $this->get_cookies_bar_js( $html_replaces, $theme_stylesheet, $accent_color );

		// cheap and dirty pseudo-random filename generation.
		$settings['cookie_filename'] = 'cookies_mct4_' . bin2hex( random_bytes( 4 ) ) . '.js';

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
					$this->notice( sprintf( __( 'Could not delete old javascript file from %s please check file permissions. Aborting to prevent inconsistent state.', 'machete' ), MACHETE_RELATIVE_DATA_PATH ), 'warning' );
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
	 * Builds the JavaScript that defines the cookie bar variables.
	 *
	 * Every value is JSON encoded, with the characters that are special in HTML
	 * and JavaScript written as \u escapes, so no text can break out of its string
	 * or close the script tag the code is printed in.
	 *
	 * @param array  $html_replaces    Placeholders of the bar HTML and their values.
	 * @param string $theme_stylesheet URL of the bar theme stylesheet.
	 * @param string $accent_color     Accent color, only set when it is not the default one.
	 * @return string JavaScript code.
	 */
	public function get_cookies_bar_js( $html_replaces, $theme_stylesheet, $accent_color = '' ) {
		$json_flags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

		$bar_html = str_replace(
			array_keys( $html_replaces ),
			array_values( $html_replaces ),
			$this->cookies_bar_html
		);

		$js  = 'var machete_cookies_bar_html = ' . wp_json_encode( $bar_html, $json_flags ) . ';' . "\n";
		$js .= 'var machete_cookies_configbar_html = ' . wp_json_encode( $this->cookies_configbar_html, $json_flags ) . ';' . "\n";
		$js .= 'var machete_cookies_bar_stylesheet = ' . wp_json_encode( $theme_stylesheet, $json_flags ) . ';' . "\n";

		if ( ! empty( $accent_color ) && $accent_color !== $this->default_settings['accent_color'] ) {
			$js .= 'var machete_cookies_accent_color = ' . wp_json_encode( $accent_color, $json_flags ) . ';' . "\n";
		}

		return $js;
	}

	/**
	 * Echoes the cookie bar for use in the front-end.
	 */
	public function render_cookie_bar() {

		if ( ! isset( $this->settings['cookie_filename'] ) ) {
			return false;
		}
		if ( ! file_exists( MACHETE_DATA_PATH . $this->settings['cookie_filename'] ) ) {
			return false;
		}

		?>
<script>
		<?php
		echo PHP_EOL;
		$this->readfile( MACHETE_DATA_PATH . $this->settings['cookie_filename'] );
		?>

(function(){
	if ( typeof machete_cookies_bar_stylesheet === 'undefined') return;
	var s = document.createElement('script'); s.type = 'text/javascript';
	s.defer = true; s.src = '<?php echo esc_url( $this->baseurl . 'js/cookies_bar_js.js' ); ?>';
	var body = document.getElementsByTagName('body')[0];
	body.appendChild(s);
})();
</script>
		<?php
	}
}
$machete->modules['cookies'] = new MACHETE_COOKIES_MODULE();
