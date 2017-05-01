<?php
if ( ! defined( 'ABSPATH' ) ) exit;

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