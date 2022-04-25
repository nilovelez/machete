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
	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug'            => 'cleanup',
				'title'           => __( 'Optimization', 'machete' ),
				'full_title'      => __( 'WordPress Optimization', 'machete' ),
				'description'     => __( 'Reduces much of the legacy code bloat in WordPress page headers. It also has some tweaks to make your site faster and safer.', 'machete' ),
				'is_active'       => true,
				'has_config'      => true,
				'can_be_disabled' => true,
				'role'            => 'manage_options',
			)
		);

		$this->cleanup_array = array(
			'rsd_link'          => array(
				'title'       => __( 'RSD', 'machete' ),
				'description' => __( 'Remove Really Simple Discovery (RSD) links. They are used for automatic pingbacks.', 'machete' ),
			),
			'wlwmanifest'       => array(
				'title'       => __( 'WLW', 'machete' ),
				'description' => __( 'Remove the link to wlwmanifest.xml. It is needed to support Windows Live Writer. Yes, seriously.', 'machete' ),
			),
			'feed_links'        => array(
				'title'       => __( 'feed_links', 'machete' ),
				'description' => __( 'Remove Automatics RSS links. RSS will still work, but you will need to provide your own links.', 'machete' ),
			),
			'feed_generator'    => array(
				'title'       => __( 'feed_generator', 'machete' ),
				'description' => __( 'Remove generator tag from RSS feeds.', 'machete' ),
			),
			'next_prev'         => array(
				'title'       => __( 'adjacent_posts', 'machete' ),
				'description' => __( 'Remove the next and previous post links from the header', 'machete' ),
			),
			'shortlink'         => array(
				'title'       => __( 'shortlink', 'machete' ),
				'description' => __( 'Remove the shortlink url from header', 'machete' ),
			),
			'wp_generator'      => array(
				'title'       => __( 'wp_generator', 'machete' ),
				'description' => __( 'Remove WordPress and WooCommerce meta generator tags. Used by attackers to detect the WordPress version.', 'machete' ),
			),
			'ver'               => array(
				'title'       => __( 'version', 'machete' ),
				'description' => __( 'Remove WordPress version var (?ver=) after styles and scripts. Used by attackers to detect the WordPress version.', 'machete' ),
			),
			'recentcomments'    => array(
				'title'       => __( 'recent_comments_style', 'machete' ),
				'description' => __( 'Removes a block of inline CSS used by old themes from the header', 'machete' ),
			),
			'wp_resource_hints' => array(
				'title'       => __( 'dns-prefetch', 'machete' ),
				'description' => __( 'Removes dns-prefetch links from the header', 'machete' ),
			),
		);

		$this->optimize_array = array(
			'emojicons'              => array(
				'title'       => __( 'Emojicons', 'machete' ),
				'description' => __( 'Remove lots of emoji styles and scripts from the header, RSS, mail function, tinyMCE editor...', 'machete' ),
			),
			'pdf_thumbnails'         => array(
				'title'       => __( 'PDF Thumbnails', 'machete' ),
				'description' => __( 'Starting with 4.7, WordPress tries to make thumbnails from each PDF you upload, potentially crashing your server if GhostScript and ImageMagick aren\'t properly configured. This option disables PDF thumbnails.', 'machete' ),
			),
			'limit_revisions'        => array(
				'title'       => __( 'Limit Post Revisions', 'machete' ),
				'description' => __( 'Limits the number of stored revisions to 5 only if WP_POST_REVISIONS constant has not been defined.', 'machete' ),
			),
			'slow_heartbeat'         => array( // @fpuente addons
				'title'       => __( 'Slow Heartbeat', 'machete' ),
				'description' => __( 'By default, heartbeat makes a post call every 15 seconds on post edit pages. Change to 60 seconds (less CPU usage).', 'machete' ),
			),
			'comments_reply_feature' => array( // @fpuente addons
				'title'       => __( 'JS Comment reply', 'machete' ),
				'description' => __( 'Load the comment-reply JS file only when needed.', 'machete' ),
			),
			'empty_trash_soon'       => array( // @fpuente addons
				'title'       => __( 'Empty trash every week', 'machete' ),
				'description' => __( 'You can shorten the time posts are kept in the trash, which is 30 days by default, to 1 week.', 'machete' ),
			),
			'capital_P_dangit'       => array(
				'title'       => __( 'capital_P_dangit', 'machete' ),
				'description' => __( 'Removes the filter that converts Wordpress to WordPress in every dang title, content or comment text.', 'machete' ),
			),
			'disable_editor'         => array(
				'title'       => __( 'Plugin and theme editor', 'machete' ),
				'description' => __( 'Disables the plugins and theme editor. A mostly useless tool that can be very dangerous in the wrong hands.', 'machete' ),
			),
			'medium_large_size'      => array(
				'title'       => __( 'medium_large thumbnail', 'machete' ),
				'description' => __( 'Prevents WordPress from generating the medium_large 768px thumbnail size of image uploads.', 'machete' ),
			),
			'comment_autolinks'      => array(
				'title'       => __( 'No comment autolinks', 'machete' ),
				'description' => __( 'URLs in comments are converted to links by default. This feature is often exploited by spammers.', 'machete' ),
			),
			'no_duotone'      => array(
				'title'       => __( 'Disable duotone styles', 'machete' ),
				'description' => __( 'Removes the CSS and SVG injected by Gutenberg duotone filters.', 'machete' ),
			),
		);

		$this->tweaks_array = array(
			'json_api'       => array(
				'title'       => __( 'JSON API', 'machete' ),
				'description' => __( 'Remove the JSON-API links from page headers. Also require that API consumers be authenticated.', 'machete' ) . ' <br><span style="color: #d94f4f">' . __( 'Be careful. Breaks the block editor and many plugins that use the REST API.', 'machete' ) . '</span>',
			),
			'xmlrpc'         => array(
				'title'       => __( 'XML-RPC', 'machete' ),
				'description' => __( 'Disable the XML-RPC interface. ', 'machete' ),
			),
			'jquery-migrate' => array(
				'title'       => __( 'remove jQuery-migrate', 'machete' ),
				'description' => __( 'jQuery-migrate provides diagnostics that can simplify upgrading to new versions of jQuery, you can safely disable it.', 'machete' ) . ' <br><span style="color: #d94f4f">' . __( 'May break some themes and plugins that depend on legacy code.', 'machete' ) . '</span>',
			),
			'oembed_scripts' => array( // @fpuente addons
				'title'       => __( 'Remove oEmbed Scripts', 'machete' ),
				'description' => __( 'Since WordPress 4.4, oEmbed is installed and available by default. If you don’t need oEmbed, you can remove it.', 'machete' ),
			),
			'wpcf7_refill'   => array(
				'title'       => __( 'Remove CF7 refill', 'machete' ),
				'description' => __( 'Disables the Contact Form 7 refill script. Saves one hit to wp-ajax.php per pageview on cached sites', 'machete' ) . ' <br><span style="color: #d94f4f">' . __( 'Activate only if you are not using the reCAPTCHA Contact Form 7 field', 'machete' ) . '</span>',
			),
			'jpeg_quality'   => array(
				'title'       => __( 'Reduce JPEG quality', 'machete' ),
				'description' => __( 'When WordPress generates a JPEG thumbnail it compresses the image to 82% quality. Check this to reduce the default quality to 72%. (It doesn\'t affect original image quality).', 'machete' ),
			),
			'gutenberg_css'  => array(
				'title'       => __( 'Remove block editor CSS', 'machete' ),
				'description' => __( 'Dequeues Gutenberg CSS stylesheets from the from the frontend.', 'machete' ) . ' <br><span style="color: #d94f4f">' . __( 'Check this only if you are not using the block editor.', 'machete' ) . '</span>',
			),
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
