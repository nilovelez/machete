<?php
/**
 * Functions machete clone module functions only used in the backend
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
}
/**
 * Main post/page clone function
 */
function machete_content_clone() {
	global $wpdb;

	// Gets original post id.
	$post_id = filter_input( INPUT_GET, 'post' );
	if ( null === $post_id ) {
		$post_id = filter_input( INPUT_POST, 'post' );
		if ( null === $post_id ) {
			wp_die( 'No post to duplicate has been supplied!' );
		}
	}

	check_admin_referer( 'machete_clone_' . $post_id );

	// Gets original post.
	$post = get_post( $post_id );
	if ( null === $post ) {
		wp_die( 'Couldn\'t find any post/page with this id: ' . esc_html( $post_id ) );
	}

	// Copies the contents from the post.
	$args = array(
		'comment_status' => $post->comment_status,
		'ping_status'    => $post->ping_status,
		'post_author'    => get_current_user_id(),
		'post_content'   => $post->post_content,
		'post_excerpt'   => $post->post_excerpt,
		'post_name'      => $post->post_name,
		'post_parent'    => $post->post_parent,
		'post_password'  => $post->post_password,
		'post_status'    => 'draft',
		'post_title'     => machete_content_clone_new_title( $post->post_title ),
		'post_type'      => $post->post_type,
		'to_ping'        => $post->to_ping,
		'menu_order'     => $post->menu_order,

	);

	// Inserts the new post via wp_insert_post().
	$new_post_id = wp_insert_post( $args );
	// Gets the taxonomies from the post to clone.
	$taxonomies = get_object_taxonomies( $post->post_type ); // Returns a taxonomy array.
	foreach ( $taxonomies as $taxonomy ) {
		$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
		wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
	}

	// Gets the post metas from the old post and adds them to the new.
	$post_metas = get_post_meta( $post_id );
	foreach ( $post_metas as $post_meta => $data ) {
		// Ignore edit flag metas.
		if ( in_array( $post_meta, array( '_edit_lock', '_edit_last' ), true ) ) {
			continue;
		}
		// Ignore oembed cache time metas.
		if ( '_oembed_time_' === substr( $post_meta, 0, 13 ) ) {
			continue;
		}
		foreach ( $data as $meta_value ) {
			add_post_meta( $new_post_id, $post_meta, maybe_unserialize( $meta_value ) );
		}
	}

	// redirect to the post editor.
	wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
	exit;
}
add_action( 'admin_action_machete_clone', 'machete_content_clone' );


/**
 * Renames the post for the clone
 *
 * @param string $title old post title
 */
function machete_content_clone_new_title( $title ) {

	$copy_text = __( 'copy', 'machete' );

	if ( preg_match( '/^.* \b' . $copy_text . '\b$/', $title ) ) {
		// If title ends in " copy" changes it to " copy 2"
		$title .= ' 2';
	} elseif ( preg_match( '/^(.* \b' . $copy_text . ' )(\d+)\b$/', $title, $matches ) ) {
		// If title ends in " copy n", changes it to " copy n+1"
		$next_number = intval( $matches[2] ) + 1;
		$title       = $matches[1] . $next_number;
	} else {
		// If title doesn't end in " copy" or " copy n", add " copy" to the title"
		$title .= ' ' . $copy_text;
	}
	return $title;
}

/**
 * Adds the "clone" link to the post and page lists.
 *
 * @param array  $actions post/page actions array.
 * @param object $post    reference to the current row post.
 */
function machete_clone_link( $actions, $post ) {
	$notify_url = wp_nonce_url( admin_url( 'admin.php?action=machete_clone&amp;post=' . absint( $post->ID ) ), 'machete_clone_' . $post->ID );

	$actions['duplicate'] = '<a href="' . $notify_url . '" title="' . __( 'Clone this!', 'machete' ) . '" rel="permalink">' . __( 'Duplicate', 'machete' ) . '</a>';
	return $actions;
}

/**
 * Adds the "copy to a new draft" button to the post/page editor actions
 */
function machete_clone_button() {
	global $post;

	if ( 'product' === $post->post_type ) {
		return;
	}

	$post_id = filter_input( INPUT_GET, 'post' );

	if ( null !== $post_id ) {

		$notify_url = wp_nonce_url( admin_url( 'admin.php?action=machete_clone&amp;post=' . absint( $post_id ) ), 'machete_clone_' . $post_id );

		?>
		<div id="duplicate-action"><a class="submitduplicate duplication" href="<?php echo esc_url( $notify_url ); ?>"><?php esc_html_e( 'Copy to a new draft', 'machete' ); ?></a></div>
		<?php
	}
}

/*
 * Add the duplicate link to edit screen - gutenberg
 */
function machete_clone_button_guten() {
	global $post;
	if ( $post ) {

		$notify_url = wp_nonce_url( admin_url( 'admin.php?action=machete_clone&amp;post=' . absint( $post->ID ) ), 'machete_clone_' . $post->ID );

		wp_enqueue_style(
			'machete_clone_style',
			MACHETE_BASE_URL . 'inc/clone/css/editor-style.css',
			array(),
			MACHETE_VERSION
		);
		wp_register_script(
			'machete_clone_script',
			MACHETE_BASE_URL . 'inc/clone/js/editor-script.js',
			array(
				'wp-edit-post',
				'wp-plugins',
				'wp-i18n',
				'wp-element',
			),
			MACHETE_VERSION,
			array(
				'in_footer' => false,
			)
		);
		wp_localize_script(
			'machete_clone_script',
			'machete_params',
			array(
				'machete_clone_nonce_url'    => $notify_url,
				'machete_clone_button_text'  => __( 'Clone this post', 'machete' ),
				'machete_clone_button_title' => __( 'Copy to a new draft', 'machete' ),
			)
		);
		wp_enqueue_script( 'machete_clone_script' );
	}
}

if ( current_user_can( 'edit_posts' ) ) {
	add_filter( 'post_row_actions', 'machete_clone_link', 10, 2 ); // Posts.
	add_filter( 'page_row_actions', 'machete_clone_link', 10, 2 ); // Pages.
	add_action( 'admin_head', 'machete_clone_button_guten' );
	add_action( 'post_submitbox_start', 'machete_clone_button' );
}
