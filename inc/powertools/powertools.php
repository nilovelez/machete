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
widget_oembed
rss_thumbnails
page_excerpts
save_with_keyboard
move_scripts_footer
defer_all_scripts
disable_feeds
enable_svg
*/

// enable shortcodes in widgets.
if ( in_array( 'widget_shortcodes', $this->settings, true ) && ! is_admin() ) {
	add_filter( 'widget_text', 'do_shortcode', 11 );
}

// enable oembed in text widgets.
if ( in_array( 'widget_oembed', $this->settings, true ) ) {
	global $wp_embed;
	add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
	add_filter( 'widget_text', array( $wp_embed, 'autoembed' ), 8 );
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

// save with keyboard.
if ( in_array( 'save_with_keyboard', $this->settings, true ) && is_admin() ) {
	add_action(
		'admin_enqueue_scripts',
		function() {
			wp_register_script(
				'machete_save_with_keyboard',
				MACHETE_BASE_URL . 'vendor/save-with-keyboard/saveWithKeyboard.js',
				array( 'jquery' ),
				MACHETE_VERSION,
				false
			);
			$translation_array = array(
				'save_button_tooltip'    => __( 'Ctrl+S or Cmd+S to click', 'machete' ),
				'preview_button_tooltip' => __( 'Ctrl+P or Cmd+P to preview', 'machete' ),
			);
			wp_localize_script( 'machete_save_with_keyboard', 'l10n_strings', $translation_array );
			wp_enqueue_script( 'machete_save_with_keyboard' );
		}
	);
}

// Script to Move JavaScript from the Head to the Footer.
if ( in_array( 'move_scripts_footer', $this->settings, true ) ) {
	add_action(
		'wp_enqueue_scripts',
		function() {
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
		function( $tag ) {
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
		wp_die( sprintf( wp_kses( __( 'No feed available, please visit our <a href="%s">homepage</a>!', 'machete' ), $link_only ), esc_url( get_bloginfo( 'url' ) ) ) );
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
		function( $upload_mimes ) {
			$upload_mimes['svg']  = 'image/svg+xml';
			$upload_mimes['svgz'] = 'image/svg+xml';
			return $upload_mimes;
		},
		10,
		1
	);
}
