<?php
/**
 * Machete Social Sharing class
 *
 * @package    WordPress
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
		$this->init(
			array(
				'slug'        => 'social',
				'title'       => __( 'Social Sharing', 'machete' ),
				'full_title'  => __( 'Social Sharing Buttons', 'machete' ),
				'description' => __( 'Social sharing buttons as simple as they can be. No bloat, no extra JS libraries, no API calls.', 'machete' ),
				'role'        => 'publish_posts', // targeting Author role.
			)
		);
		$this->default_settings = array(
			'status'     => 'disabled',
			/* translators: %%post_type%% is a placeholder, keep it as is. */
			'title'      => __( 'Share this %%post_type%%', 'machete' ),
			'networks'   => array(
				'facebook',
				'twitter',
			),
			'positions'  => array( 'after', 'footer' ),
			'post_types' => array( 'post' ),
			'theme'      => 'color',
			'responsive' => true,
		);
		$this->positions        = array(
			'before' => __( 'At the beginning of the content', 'machete' ),
			'after'  => __( 'At the end of the content (hidden on mobile)', 'machete' ),
			'footer' => __( 'Floating footer (mobile only)', 'machete' ),
		);
		$this->networks         = array(
			'facebook'  => array(
				'title' => _x( 'Facebook', 'network name', 'machete' ),
				'label' => _x( 'Share this', 'Facebook button label', 'machete' ),
				'url'   => 'https://facebook.com/sharer/sharer.php?u=%s',
			),
			'twitter'   => array(
				'title' => _x( 'Twitter', 'network name', 'machete' ),
				'label' => _x( 'Tweet this', 'Twitter button label', 'machete' ),
				'url'   => 'https://twitter.com/intent/tweet?url=%s',
			),
			'linkedin'  => array(
				'title' => _x( 'LinkedIn', 'network name', 'machete' ),
				'label' => _x( 'Share this', 'LinkeIn button label', 'machete' ),
				'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=%s',
			),
			'whatsapp'  => array(
				'title' => _x( 'WhatsApp (only on mobile devices)', 'network name', 'machete' ),
				'label' => _x( 'Share this', 'WhatsApp button label', 'machete' ),
				'url'   => 'https://api.whatsapp.com/send?text=%s',
			),
			'pinterest' => array(
				'title' => _x( 'Pinterest', 'network name', 'machete' ),
				'label' => _x( 'Pin this', 'Pinterest button label', 'machete' ),
				'url'   => 'https://pinterest.com/pin/create/button/?url=%s',
			),

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

		$below_pos = array_search( 'below', $this->settings['positions'], true );

		if ( false !== $below_pos ) {
			// Old positions were 'above' and 'below'.
			// 'below' is converted to 'after' + 'footer'.
			unset( $this->settings['positions'], $below_pos );
			$this->settings['positions'][] = 'after';
			$this->settings['positions'][] = 'footer';
		}

		return array_merge( $this->default_settings, $this->settings );
	}

	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {
		$this->read_settings();

		$this->valid_post_types = $this->get_valid_post_types();

		add_action(
			'admin_init',
			function() {
				if ( filter_input( INPUT_POST, 'machete-social-saved' ) !== null ) {
					check_admin_referer( 'machete_save_social' );
					$this->save_settings( filter_input_array( INPUT_POST ) );
				}
			}
		);
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}
	/**
	 * Executes code related to the front-end.
	 */
	public function frontend() {

		$this->read_settings();

		// shortcode returns empty string if it cannot be rendered.
		add_shortcode( 'mct-social-share', '__return_empty_string' );

		// bail if main switch is set to inactive or no active networks.
		if (
			( 'enabled' !== $this->settings['status'] ) ||
			( 0 === count( $this->settings['networks'] ) )
		) {
			return;
		}

		add_action(
			'wp_enqueue_scripts',
			function() {

				global $post;
				// bail if (no active positions OR no active post types ) AND no shortcode is present.
				if (
					(
						( 0 === count( $this->settings['positions'] ) ) ||
						( ! in_array( $post->post_type, $this->settings['post_types'], true ) )
					) && (
						! has_shortcode( $post->post_content, 'mct-social-share' )
					)
				) {
					return;
				}

				wp_enqueue_style(
					'machete_social',
					plugins_url( 'css/social.min.css', __FILE__ ),
					array(),
					MACHETE_VERSION
				);

				wp_enqueue_script(
					'machete_social',
					plugins_url( 'js/share.min.js', __FILE__ ),
					array(),
					MACHETE_VERSION,
					true
				);

				/**
				 * Redefines the mct-social-share shortcode for manually displaying the buttons
				 * [mct-social-share]
				 */
				remove_shortcode( 'mct-social-share' );
				add_shortcode(
					'mct-social-share',
					function() {
						$out  = '<div id="mct-shortcode-share" class="mct-social-share">';
						$out .= $this->share_buttons();
						$out .= '</div>';
						return $out;
					}
				);
			}
		);

		add_filter(
			'the_content',
			function( $content ) {

				// bail if no active positions.
				if ( 0 === count( $this->settings['positions'] ) ) {
					return $content;
				}

				global $post;
				if (
					! ( is_single() || is_singular() ) ||
					! in_the_loop() ||
					! is_main_query() ||
					// check if current post type is active.
					( ! in_array( $post->post_type, $this->settings['post_types'], true ) )
				) {
					return $content;
				}

				$share_buttons = $this->share_buttons();

				if ( in_array( 'before', $this->settings['positions'], true ) ) {
					$content = $this->top_share_block( $share_buttons ) . $content;
				}

				if (
					in_array( 'after', $this->settings['positions'], true ) ||
					in_array( 'bottom', $this->settings['positions'], true )
				) {
					if ( ! empty( $this->settings['title'] ) ) {
						// replace the %%post_type%% placholder only if present.
						if ( false !== strpos( $this->settings['title'], '%%post_type%%' ) ) {

							// saves one DB query for standard post types.
							switch ( $post->post_type ) {
								case 'page':
									$post_type_name = _x( 'page', 'Page post type singular label', 'machete' );
									break;
								case 'post':
									$post_type_name = _x( 'post', 'Post post type singular label', 'machete' );
									break;
								case 'product':
									$post_type_name = _x( 'product', 'Product post type singular label', 'machete' );
									break;
								default:
									$post_type      = get_post_type_object( $post->post_type );
									$post_type_name = mb_strtolower( $post_type->labels->singular_name );

							}

							// replaces the %%post_type%% placholder.
							$title = str_replace(
								'%%post_type%%',
								$post_type_name,
								$this->settings['title']
							);
						} else {
							$title = $this->settings['title'];
						}
					} else {
						$title = null;
					}

					$content .= $this->bottom_share_block( $share_buttons, $title );
				} // end if after.

				return $content;

			}
		);
	}

	/**
	 * Pregenerates the HTML code for the buttons.
	 */
	private function share_buttons() {
		$rt = '<ul class="mct-share-buttons">';

		foreach ( $this->settings['networks'] as $network_slug ) {
			$network = $this->networks[ $network_slug ];

			$canonical = wp_get_canonical_url();
			if ( ! $canonical ) {
				$canonical = get_permalink();
			}

			$url = sprintf( $network['url'], rawurlencode( $canonical ) );

			$rt .= '<li class="mct-ico-' . esc_attr( $network_slug ) . '"><a href="' . esc_url( $url ) . '" data-network="' . esc_attr( $network_slug ) . '">' . esc_html( $network['label'] ) . '</a></li>' . "\n";
		}

		$rt .= '</ul>';
		return $rt;
	}

	/**
	 * Composes top sharing blocks div
	 *
	 * @param string $buttons HTML code for the buttons, pregenerated by share_buttons().
	 */
	private function top_share_block( $buttons ) {
		return '<div id="mct-top-share" class="mct-social-share">' . $buttons . '</div>';
	}

	/**
	 * Composes bottom sharing blocks div
	 *
	 * @param string $buttons HTML code for the buttons, pregenerated by share_buttons().
	 * @param string $title   heading text for the bootm buttons area.
	 */
	private function bottom_share_block( $buttons, $title = '' ) {

		$classes = array( 'mct-social-share' );

		if ( ! in_array( 'after', $this->settings['positions'], true ) ) {
			$classes[] = 'machete-hide-desktop';
		}
		if ( ! in_array( 'footer', $this->settings['positions'], true ) ) {
			$classes[] = 'machete-hide-mobile';
		}

		$rt = '<div id="mct-bottom-share" class="' . join( ' ', $classes ) . '">';

		if ( ! empty( $title ) ) {
			$rt .= '<h2>' . esc_html( $title ) . '</h2>';
		}
		return $rt . $buttons . '</div>';
	}





	/**
	 * Gets the list of post types where sharing buttons can be shown
	 */
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
		$settings = $this->read_settings();

		if ( empty( $options['social_status'] ) || ( 'disabled' === $options['social_status'] ) ) {
			$settings['status'] = 'disabled';
		} else {
			$settings['status'] = 'enabled';
		}

		if (
			array_key_exists( 'social_title', $options ) &&
			( ! empty( $options['social_title'] ) )
		) {
			$options['title']  = esc_html( trim( $options['social_title'] ) );
			$settings['title'] = $options['title'];
		} else {
			$settings['title'] = '';
		}

		if (
			array_key_exists( 'networkEnabled', $options ) &&
			( count( $options['networkEnabled'] ) > 0 )
		) {
			$settings['networks'] = $options['networkEnabled'];
		} else {
			$settings['networks'] = array();
		}

		if (
			array_key_exists( 'positionEnabled', $options ) &&
			( count( $options['positionEnabled'] ) > 0 )
		) {
			$settings['positions'] = $options['positionEnabled'];
		} else {
			$settings['positions'] = array();
		}

		if (
			array_key_exists( 'postTypeEnabled', $options ) &&
			( count( $options['postTypeEnabled'] ) > 0 )
		) {
			$settings['post_types'] = $options['postTypeEnabled'];
		} else {
			$settings['post_types'] = array();
		}

		if ( $this->is_equal_array( $this->settings, $settings ) ) {
			if ( ! $silent ) {
				$this->save_no_changes_notice();
			}
			return true;
		}

		// Option saved WITHOUT autoload.
		if ( update_option( 'machete_social_settings', $settings, 'no' ) ) {
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
$machete->modules['social'] = new MACHETE_SOCIAL_MODULE();
