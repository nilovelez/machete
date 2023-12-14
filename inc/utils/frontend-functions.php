<?php
/**
 * Machete Analytics&Code Module actions specific to the front-end
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( file_exists( MACHETE_DATA_PATH . 'header.html' ) ) {
	add_action(
		'wp_head',
		array( $this, 'read_header_html' ),
		10001
	);
}


if (
	( 'none' !== $this->settings['tracking_format'] ) &&
	( ! empty( $this->settings['tracking_filename'] ) ) &&
	( file_exists( MACHETE_DATA_PATH . $this->settings['tracking_filename'] ) )
	) {
	$this->enqueue_tracking_if_no_cookies();
}

if ( file_exists( MACHETE_DATA_PATH . 'custom.css' ) ) {
	wp_enqueue_style(
		'machete-custom',
		MACHETE_DATA_URL . 'custom.css',
		array(),
		MACHETE_VERSION
	);
}

if ( file_exists( MACHETE_DATA_PATH . 'footer.html' ) ) {
	add_action(
		'wp_footer',
		array( $this, 'read_footer_html' ),
		10001
	);
}

if ( file_exists( MACHETE_DATA_PATH . 'body.html' ) ) {

	if ( 'auto' === $this->settings['alfonso_content_injection_method'] ) {
		/**
		 * Automatic body injection.
		 * Uses a work-around to add code just after the opening body tag
		 */
		add_filter(
			'body_class',
			array( $this, 'inject_body_html' ),
			10001
		);
	} elseif ( 'wp_body_open' === $this->settings['alfonso_content_injection_method'] ) {
		/**
		 * Body injection using wp_body hook
		 */
		add_action(
			'wp_body_open',
			array( $this, 'read_body_html' ),
			1
		);
	} else {
		/**
		 * Manual body injection
		 */
		function machete_custom_body_content() {
			global $machete;
			$machete->modules['utils']->read_body_html();
		}
	}
}

if ( ! function_exists( 'machete_custom_body_content' ) ) {
	/**
	 * Defines an empty function as fallback to prevent errors
	 */
	function machete_custom_body_content() {}
}
