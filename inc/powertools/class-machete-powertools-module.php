<?php
/**
 * Machete PowerTools Module class

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete PowerTools Module class
 */
class MACHETE_POWERTOOLS_MODULE extends MACHETE_MODULE {
	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug'            => 'powertools',
				'title'           => '<span style="color: #ff9900">' . __( 'PowerTools', 'machete' ) . '</span>',
				'full_title'      => __( 'Machete PowerTools', 'machete' ),
				'description'     => __( 'Machete PowerTools is a free upgrade module targeted at WordPress developers and power users.', 'machete' ),
				'is_active'       => false,
				'can_be_disabled' => false,
			)
		);

		$this->powertools_array = array(
			'widget_shortcodes'   => array(
				'title'       => __( 'Shortcodes in Widgets', 'machete' ),
				'description' => __( 'Enables the use of shortcodes in text/html widgets. It may slightly impact performance', 'machete' ),
			),

			'rss_thumbnails'      => array(
				'title'       => __( 'Thumbnails in RSS', 'machete' ),
				'description' => __( 'Add the featured image or the first attached image as the thumbnail of each post in the RSS feed', 'machete' ),
			),
			'page_excerpts'       => array(
				'title'       => __( 'Excerpts in Pages', 'machete' ),
				'description' => __( 'Enables excerpts in pages. Useless for most people but awesome when combined with a page builder like Visual Composer', 'machete' ),
			),
			'save_with_keyboard'  => array(
				'title'       => __( 'Save with keyboard', 'machete' ),
				'description' => __( 'Lets you save your posts, pages, theme and plugin files in the most natural way: pressing Ctrl+S (or Cmd+S on Mac). It saves unpublished posts/pages as drafts and updates the ones that are already public', 'machete' ),
			),
			'move_scripts_footer' => array(
				'title'       => __( 'Move scripts to footer', 'machete' ),
				'description' => __( 'Move all enqueued JS scripts from the header to the footer. Machete will de-register the call for the JavaScript to load in the HEAD section of the site and re-register it to the FOOTER.', 'machete' ),
			),
			'defer_all_scripts'   => array(
				'title'       => __( 'Defer your JavaScript', 'machete' ),
				'description' => __( 'The defer attribute also downloads the JS file during HTML parsing, but it only executes it after the parsing has completed. Executed in order of appearance on the page', 'machete' ),
			),
			'disable_feeds'       => array(
				'title'       => __( 'Disable all feeds', 'machete' ),
				'description' => __( 'RSS, RDF, Atom... disables all of them and makes life a little less easy for leechers.', 'machete' ),
			),
			'enable_svg'          => array(
				'title'       => __( 'Enable SVG images', 'machete' ),
				// translators: Link the post "SVG uploads in WordPress (the Inconvenient Truth)".
				'description' => sprintf( __( 'Enables the upload of SVG images to the media library. This <a href="%s" target="_blank" rel="noopener noreferrer">has been proven to be dangerous</a>, so be careful.', 'machete' ), 'https://bjornjohansen.no/svg-in-wordpress' ),
			),
			'widget_oembed'       => array(
				'title'       => __( 'OEmbed in Widgets', 'machete' ),
				'description' => __( 'Enables OEmbed in text/html widgets.', 'machete' ) . ' <br><span style="color: red">' . __( 'Deprecated. This feature is natively enabled since WordPress 4.8.1', 'machete' ) . '</span>',
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
			require $this->path . 'powertools.php';
		}
	}
	/**
	 * Executes code related to the WordPress admin.
	 * Loads optimization code if there is any option active.
	 */
	public function admin() {
		$this->read_settings();

		if ( filter_input( INPUT_POST, 'machete-powertools-saved' ) !== null ) {
			check_admin_referer( 'machete_save_powertools' );
			$this->save_settings(
				filter_input( INPUT_POST, 'optionEnabled', FILTER_DEFAULT, FILTER_FORCE_ARRAY )
			);
		}
		if ( filter_input( INPUT_POST, 'machete-powertools-action' ) !== null ) {
			check_admin_referer( 'machete_powertools_action' );

			switch ( filter_input( INPUT_POST, 'machete-powertools-action' ) ) {
				case 'purge_transients':
					$this->purge_transients();
					break;
				case 'purge_revisions':
					$this->purge_post_revisions();
					break;
				case 'flush_rewrites':
					$this->flush_rewrite_rules();
					break;
				case 'flush_opcache':
					$this->flush_opcache();
					break;
				case 'flush_wpcache':
					$this->flush_wpcache();
					break;
				default:
					$this->notice( __( 'Unknown action requested', 'machete' ), 'error' );
			}
		}

		$this->all_powertools_checked = ( count( array_intersect( array_keys( $this->powertools_array ), $this->settings ) ) === count( $this->powertools_array ) ) ? true : false;

		if ( count( $this->settings ) > 0 ) {
			require $this->path . 'powertools.php';
		}

		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}
	/**
	 * Saves options to database
	 *
	 * @param array $options options array, normally $_POST.
	 * @param bool  $silent prevent the function from generating admin notices.
	 */
	protected function save_settings( $options = array(), $silent = false ) {
		if ( null === $options ) {
			$options = array();
		}

		$this->read_settings();
		$options = array_intersect( $options, array_keys( $this->powertools_array ) );

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

			if ( update_option( 'machete_powertools_settings', $options ) ) {
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
			if ( delete_option( 'machete_powertools_settings' ) ) {
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
	 * Deletes all expired transients. The multi-table delete syntax is used.
	 * to delete the transient record from table a, and the corresponding.
	 * transient_timeout record from table b.
	 *
	 * Based on code inside core's upgrade_network() function.
	 */
	private function purge_transients() {
		global $wpdb;

		$rows = $wpdb->query(
			$wpdb->prepare(
				"DELETE a, b FROM $wpdb->options a, $wpdb->options b
				WHERE a.option_name LIKE %s
				AND a.option_name NOT LIKE %s
				AND b.option_name = CONCAT( '_transient_timeout_', SUBSTRING( a.option_name, 12 ) )
				AND b.option_value < %d",
				$wpdb->esc_like( '_transient_' ) . '%',
				$wpdb->esc_like( '_transient_timeout_' ) . '%',
				time()
			)
		); // phpcs: cache ok, db call ok.

		$rows2 = $wpdb->query(
			$wpdb->prepare(
				"DELETE a, b FROM $wpdb->options a, $wpdb->options b
				WHERE a.option_name LIKE %s
				AND a.option_name NOT LIKE %s
				AND b.option_name = CONCAT( '_site_transient_timeout_', SUBSTRING( a.option_name, 17 ) )
				AND b.option_value < %d",
				$wpdb->esc_like( '_site_transient_' ) . '%',
				$wpdb->esc_like( '_site_transient_timeout_' ) . '%',
				time()
			)
		); // phpcs: cache ok, db call ok.
		// translators: $d number of deleted transsients.
		$this->notice( sprintf( __( '%d Transients Rows Cleared', 'machete' ), $rows + $rows2 ), 'success' );
		return true;
	}

	/**
	 * Deletes all unused post revisions.
	 */
	private function purge_post_revisions() {
		global $wpdb;

		$rows = $wpdb->query(
			"DELETE a,b,c
			FROM wp_posts a
			WHERE a.post_type = 'revision'
			LEFT JOIN wp_term_relationships b
			ON (a.ID = b.object_id)
			LEFT JOIN wp_postmeta c ON (a.ID = c.post_id);"
		);  // phpcs: cache ok, db call ok.
		// translators: $d number of deleted post revisions.
		$this->notice( sprintf( _n( 'Success! %s Post revision deleted.', 'Success! %s Post revisions deleted.', $rows, 'machete' ), $rows ), 'success' );
		return true;
	}
	/**
	 * Flushes rewrite rules.
	 */
	private function flush_rewrite_rules() {
		flush_rewrite_rules();
		$this->notice( __( 'Rewrite rules flushed', 'machete' ), 'success' );
		return true;
	}
	/**
	 * Flushes WordPress object cache.
	 */
	private function flush_wpcache() {
		wp_cache_flush();
		$this->notice( __( 'Object cache flushed', 'machete' ), 'success' );
		return true;
	}
	/**
	 * Flushes Opcache.
	 */
	private function flush_opcache() {
		opcache_reset();
		$this->notice( __( 'Opcache flushed', 'machete' ), 'success' );
		return true;
	}
}
$machete->modules['powertools'] = new MACHETE_POWERTOOLS_MODULE();
