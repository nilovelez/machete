<?php
/**
 * Optimization actions for the cleanup module.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
HEADER CLEANUP
rsd_link
wlwmanifest
feed_links
next_prev
shortlink
wp_generator
ver
recentcomments
wp_resource_hints

FEATURE CLEANUP
emojicons
pdf_thumbnails
limit_revisions
slow_heartbeat
comments_reply_feature
empty_trash_soon
capital_P_dangit
disable_editor
medium_large_size
comment_autolinks

OPTIMIZATION TWEAKS
json_api
xmlrpc
jquery-migrate
oembed_scripts
wpcf7_refill
jpeg_quality

*/


/*
******* HEADER CLEANUP **********
*/

// remove really simple discovery link.
if ( in_array( 'rsd_link', $this->settings, true ) && ! is_admin() ) {
	remove_action( 'wp_head', 'rsd_link' );
}

// remove wlwmanifest.xml (needed to support windows live writer).
if ( in_array( 'wlwmanifest', $this->settings, true ) && ! is_admin() ) {
	remove_action( 'wp_head', 'wlwmanifest_link' );
}

// remove rss feed and exta feed links
// (make sure you add them in yourself if you are using as RSS service.
if ( in_array( 'feed_links', $this->settings, true ) && ! is_admin() ) {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
}

// remove the next and previous post links.
if ( in_array( 'next_prev', $this->settings, true ) && ! is_admin() ) {
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
}

// remove the shortlink url from header.
if ( in_array( 'shortlink', $this->settings, true ) && ! is_admin() ) {
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
	remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
}

// remove wordpress generator version,
if ( in_array( 'wp_generator', $this->settings, true ) && ! is_admin() ) {
	remove_action( 'wp_head', 'wp_generator' );
}

// remove ver= after style and script links.
if ( in_array( 'ver', $this->settings, true ) && ! is_admin() ) {
	add_filter( 'style_loader_src', function ( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}, 9999 );
	add_filter( 'script_loader_src', function ( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}, 9999 );
}

if ( in_array( 'recentcomments', $this->settings, true ) && ! is_admin() ) {
	// Remove the annoying:
	// <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
	// added in the header.
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}

// remove s.w.org dns-prefetch.
if ( in_array( 'wp_resource_hints', $this->settings, true ) && ! is_admin() ) {
	remove_action( 'wp_head', 'wp_resource_hints', 2 );
}

/*
******** FEATURE CLEANUP **********
*/

// remove emoji styles and script from header.
if ( in_array( 'emojicons', $this->settings, true ) ) {
	if ( is_admin() ) {
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', function( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			}
			return array();
		});
	} else {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	}
}

if ( in_array( 'pdf_thumbnails', $this->settings, true ) && is_admin() ) {
	add_filter( 'fallback_intermediate_image_sizes', function() {
		return array();
	});
}

if ( in_array( 'limit_revisions', $this->settings, true ) && is_admin() ) {
	if ( defined( 'WP_POST_REVISIONS' ) && ( WP_POST_REVISIONS != false ) ) {
		add_filter( 'wp_revisions_to_keep', function( $num, $post ) {
			return 5;
		}, 10, 2 );
	}
}

//Slow default heartbeat
if ( in_array( 'slow_heartbeat', $this->settings, true ) && is_admin() ) {
	add_filter( 'heartbeat_settings', function ( $settings ) {
		$settings['interval'] = 60;
		return $settings;
	});
}

if ( in_array( 'comments_reply_feature', $this->settings, true ) && ! is_admin() ) {
	//Only load the comment-reply.js when needed  
	add_action('wp_print_scripts', function() {
		if ( is_singular() && ( get_option( 'thread_comments' ) == 1 ) && comments_open() && have_comments() ) {
			wp_enqueue_script( 'comment-reply' );
		} else {
			wp_dequeue_script( 'comment-reply' );
		}
	}, 100);
}

