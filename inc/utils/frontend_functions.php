<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(@file_exists(MACHETE_DATA_PATH.'header.html')){
	add_action( 'wp_head', function(){
  		readfile(MACHETE_DATA_PATH.'header.html');
	});

	if ( $this->settings['track_wpcf7'] ) {
		add_filter ( 'wpcf7_contact_form_properties', function( $properties, $wpcf7 ){
			global $machete;
			$machete->modules['utils']->last_wpcf7 = $wpcf7->title();
			add_filter( 'wpcf7_form_hidden_fields', function( $hidden_fields ) {
				global $machete;
				$hidden_fields['machete_wpcf7_title'] = $machete->modules['utils']->last_wpcf7;
				return $hidden_fields;
			}, 10, 1);
			return $properties;
		}, 10, 2);

		wp_enqueue_script(
			'machete_track_wpcf7',
			plugins_url('js/track_wpcf7.min.js', __FILE__),
			array(), MACHETE_VERSION, true
		);
	}
}

if(@file_exists(MACHETE_DATA_PATH.'custom.css')){
	wp_enqueue_script(
		'machete_custom_css',
		MACHETE_DATA_PATH.'custom.css',
		array(), MACHETE_VERSION
	);
}

if(@file_exists(MACHETE_DATA_PATH.'footer.html')){
	add_action( 'wp_footer', function(){
  		readfile(MACHETE_DATA_PATH.'footer.html');
	});
}

if(@file_exists(MACHETE_DATA_PATH.'body.html')){
	if(
		!empty($this->settings['alfonso_content_injection_method']) &&
		($this->settings['alfonso_content_injection_method'] == 'auto')
	){
		// automatic body injection
		add_filter('body_class','machete_inject_body_content',10001);
		function machete_inject_body_content( $classes ){
			$alfonso_content = file_get_contents(MACHETE_DATA_PATH.'body.html');
			$classes[] = '">'.$alfonso_content.'<br style="display: none';
			return $classes;
		}

		// disable manual body injection
		function machete_custom_body_content() {
			echo '';
		}

	}else{

		// manual body injection
		function machete_custom_body_content() {
			readfile(MACHETE_DATA_PATH.'body.html');
		}
	}	
}else{
	// disable manual body injection
	function machete_custom_body_content() {
		echo '';
	}
}