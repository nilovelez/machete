<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(($machete_cleanup_settings = get_option('machete_cleanup_settings')) && (count($machete_cleanup_settings) > 0)){
	require_once('inc/cleanup/frontend_functions.php');
	machete_optimize($machete_cleanup_settings);
}


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
		add_filter('body_class','machete_inject_alfonso_content',10001);
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

add_action( 'wp_footer', 'machete_cookie_bar');

function machete_cookie_bar(){ 
	
	if(!$machete_cookies_settings = get_option('machete_cookies_settings')){
		return false;
	}
	if(!isset($machete_cookies_settings['bar_status']) || ($machete_cookies_settings['bar_status'] != 'enabled') ){
			return false;
	}
	if(!isset($machete_cookies_settings['cookie_filename'])){
		return false;
	}
	if(!file_exists(MACHETE_DATA_PATH.$machete_cookies_settings['cookie_filename'])){
		return false;
	}

	?><script>
if (!navigator.userAgent || (
    (navigator.userAgent.indexOf("Speed Insights") == -1) &&
    (navigator.userAgent.indexOf("Googlebot") == -1)
)) {(function(){
    var s = document.createElement('script'); s.type = 'text/javascript';
    s.async = true; s.src = '<?php echo MACHETE_DATA_URL.$machete_cookies_settings['cookie_filename'] ?>';
    var body = document.getElementsByTagName('body')[0];
    body.appendChild(s);
})()}
</script><?php 
}

require_once( 'inc/maintenance/frontend_functions.php' );