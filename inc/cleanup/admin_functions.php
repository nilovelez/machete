<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;


// Machete cleanup actions specific to the back-end
if (in_array('emojicons',$this->settings)) {

  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  //remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  //remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  //remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  //remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  add_filter( 'tiny_mce_plugins', function( $plugins ) {
    if ( is_array( $plugins ) ) {
      return array_diff( $plugins, array( 'wpemoji' ) );
    }
    return array();
  });

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

if (in_array('pdf_thumbnails',$this->settings)) {
  
  add_filter('fallback_intermediate_image_sizes', function() {
    $fallbacksizes = array();
    return $fallbacksizes;
  });
}

if (in_array('limit_revisions',$this->settings)) {
  if ( defined('WP_POST_REVISIONS') && (WP_POST_REVISIONS != false)) {
    add_filter( 'wp_revisions_to_keep', 'machete_revisions_filter', 10, 2 );
    function machete_revisions_filter( $num, $post ) {
       return 5;
    }
  }
}

//Slow default heartbeat
if (in_array('slow_heartbeat',$this->settings)) {
  
  add_filter( 'heartbeat_settings', function ( $settings ) {
    $settings['interval'] = 60; 
    return $settings;
  });
}

//Empty trash sooner
if (in_array('empty_trash_soon',$this->settings)) {
  if (!defined('EMPTY_TRASH_DAYS')) {
    define('EMPTY_TRASH_DAYS', 7);
  }
}

if (in_array('medium_large_size',$this->settings)) {
  add_image_size( 'medium_large', 0, 0);
}

if (in_array('disable_editor',$this->settings)) {
  if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT',true);
  }
}