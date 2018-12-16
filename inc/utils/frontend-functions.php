<?php
/**
 * Machete Analytics&Code Module actions especific to the front-end
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
		function() {
			$this->readfile( MACHETE_DATA_PATH . 'header.html' );
		}
	);

	if ( $this->settings['track_wpcf7'] ) {
		add_filter(
			'wpcf7_contact_form_properties',
			function( $properties, $wpcf7 ) {
				global $machete;
				$machete->modules['utils']->last_wpcf7 = $wpcf7->title();
				add_filter(
					'wpcf7_form_hidden_fields',
					function( $hidden_fields ) {
						global $machete;
						$hidden_fields['machete_wpcf7_title'] = $machete->modules['utils']->last_wpcf7;
						return $hidden_fields;
					},
					10,
					1
				);
				return $properties;
			},
			10,
			2
		);

		wp_enqueue_script(
			'machete_track_wpcf7',
			plugins_url( 'js/track_wpcf7.min.js', __FILE__ ),
			array(),
			MACHETE_VERSION,
			true
		);
	}
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
		function() {
			$this->readfile( MACHETE_DATA_PATH . 'footer.html' );
		}
	);
}

if ( file_exists( MACHETE_DATA_PATH . 'body.html' ) ) {
	if (
		! empty( $this->settings['alfonso_content_injection_method'] ) &&
		( 'auto' === $this->settings['alfonso_content_injection_method'] )
	) {
		/**
		 * Automatic body injection.
		 * Uses a work-around to add code just after the opening body tag
		 */
		add_filter(
			'body_class',
			function( $classes ) {
				ob_start();
				require $this->get_contents( MACHETE_DATA_PATH . 'body.html' );
				$alfonso_content = ob_get_clean();
				$classes[]       = '">' . $alfonso_content . '<br style="display: none';
				return $classes;
			},
			10001
		);

		/**
		 * Disables manual body injection to prevent duplicate insertion.
		 */
		function machete_custom_body_content() {
			echo '';
		}
	} else {

		/**
		 * Manual body injection
		 */
		function machete_custom_body_content() {
			$this->readfile( MACHETE_DATA_PATH . 'body.html' );
		}
	}
} else {
	/**
	 * Disables manual body injection if custom body content is empty.
	 */
	function machete_custom_body_content() {
		echo '';
	}
}
