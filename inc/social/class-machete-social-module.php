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
 * Machete Social Sharing Module class
 */
class MACHETE_SOCIAL_MODULE extends MACHETE_MODULE {
	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init( array(
			'slug'        => 'social',
			'title'       => __( 'Social Sharing', 'machete' ),
			'full_title'  => __( 'Social Sharing Buttons', 'machete' ),
			'description' => __( 'Social sharing icons as simple as can be. No bloat, no extra JS, no APIs', 'machete' ),
			'role'        => 'publish_posts', // targeting Author role.
		) );
		$this->default_settings = array(
			'status'     => 'disabled',
			/* translators: %%post_type%% is a placeholder, keep it as is. */
			'title'      => __( 'Share this %%post_type%%', 'machete' ),
			'positions'  => array(
				'below',
			),
			'networks'   => array(
				'facebook',
				'twitter',
				'googleplus',
			),
			'post_types' => array( 'post' ),
			'theme'      => 'color',
			'responsive' => true,
		);
		$this->positions        = array(
			'above' => __( 'At the beginning of the content', 'machete' ),
			'below' => __( 'At the end of the content', 'machete' ),
		);
		$this->networks         = array(
			'facebook'   => array(
				'title' => _x( 'Facebook', 'network name', 'machete' ),
				'label' => _x( 'Share this', 'Facebook button label', 'machete' ),
				'url'   => 'https://facebook.com/sharer/sharer.php?u=%s',
			),
			'twitter'    => array(
				'title' => _x( 'Twitter', 'network name', 'machete' ),
				'label' => _x( 'Tweet this', 'Twitter button label', 'machete' ),
				'url'   => 'http://twitter.com/intent/tweet?url=%s',
			),
			'googleplus' => array(
				'title' => _x( 'Google+', 'network name', 'machete' ),
				'label' => _x( 'Share this', 'google+ button label', 'machete' ),
				'url'   => 'https://plus.google.com/share?url=%s',
			),
			'linkedin'   => array(
				'title' => _x( 'LinkedIn', 'network name', 'machete' ),
				'label' => _x( 'Pin this', 'Pinterest button label', 'machete' ),
				'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=%s',
			),
			'whatsapp'   => array(
				'title' => _x( 'WhatsApp', 'network name', 'machete' ),
				'label' => _x( 'Pin this', 'Pinterest button label', 'machete' ),
				'url'   => 'whatsapp://send?text=%s',
			),
			'pinterest'  => array(
				'title' => _x( 'Pinterest', 'network name', 'machete' ),
				'label' => _x( 'Pin this', 'Pinterest button label', 'machete' ),
				'url'   => 'http://pinterest.com/pin/create/button/?url=%s',
			),

		);

	}
	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {
		$this->read_settings();

		$this->valid_post_types = $this->get_valid_post_types();

		add_action('admin_init', function() {
			if ( filter_input( INPUT_POST, 'machete-social-saved' ) !== null ) {
				check_admin_referer( 'machete_save_social' );
				$this->save_settings( filter_input_array( INPUT_POST ) );
			}
		});
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}
	/**
	 * Executes code related to the front-end.
	 *
	 * @todo Hook render_cookie_bar function only if bar is active.
	 */
	public function frontend() {
		$this->read_settings();


		// ToDo get enabled post types and positions from settings

		if ( ! is_single() ) {

			wp_enqueue_style(
				'machete_social',
				plugins_url( 'css/social.css', __FILE__ ),
				array(), MACHETE_VERSION
			);

			wp_enqueue_script(
				'machete_social',
				plugins_url( 'js/share.js', __FILE__ ),
				array(), MACHETE_VERSION, true
			);

			add_filter('the_content', function( $content ) {

				global $post;

				$post_type_obj = get_post_type_object( $post->post_type );

				$title = str_replace(
					'%%post_type%%',
					strtolower( $post_type_obj->labels->singular_name ),
					$this->settings['title']
				);

				$share_buttons = $this->share_buttons();

				return $this->top_share_block( $share_buttons ) . $content . $this->bottom_share_block( $share_buttons, $title );
			} );
		}
	}

	private function share_buttons () {
		$rt = '<ul class="machete-social-share">';

		foreach ( $this->settings['networks'] as $network_slug ) {
			$network = $this->networks[ $network_slug ];

			$url = sprintf( $network['url'], rawurlencode( get_permalink() ) );

			$rt .= '<li class="mct-ico-' . esc_attr( $network_slug ) . '"><a href="' . esc_url( $url ) . '" data-network="' . esc_attr( $network_slug ) . '">' . esc_html( $network['label'] ) . '</a></li>' . "\n";
		}

		$rt .= '</ul>';
		return $rt;
	}


	private function top_share_block( $buttons ) {
		return '<div id="machete-top-share-buttons">' . $buttons . '</div>';
	}

	private function bottom_share_block( $buttons, $title = '', $responsive = false ) {

		if ( $this->settings['responsive'] ) {
			$rt = '<div id="machete-bottom-share-buttons" class="machete-responsive">';
		} else {
			$rt = '<div id="machete-bottom-share-buttons">';
		}
		if ( ! empty( $title ) ) {
			$rt .= '<h2>' . esc_html( $title ) . '</h2>';
		}
		return $rt . $buttons . '</div>';
	}

	protected function get_valid_post_types() {
		$post_types = get_post_types(
			array(
				'public'             => true,
				'publicly_queryable' => true,
			),
			'objects',
			'or'
		);

		$valid_post_types = array();

		foreach ( $post_types as $post_type => $attrs ) {
			if ( 'attachment' === $post_type ) {
				continue;
			}
			$valid_post_types[ $post_type ] = $attrs->label;
		}
		return $valid_post_types;
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
}
$machete->modules['social'] = new MACHETE_SOCIAL_MODULE();
