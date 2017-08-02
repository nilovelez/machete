<?php
if ( ! defined( 'ABSPATH' ) ) exit;




function machete_optimize($settings){

  /*
  $settings = array(
  'rsd_link',
  'wlwmanifest',
  'feed_links',
  'next_prev',
  'shortlink',
  'wp_generator',
  'ver',
  'emojicons',
  'wp_resource_hints',
  'recentcomments',
  'xmlrpc',


  'json_api',
  'oembed_scripts',
  'slow_heartbeat',
  'comments_reply_feature',
  'empty_trash_soon',

  );*/

  if (empty($settings)){
    return false;
  }

  /********* HEADER CLEANUP ***********/


  // remove really simple discovery link
  if (in_array('rsd_link',$settings)) {
    remove_action('wp_head', 'rsd_link');
  }

  // remove wlwmanifest.xml (needed to support windows live writer)
  if (in_array('wlwmanifest',$settings)) {
    remove_action('wp_head', 'wlwmanifest_link'); 
  }

  // remove rss feed and exta feed links
  // (make sure you add them in yourself if you are using as RSS service
  if (in_array('feed_links',$settings)) {
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
  }

  // remove the next and previous post links
  if (in_array('next_prev',$settings)) {
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  }

  // remove the shortlink url from header
  if (in_array('shortlink',$settings)) {
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
  }

  // remove wordpress generator version
  if (in_array('wp_generator',$settings)) {
    /*
    function wp_remove_version() {
      return '';
    }
    add_filter('the_generator', 'wp_remove_version');
    */
    remove_action( 'wp_head' , 'wp_generator' );
  }

  // remove ver= after style and script links
  if (in_array('ver',$settings)) {
    add_filter( 'style_loader_src', 'machete_remove_ver_css_js', 9999 );
    add_filter( 'script_loader_src', 'machete_remove_ver_css_js', 9999 );
  }

  // remove emoji styles and script from header
  if (in_array('emojicons',$settings)) {

    // disabled options are called at the end of machete_admin.php

    //remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    //remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    //add_filter( 'tiny_mce_plugins', 'machete_disable_emojicons_tinymce' );
  }

  if (in_array('json_api',$settings)) {
    // disable json api and remove link from header


    // remove json_api
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    add_filter( 'embed_oembed_discover', '__return_false' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );


    // disable json_api
    add_filter('json_enabled', '__return_false');
    add_filter('json_jsonp_enabled', '__return_false');
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');

  }

  // remove s.w.org dns-prefetch 
  if (in_array('wp_resource_hints',$settings)) {
    remove_action( 'wp_head', 'wp_resource_hints', 2 );
  }



  if (isset($settings['widgets']) && is_array( $settings['widgets'] )) {
    // unregister widgets
    add_action('widgets_init', 'machete_unregister_default_widgets', 11);
  }

  if (in_array('recentcomments',$settings)) {
    add_action( 'widgets_init', function(){
      // Remove the annoying:
      // <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style> added in the header
      global $wp_widget_factory;
      remove_action( 
          'wp_head', 
          array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) 
      );
    });
  }

  if (in_array('capital_P_dangit',$settings)) {
    foreach ( array( 'the_content', 'the_title', 'wp_title', 'comment_text' ) as $filter ) {
      $priority = has_filter( $filter, 'capital_P_dangit' );
      if ( $priority !== FALSE ) {
        remove_filter( $filter, 'capital_P_dangit', $priority );
      }
    }
  }

  /********* OPTIMIZATION TWEAKS ***********/
  if (in_array('jquery-migrate',$settings)) {
    add_filter( 'wp_default_scripts', 'machete_dequeue_jquery_migrate' );
    function machete_dequeue_jquery_migrate( $scripts){
      if ( !empty( $scripts->registered['jquery'] ) ) {
        $jquery_dependencies = $scripts->registered['jquery']->deps;
        $scripts->registered['jquery']->deps = array_diff( $jquery_dependencies, array( 'jquery-migrate' ) );
      }
    }
  }

  // pdf_thumbnails está en machete_admin.php
  // limit_revisions está en machete_admin.php

  
  if (in_array('oembed_scripts',$settings)) {
    //Remove oEmbed Scripts
    //Since WordPress 4.4, oEmbed is installed and available by default. WordPress assumes you’ll want to easily embed media like tweets and YouTube videos so includes the scripts as standard. If you don’t need oEmbed, you can remove it
    wp_deregister_script('wp-embed');
  }
  

  // slow_heartbeat está en machete_admin.php

  if (in_array('comments_reply_feature',$settings)) {
    //Only load the comment-reply.js when needed
    function machete_queue_comment_reply(){
        if (is_singular() && (get_option('thread_comments') == 1) && comments_open() && have_comments()) {
          wp_enqueue_script('comment-reply');
        } else {
          wp_dequeue_script('comment-reply');
        }
    }
    add_action('wp_print_scripts', 'machete_queue_comment_reply', 100);
  }

  //empty_trash_soon está en machete_admin.php



}

function machete_remove_ver_css_js( $src ) {
  if ( strpos( $src, 'ver=' ) ) {
    $src = remove_query_arg( 'ver', $src );
  }
  return $src;
}

if ( ! function_exists( 'machete_disable_emojicons_tinymce' ) ) :
function machete_disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  }
  return array();
}
endif;

function machete_unregister_default_widgets() {
  global $settings;
  foreach( $settings['widgets'] as $widget ) {
    unregister_widget( $widget );
  }
}

/*
if (in_array('xmlrpc',$settings)) {
  add_filter( 'xmlrpc_enabled', '__return_false' );
}*/


/*
//add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', function( $methods ) {
   //unset( $methods['pingback.ping'] );
   //return $methods;
   return array();
} );

# Block WordPress xmlrpc.php requests
<Files xmlrpc.php>
order deny,allow
deny from all
</Files>
*/

if(($machete_cleanup_settings = get_option('machete_cleanup_settings')) && (count($machete_cleanup_settings) > 0)){
  machete_optimize($machete_cleanup_settings);
}