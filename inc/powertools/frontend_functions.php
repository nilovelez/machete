<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function machete_powertools($settings){

  /*
  'post_cloner'
  'widget_shortcodes'
  'widget_oembed'
  'rss_thumbnails'
  'page_excerpts'
  */

  // enable shortcodes in widgets
  if (in_array('widget_shortcodes',$settings)) {
    //add_filter( 'widget_text', 'shortcode_unautop' );
    add_filter( 'widget_text', 'do_shortcode', 11 );
  }

  // enable oembed in text widgets
  if (in_array('widget_oembed',$settings)) {
    global $wp_embed;
    add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
    add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );
  }

  // enable page_excerpts
  if (in_array('page_excerpts',$settings)) {
    add_post_type_support( 'page', 'excerpt' );
  }

}