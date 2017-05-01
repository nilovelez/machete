<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(@file_exists(MACHETE_DATA_PATH.'header.html')){
	add_action( 'wp_head', function(){
  		readfile(MACHETE_DATA_PATH.'header.html');
	});
}

if(@file_exists(MACHETE_DATA_PATH.'footer.html')){
	add_action( 'wp_footer', function(){
  		readfile(MACHETE_DATA_PATH.'footer.html');
	});
}

if(@file_exists(MACHETE_DATA_PATH.'body.html')){
	if(
		($machete_utils_settings = get_option('machete_utils_settings')) &&
		(isset($machete_utils_settings['alfonso_content_injection_method'])) &&
		($machete_utils_settings['alfonso_content_injection_method'] == 'auto')
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