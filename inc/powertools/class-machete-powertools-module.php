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

	public $powertools_array;
	public $all_powertools_checked;

	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug'            => 'powertools',
				'is_active'       => false,
				'has_warning'     => true,
				'can_be_disabled' => true,
			)
		);

		$this->powertools_array = array(
			'widget_shortcodes'   => array(),
			'rss_thumbnails'      => array(),
			'page_excerpts'       => array(),
			'move_scripts_footer' => array(),
			'defer_all_scripts'   => array(),
			'disable_feeds'       => array(),
			'enable_svg'          => array(),
			'disable_search'      => array(),
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

		$this->load_i18n();

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
				case 'purge_post_revisions':
					$this->purge_post_revisions();
					break;
				case 'purge_orphaned_meta':
					$this->purge_orphaned_meta();
					break;
				case 'purge_expired_cron':
					$this->purge_expired_cron();
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
	 * @param bool  $silent  prevent the function from generating admin notices.
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
	 * Deletes all post revisions and their related meta/term rows.
	 */
	private function purge_post_revisions() {
		global $wpdb;

		$wpdb->query(
			"DELETE pm FROM $wpdb->postmeta pm
			INNER JOIN $wpdb->posts p ON pm.post_id = p.ID
			WHERE p.post_type = 'revision'"
		); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		$wpdb->query(
			"DELETE tr FROM $wpdb->term_relationships tr
			INNER JOIN $wpdb->posts p ON tr.object_id = p.ID
			WHERE p.post_type = 'revision'"
		); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		$rows = $wpdb->query(
			"DELETE FROM $wpdb->posts WHERE post_type = 'revision'"
		); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		// translators: %s: number of deleted post revisions.
		$this->notice( sprintf( _n( 'Success! %s post revision deleted.', 'Success! %s post revisions deleted.', $rows, 'machete' ), number_format_i18n( $rows ) ), 'success' );
		return true;
	}

	/**
	 * Deletes postmeta rows whose post no longer exists.
	 */
	private function purge_orphaned_meta() {
		global $wpdb;

		$rows = $wpdb->query(
			"DELETE pm FROM $wpdb->postmeta pm
			LEFT JOIN $wpdb->posts p ON p.ID = pm.post_id
			WHERE p.ID IS NULL"
		); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		// translators: %s: number of deleted postmeta rows.
		$this->notice( sprintf( _n( '%s orphaned postmeta row deleted.', '%s orphaned postmeta rows deleted.', $rows, 'machete' ), number_format_i18n( $rows ) ), 'success' );
		return true;
	}

	/**
	 * Removes cron events scheduled in the past.
	 */
	private function purge_expired_cron() {
		if ( ! function_exists( '_get_cron_array' ) ) {
			require_once ABSPATH . 'wp-includes/cron.php';
		}

		$cron    = _get_cron_array();
		$removed = 0;
		$time    = time();

		if ( ! empty( $cron ) ) {
			foreach ( $cron as $timestamp => $hooks ) {
				if ( (int) $timestamp >= $time ) {
					continue;
				}
				foreach ( $hooks as $events ) {
					$removed += count( $events );
				}
				unset( $cron[ $timestamp ] );
			}
			_set_cron_array( $cron );
		}

		// translators: %s: number of removed cron events.
		$this->notice( sprintf( _n( '%s expired cron event removed.', '%s expired cron events removed.', $removed, 'machete' ), number_format_i18n( $removed ) ), 'success' );
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
