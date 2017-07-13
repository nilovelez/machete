<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;


function machete_cleanup_page() {
  add_submenu_page(
  	'machete',
    __('WordPress Optimization','machete'),
    __('Optimization','machete'),
    'manage_options', // targeting Admin role    
    'machete-cleanup',
    'machete_cleanup_page_content'
  );
}
add_action('admin_menu', 'machete_cleanup_page');

function machete_cleanup_page_content() {
	require('admin_content.php');
}


if ( ! function_exists( 'machete_cleanup_save_options' ) ) :
function machete_cleanup_save_options() {

	if (isset($_POST['optionEnabled'])){

		$settings = $_POST['optionEnabled'];

		for($i = 0; $i < count($settings); $i++){
			$settings[$i] = sanitize_text_field($settings[$i]);
		}
		
		if ($old_options = get_option('machete_cleanup_settings')){
			if(
				(0 == count(array_diff($settings, $old_options))) &&
				(0 == count(array_diff($old_options, $settings)))
				){
				// no removes && no adds
				new Machete_Notice(__( 'No changes were needed.', 'machete' ), 'info');
				return false;
			}
		}
		if (update_option('machete_cleanup_settings',$settings)){
			return true;
		}else{
			new Machete_Notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
			return false;
		}

	}else{
		if (delete_option('machete_cleanup_settings')){
			return true;
		}else{
			new Machete_Notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
			return false;
		}
	}
	return false;
}
endif; // machete_cleanup_save_options()


//update_option( $option, $new_value, $autoload );
if (isset($_POST['machete-cleanup-saved'])){
  	check_admin_referer( 'machete_save_cleanup' );
	if(machete_cleanup_save_options()){
		new Machete_Notice(__( 'Options saved!', 'machete' ), 'success');
	}
}



// Machete cleanup actions specific to the back-end
if(
  ($machete_cleanup_settings = get_option('machete_cleanup_settings')) &&
  (count($machete_cleanup_settings) > 0)){

  if (in_array('emojicons',$machete_cleanup_settings)) {
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    //remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    //remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    //remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    //remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    add_filter( 'tiny_mce_plugins', 'machete_disable_emojicons_tinymce' );

  }

  if (in_array('pdf_thumbnails',$machete_cleanup_settings)) {
    function machete_disable_pdf_previews() {
      $fallbacksizes = array();
      return $fallbacksizes;
    }
    add_filter('fallback_intermediate_image_sizes', 'machete_disable_pdf_previews');
  }

  if (in_array('limit_revisions',$machete_cleanup_settings)) {
    if ( defined('WP_POST_REVISIONS') && (WP_POST_REVISIONS != false)) {
      add_filter( 'wp_revisions_to_keep', 'machete_revisions_filter', 10, 2 );
      function machete_revisions_filter( $num, $post ) {
         return 5;
      }
    }
  }

  //Slow default heartbeat
  if (in_array('slow_heartbeat',$machete_cleanup_settings)) {
    function machete_slow_heartbeat( $settings ) {
      $settings['interval'] = 60; 
      return $settings;
    }
    add_filter( 'heartbeat_settings', 'machete_slow_heartbeat' );
  }

  //Empty trash sooner
  if (in_array('empty_trash_soon',$machete_cleanup_settings)) {
    if (!defined('EMPTY_TRASH_DAYS')) {
      define('EMPTY_TRASH_DAYS', 7);
    }
  }

}

if ( ! function_exists( 'machete_disable_emojicons_tinymce' ) ) :
function machete_disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  }
  return array();
}
endif;