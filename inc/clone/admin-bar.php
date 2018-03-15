<?php
/**
 * Adds the clone button to the admin bar when needed.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds link to admin bar
 */
function machete_clone_admin_bar_link() {
	if ( ! is_single() && ( null === filter_input( INPUT_GET, 'post' ) ) ) {
		return;
	}

	$post = get_post();

	if ( ( null === $post ) || ( 'product' === $post->post_type ) ) {
		return;
	}

	$post_type      = get_post_type_object( $post->post_type );
	$post_type_name = $post_type->labels->singular_name;

	global $wp_admin_bar;

	$wp_admin_bar->add_menu(
		array(
			'id'    => 'machete-clone',
			/* translators: %s: singular post type name. */
			'title' => '<span class="ab-icon"></span><span class="ab-label">' . esc_html( sprintf( __( 'Clone %s', 'machete' ), $post_type_name ) ) . '</span>',
			'href'  => wp_nonce_url( admin_url( 'admin.php?action=machete_clone&amp;post=' . absint( $post->ID ) ), 'machete_clone_' . absint( $post->ID ) ),
			'meta'  => array(
				/* translators: %s: singular post type name. */
				'title'   => esc_html( sprintf( __( 'Copy this %s to a new draft', 'machete' ), strtolower( $post_type_name ) ) ),
				'class'   => 'machete_clone_admin_link',
				'_target' => 'self',
			),
		)
	);
}
add_action( 'wp_before_admin_bar_render', 'machete_clone_admin_bar_link' );

/**
 * Enqueues Clone button admin bar styles
 */
function machete_clone_admin_bar_scripts() {
	wp_register_style(
		'machete-clone-styles',
		MACHETE_BASE_URL . 'css/clone-admin-bar.css',
		false
	);
	wp_enqueue_style( 'machete-clone-styles' );
}
add_action( 'wp_enqueue_scripts', 'machete_clone_admin_bar_scripts' );
add_action( 'admin_enqueue_scripts', 'machete_clone_admin_bar_scripts' );
