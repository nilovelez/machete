<?php
/**
 * Snippets that could be added to Machete PowerTools

 * @package WordPress
 * @subpackage Machete
 */

// phpcs:disable
/**
 * Change default WordPress editor.
 *
 * @return (string) Either 'tinymce', or 'html', or 'test'
 */
add_filter( 'wp_default_editor', function() {
	return 'html';
});

/**
 * Disable WordPress descriptive login errors
 *
 * @return (string) text to return on error
 */
add_filter( 'login_errors', function () {
	return 'Something is wrong!';
});

/**
 * Disable option to login using email
 */
remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );


/**
 * Removes search.
 *
 * @param WP_Query $query The query object that parsed the query.
 */
add_action( 'parse_query', function( $query, $error = true ) {
	if ( is_search() ) {
		$query->is_search       = false;
		$query->query_vars[ s ] = false;
		$query->query[ s ]      = false;
		if ( true === $error ) {
			$query->is_404 = true;
		}
	}
});
add_filter( 'get_search_form', function( $a ) {
	return null;
});
add_action( 'widgets_init', function() {
	unregister_widget( 'WP_Widget_Search' );
});


/**
 * Redefines default gravatar
 *
 * @param array $avatar_defaults The default avatar array
 */
add_filter( 'avatar_defaults', function ( $avatar_defaults ) {
	$myavatar                     = 'http://example.com/wp-content/uploads/2017/01/wpb-default-gravatar.png';
	$avatar_defaults[ $myavatar ] = 'Default Gravatar';
	return $avatar_defaults;
});