//Empty trash sooner
if ( in_array( 'empty_trash_soon', $this->settings, true ) ) {
	if ( ! defined( 'EMPTY_TRASH_DAYS' ) ) {
		define( 'EMPTY_TRASH_DAYS', 7 );
	}
}

// remove the capital_P_dangit filter
if ( in_array( 'capital_P_dangit', $this->settings, true ) ) {
	foreach ( array( 'the_content', 'the_title', 'wp_title', 'comment_text' ) as $filter ) {
		$priority = has_filter( $filter, 'capital_P_dangit' );
		if ( false !== $priority ) {
			remove_filter( $filter, 'capital_P_dangit', $priority );
		}
	}
}

if ( in_array( 'disable_editor', $this->settings, true ) ) {
	if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
		define( 'DISALLOW_FILE_EDIT', true );
	}
}

if ( in_array( 'medium_large_size', $this->settings, true ) ) {
	add_image_size( 'medium_large', 0, 0 );
}

if ( in_array( 'comment_autolinks', $this->settings, true ) && ! is_admin() ) {
	remove_filter( 'comment_text', 'make_clickable', 9 );
}

/*
******** OPTIMIZATION TWEAKS **********
*/

// disable json api and remove link from header.
if ( in_array( 'json_api', $this->settings, true ) ) {

	if ( ! is_admin() ) {
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	}

	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	add_filter( 'embed_oembed_discover', '__return_false' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );


	// disable json_api.
	add_filter( 'json_enabled', '__return_false' );
	add_filter( 'json_jsonp_enabled', '__return_false' );
	// Notice: rest_enabled is deprecated since version 4.7.0! Use rest_authentication_errors instead. The REST API can no longer be completely disabled, the rest_authentication_errors can be used to restrict access to the API
	//add_filter( 'rest_enabled', '__return_false' );
	add_filter( 'rest_jsonp_enabled', '__return_false' );

	add_filter( 'rest_authentication_errors', function( $access ) {
		return new WP_Error(
			'rest_disabled',
			__( 'The REST API an this site has been disabled.' ) . ' Machete don\'t REST',
			array(
				'status' => rest_authorization_required_code(),
			)
		);
	});

}

if ( in_array( 'xmlrpc', $this->settings, true ) ) {
	// Remove REST API info from head and headers.
	add_filter( 'xmlrpc_enabled', '__return_false' );

	// Hide xmlrpc.php in HTTP response headers.
	add_filter( 'wp_headers', function( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	});

	remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
	add_filter( 'xmlrpc_enabled', '__return_false' );
	add_filter( 'xmlrpc_methods', function( $methods ) {
		unset( $methods['pingback.ping'] );
		return $methods;
	});
}

if ( in_array( 'jquery-migrate', $this->settings, true ) ) {

	global $wp_scripts;

	if ( ! empty( $wp_scripts->registered['jquery'] ) ) {
    $wp_scripts->registered['jquery']->deps = array_diff(
        $wp_scripts->registered['jquery']->deps,
        array( 'jquery-migrate' )
    );
  }
}

if ( in_array( 'oembed_scripts',$this->settings, true ) && ! is_admin() ) {
  //Remove oEmbed Scripts
  //Since WordPress 4.4, oEmbed is installed and available by default. WordPress assumes you’ll want to easily embed media like tweets and YouTube videos so includes the scripts as standard. If you don’t need oEmbed, you can remove it
	wp_deregister_script( 'wp-embed' );
}

if ( in_array( 'wpcf7_refill',$this->settings, true ) && ! is_admin() ) {
  add_action( 'wp_enqueue_scripts', function(){
    wp_localize_script( 'contact-form-7', 'wpcf7', array(
      'apiSettings' => array(
        'root' => esc_url_raw( rest_url( 'contact-form-7/v1' ) ),
        'namespace' => 'contact-form-7/v1',
      ),
      'jqueryUi' => 1
    ));
  }, 10);
}
if ( in_array( 'jpeg_quality', $this->settings, true ) ) {
	add_filter( 'jpeg_quality', function( $arg ) {
		return 72;
	});
}
