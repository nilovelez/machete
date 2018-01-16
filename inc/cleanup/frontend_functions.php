<?php
if ( ! defined( 'ABSPATH' ) ) exit;


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

'wpcf7_refill'

);*/


/********* HEADER CLEANUP ***********/

// remove really simple discovery link
if (in_array('rsd_link',$this->settings)) {
  remove_action('wp_head', 'rsd_link');
}

// remove wlwmanifest.xml (needed to support windows live writer)
if (in_array('wlwmanifest',$this->settings)) {
  remove_action('wp_head', 'wlwmanifest_link'); 
}

// remove rss feed and exta feed links
// (make sure you add them in yourself if you are using as RSS service
if (in_array('feed_links',$this->settings)) {
  remove_action('wp_head', 'feed_links', 2);
  remove_action('wp_head', 'feed_links_extra', 3);
}

// remove the next and previous post links
if (in_array('next_prev',$this->settings)) {
  remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
}

// remove the shortlink url from header
if (in_array('shortlink',$this->settings)) {
  remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
  remove_action('template_redirect', 'wp_shortlink_header', 11, 0);
}

// remove wordpress generator version
if (in_array('wp_generator',$this->settings)) {
  //add_filter('the_generator', function(){ return '' });
  remove_action( 'wp_head' , 'wp_generator' );
}

// remove ver= after style and script links
if (in_array('ver',$this->settings)) {
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

// remove emoji styles and script from header
if (in_array('emojicons',$this->settings)) {

  // disabled options are called at the end of machete_admin.php
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
}

if (in_array('json_api',$this->settings)) {
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
if (in_array('wp_resource_hints',$this->settings)) {
  remove_action( 'wp_head', 'wp_resource_hints', 2 );
}

if (in_array('recentcomments',$this->settings)) {
  // Remove the annoying:
  // <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
  // added in the header
  add_filter('show_recent_comments_widget_style', '__return_false');
}

if (in_array('capital_P_dangit',$this->settings)) {
  foreach ( array( 'the_content', 'the_title', 'wp_title', 'comment_text' ) as $filter ) {
    $priority = has_filter( $filter, 'capital_P_dangit' );
    if ( $priority !== FALSE ) {
      remove_filter( $filter, 'capital_P_dangit', $priority );
    }
  }
}


/********* OPTIMIZATION TWEAKS ***********/
if (in_array('jquery-migrate',$this->settings)) {
  
  global $wp_scripts;

  if ( !empty( $wp_scripts->registered['jquery'] ) ) {
    $wp_scripts->registered['jquery']->deps = array_diff(
        $wp_scripts->registered['jquery']->deps,
        array( 'jquery-migrate' )
    );
  }
}

// pdf_thumbnails está en machete_admin.php
// limit_revisions está en machete_admin.php


if (in_array('oembed_scripts',$this->settings)) {
  //Remove oEmbed Scripts
  //Since WordPress 4.4, oEmbed is installed and available by default. WordPress assumes you’ll want to easily embed media like tweets and YouTube videos so includes the scripts as standard. If you don’t need oEmbed, you can remove it
  wp_deregister_script('wp-embed');
}


// slow_heartbeat está en machete_admin.php

if (in_array('comments_reply_feature',$this->settings)) {
  //Only load the comment-reply.js when needed
  
  add_action('wp_print_scripts', function(){
      if (is_singular() && (get_option('thread_comments') == 1) && comments_open() && have_comments()) {
        wp_enqueue_script('comment-reply');
      } else {
        wp_dequeue_script('comment-reply');
      }
  }, 100);
}

//empty_trash_soon está en machete_admin.php

if (in_array('medium_large_size',$this->settings)) {
  add_image_size( 'medium_large', 0, 0);
}

if (in_array('comment_autolinks',$this->settings)) {
  remove_filter('comment_text', 'make_clickable', 9); 
}

if (in_array('wpcf7_refill',$this->settings)) {
  add_action( 'wp_enqueue_scripts', function(){
    wp_localize_script( 'contact-form-7', 'wpcf7', array(
      'cached' => 0,
      'jqueryUi' => 1
    ));
  }, 10);
}