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

  // enable rss thumbnails
  if (in_array('rss_thumbnails',$settings)) {
    function machete_add_rss_thumbnail($content) {
      global $post;
      
      if(has_post_thumbnail($post->ID)) {
          $content = '<div class="post-thumbnail-feed">' . get_the_post_thumbnail($post->ID, 'full') . '</div>' . $content;
      }
      return $content;
    }
    add_filter('the_excerpt_rss', 'machete_add_rss_thumbnail');
    add_filter('the_content_feed', 'machete_add_rss_thumbnail');
  }






   


}