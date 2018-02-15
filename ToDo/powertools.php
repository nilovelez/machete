<?php





// flush rewrite Rules
flush_rewrite_rules();



// default to HTML editor
add_filter('wp_default_editor', create_function('', 'return "html";'));


function no_wordpress_errors(){
return 'Something is wrong!';
}
add_filter( 'login_errors', 'no_wordpress_errors' );


remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );


function my_myme_types($mime_types){
$mime_types['svg'] = 'image/svg+xml';
return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);


// remove search
function wpb_filter_query( $query, $error = true ) {
if ( is_search() ) {
$query->is_search = false;
$query->query_vars[s] = false;
$query->query[s] = false;
if ( $error == true )
$query->is_404 = true;
}
}
add_action( 'parse_query', 'wpb_filter_query' );
add_filter( 'get_search_form', create_function( '$a', "return null;" ) );
function remove_search_widget() {
    unregister_widget('WP_Widget_Search');
 
add_action( 'widgets_init', 'remove_search_widget' );



//default gravatar
add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
function wpb_new_gravatar ($avatar_defaults) {
$myavatar = 'http://example.com/wp-content/uploads/2017/01/wpb-default-gravatar.png';
$avatar_defaults[$myavatar] = "Default Gravatar";
return $avatar_defaults;
}