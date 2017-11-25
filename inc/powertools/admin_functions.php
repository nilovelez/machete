<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;


// enable page_excerpts
if (in_array('page_excerpts',$this->settings)) {
    add_post_type_support( 'page', 'excerpt' );
}

// enable oembed in text widgets
if (in_array('widget_oembed',$this->settings)) {
	global $wp_embed;
	add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
	add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );
}

// save with keyboard
if (in_array('save_with_keyboard',$this->settings)) {
    function machete_save_with_keyboard() {

      
    }
    add_action('admin_enqueue_scripts', function(){
    	wp_register_script('machete_save_with_keyboard',MACHETE_BASE_URL.'vendor/save-with-keyboard/saveWithKeyboard.js',array('jquery'));
    	$translation_array = array(
    		'save_button_tooltip' => __( 'Ctrl+S or Cmd+S to click', 'machete' ),
    		'preview_button_tooltip' => __( 'Ctrl+P or Cmd+P to preview', 'machete' )
    	);
    	wp_localize_script( 'machete_save_with_keyboard', 'l10n_strings', $translation_array );
    	wp_enqueue_script( 'machete_save_with_keyboard' );

    });
}