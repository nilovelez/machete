<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

function content_clone(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'machete_clone' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}

 
	/*
	 * id do Artigo/Página original
	 */
	$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
	
	check_admin_referer( 'machete_clone_' . $post_id );
	
	// gets original post
	$post = get_post( $post_id );
 
	// sets current user as the author of the cloned post
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
 
	// copy the contents from the post
	if (isset( $post ) && $post != null) {
 
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title .__( '-copy','machete'),
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);
 
		// inserts the new post via wp_insert_post()
		$new_post_id = wp_insert_post(wp_slash($args));
 
		// gets the taxonomies from the post to clone
		$taxonomies = get_object_taxonomies($post->post_type); // retorna um array das taxonomias
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
 
		
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
		
		// redirect to the post editor
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Couldn\'t find any post/page with this id: ' . $post_id);
	}
}
add_action( 'admin_action_machete_clone', 'content_clone' );
 
// Adds the "clone" link to the post and page lists
function content_clone_link( $actions, $post ) {
	$notify_url = wp_nonce_url( admin_url( "admin.php?action=machete_clone&amp;post=" . absint( $post->ID ) ), 'machete_clone_' . $post->ID );

	$actions['duplicate'] = '<a href="'.$notify_url.'" title="'.__( 'Clone this!', 'machete' ).'" rel="permalink">'.__( 'Duplicate', 'machete' ).'</a>';
	
	return $actions;
}

function machete_clone_custom_button(){
	global $post;
	
	if ( 'product' == $post->post_type ) {
		return;
	}

	if ( isset( $_GET['post'] ) ) {
		
		$notify_url = wp_nonce_url( admin_url( "admin.php?action=machete_clone&amp;post=" . absint( $_GET['post'] ) ), 'machete_clone_' . $_GET['post'] );

		//$notify_url = 'admin.php?action=machete_clone&amp;post=' . $post->ID;
		?>
		<div id="duplicate-action"><a class="submitduplicate duplication" href="<?php echo esc_url( $notify_url ); ?>"><?php _e( 'Copy to a new draft', 'machete' ); ?></a></div>
		<?php
	}

}
if (current_user_can('edit_posts')) {
	add_filter( 'post_row_actions', 'content_clone_link', 10, 2 ); // Posts
	add_filter( 'page_row_actions', 'content_clone_link', 10, 2 ); // Pages
	add_action( 'post_submitbox_start', 'machete_clone_custom_button');
	//add_action( 'wp_before_admin_bar_render', 'duplicate_page_admin_bar_link');
}


/*

$actions['duplicate'] = '<a href="' . wp_nonce_url( admin_url( 'edit.php?post_type=product&action=duplicate_product&amp;post=' . $post->ID ), 'woocommerce-duplicate-product_' . $post->ID ) . '" aria-label="' . esc_attr__( 'Make a duplicate from this product', 'woocommerce' )
			. '" rel="permalink">' . __( 'Duplicate', 'woocommerce' ) . '</a>';

			*/