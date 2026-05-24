<?php
/**
 * Actions definde by the Powertools module.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
POWERTOOLS
widget_shortcodes
rss_thumbnails
page_excerpts
move_scripts_footer
defer_all_scripts
disable_feeds
enable_svg
disable_search
show_admin_ids
disable_admin_bar_frontend
*/

// enable shortcodes in widgets.
if ( in_array( 'widget_shortcodes', $this->settings, true ) && ! is_admin() ) {
	add_filter( 'widget_text', 'do_shortcode', 11 );
}

// enable rss thumbnails.
if ( in_array( 'rss_thumbnails', $this->settings, true ) && ! is_admin() ) {
	/**
	 * Adds the featured image before the content.
	 *
	 * @param string $content post content.
	 */
	function machete_add_rss_thumbnail( $content ) {
		global $post;
		if ( has_post_thumbnail( $post->ID ) ) {
			$content = '<div class="post-thumbnail-feed">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>' . $content;
		}
		return $content;
	}
	add_filter( 'the_excerpt_rss', 'machete_add_rss_thumbnail' );
	add_filter( 'the_content_feed', 'machete_add_rss_thumbnail' );
}

// enable page_excerpts.
if ( in_array( 'page_excerpts', $this->settings, true ) ) {
	add_post_type_support( 'page', 'excerpt' );
}

// Script to Move JavaScript from the Head to the Footer.
if ( in_array( 'move_scripts_footer', $this->settings, true ) ) {
	add_action(
		'wp_enqueue_scripts',
		function () {
			remove_action( 'wp_head', 'wp_print_scripts' );
			remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
			remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );

			add_action( 'wp_footer', 'wp_print_scripts', 5 );
			add_action( 'wp_footer', 'wp_enqueue_scripts', 5 );
			add_action( 'wp_footer', 'wp_print_head_scripts', 5 );
		}
	);
}

// Defer all JS.
if ( in_array( 'defer_all_scripts', $this->settings, true ) ) {
	add_filter(
		'script_loader_tag',
		function ( $tag ) {
			return str_replace( ' src', ' defer="defer" src', $tag );
		},
		10
	);
}

// disable RSS feeds.
if ( in_array( 'disable_feeds', $this->settings, true ) && ! is_admin() ) {
	/**
	 * Kills the execution with a informative error
	 */
	function machete_disable_feed() {
		$link_only = array(
			'a' => array(
				'href' => array(),
			),
		);
		// translators: %s: homepage URL.
		wp_die( sprintf( wp_kses( __( 'No feed available, please visit our <a href="%s">homepage</a>!', 'machete' ), $link_only ), esc_url( home_url( '/' ) ) ) );
	}

	add_action( 'do_feed', 'machete_disable_feed', 1 );
	add_action( 'do_feed_rdf', 'machete_disable_feed', 1 );
	add_action( 'do_feed_rss', 'machete_disable_feed', 1 );
	add_action( 'do_feed_rss2', 'machete_disable_feed', 1 );
	add_action( 'do_feed_atom', 'machete_disable_feed', 1 );
}

// enable SVG.
if ( in_array( 'enable_svg', $this->settings, true ) ) {
	add_filter(
		'upload_mimes',
		function ( $upload_mimes ) {
			$upload_mimes['svg']  = 'image/svg+xml';
			$upload_mimes['svgz'] = 'image/svg+xml';
			return $upload_mimes;
		},
		10,
		1
	);
}

// disable Search.
if ( in_array( 'disable_search', $this->settings, true ) ) {
	/**
	 * Removes search.
	 *
	 * @param WP_Query $query The query object that parsed the query.
	 */
	add_action(
		'parse_query',
		function ( $query, $error = true ) {
			if ( is_search() ) {
				$query->is_search       = false;
				$query->query_vars['s'] = false;
				$query->query['s']      = false;
				if ( true === $error ) {
					$query->is_404 = true;
				}
			}
		}
	);
	add_filter(
		'get_search_form',
		// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		function ( $a ) {
			return null;
		}
	);
	add_action(
		'widgets_init',
		function () {
			unregister_widget( 'WP_Widget_Search' );
		}
	);
}

// Show post, term and user IDs in admin list tables.
if ( in_array( 'show_admin_ids', $this->settings, true ) && is_admin() ) {
	/**
	 * Inserts an ID column after the primary list column.
	 *
	 * @param array $columns List table columns.
	 * @return array
	 */
	function machete_add_admin_id_column( $columns ) {
		$new_columns = array();
		$inserted    = false;

		foreach ( $columns as $key => $label ) {
			$new_columns[ $key ] = $label;
			if ( ! $inserted && in_array( $key, array( 'title', 'name', 'username', 'cb' ), true ) ) {
				$new_columns['machete_id'] = __( 'ID', 'machete' );
				$inserted                  = true;
			}
		}

		if ( ! $inserted ) {
			$new_columns['machete_id'] = __( 'ID', 'machete' );
		}

		return $new_columns;
	}

	/**
	 * Renders the post ID column value.
	 *
	 * @param string $column_name Column slug.
	 * @param int    $post_id     Post ID.
	 */
	function machete_render_admin_post_id_column( $column_name, $post_id ) {
		if ( 'machete_id' === $column_name ) {
			echo esc_html( (string) $post_id );
		}
	}

	/**
	 * Renders the term ID column value.
	 *
	 * @param string $string      Column output.
	 * @param string $column_name Column slug.
	 * @param int    $term_id     Term ID.
	 * @return string
	 */
	function machete_render_admin_term_id_column( $string, $column_name, $term_id ) {
		if ( 'machete_id' === $column_name ) {
			return esc_html( (string) $term_id );
		}
		return $string;
	}

	/**
	 * Renders the user ID column value.
	 *
	 * @param string $value       Column output.
	 * @param string $column_name Column slug.
	 * @param int    $user_id     User ID.
	 * @return string
	 */
	function machete_render_admin_user_id_column( $value, $column_name, $user_id ) {
		if ( 'machete_id' === $column_name ) {
			return esc_html( (string) $user_id );
		}
		return $value;
	}

	add_action(
		'admin_init',
		function () {
			$post_types = get_post_types(
				array(
					'show_ui' => true,
				),
				'names'
			);

			foreach ( $post_types as $post_type ) {
				add_filter( "manage_{$post_type}_posts_columns", 'machete_add_admin_id_column' );
				add_action( "manage_{$post_type}_posts_custom_column", 'machete_render_admin_post_id_column', 10, 2 );
			}

			$taxonomies = get_taxonomies(
				array(
					'show_ui' => true,
				),
				'names'
			);

			foreach ( $taxonomies as $taxonomy ) {
				add_filter( "manage_edit-{$taxonomy}_columns", 'machete_add_admin_id_column' );
				add_filter( "manage_{$taxonomy}_custom_column", 'machete_render_admin_term_id_column', 10, 3 );
			}
		}
	);

	add_filter( 'manage_users_columns', 'machete_add_admin_id_column' );
	add_filter( 'manage_users_custom_column', 'machete_render_admin_user_id_column', 10, 3 );
}

// Hide the admin bar on the frontend for non-administrators.
if ( in_array( 'disable_admin_bar_frontend', $this->settings, true ) && ! is_admin() ) {
	add_action(
		'after_setup_theme',
		function () {
			if ( current_user_can( 'manage_options' ) ) {
				return;
			}
			show_admin_bar( false );
		}
	);
}
