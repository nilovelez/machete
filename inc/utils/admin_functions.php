<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

function machete_utils_page() {
  add_submenu_page(
  	'machete',
  	__('Analytics and Custom Code','machete'),
    __('Analytics & Code','machete'),
    'manage_options',
    'machete-utils',
    'machete_utils_page_content'
  );
}
add_action('admin_menu', 'machete_utils_page');


function machete_utils_page_content() {
	require('admin_content.php');
	add_filter('admin_footer_text', 'machete_footer_text');
}


if ( ! function_exists( 'machete_utils_save_options' ) ) :
	
function machete_utils_error_mkdir() { ?>
    <div class="notice notice-error is-dismissible">
        <p><?php printf( __( 'Error creating data dir %s please check file permissions', 'machete' ), MACHETE_DATA_PATH); ?></p>
    </div>
<?php }

function machete_utils_error_bad_ua() { ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'That doesn\'t look like a valid Google Analytics tracking ID', 'machete' ); ?></p>
    </div>
<?php }

function machete_utils_error_bad_format() { ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'Something went wrong. Unknown tracking code format requested.', 'machete' ); ?></p>
    </div>
<?php }


function machete_utils_save_options() {
	/*
	tracking_id
	tracking_format: standard, machete, none
	header_content
	footer_content
	*/

	$header_content = '';

	if(!$settings = get_option('machete_utils_settings')){
		$settings = array(
			'tracking_id' => '',
			'tracking_format' => 'none'
		);
	};
	
	if (!is_dir(MACHETE_DATA_PATH)){
		if(!@mkdir(MACHETE_DATA_PATH)){
			add_action( 'admin_notices', 'machete_utils_error_mkdir' );
			return false;
		}
	}

	if(!empty($_POST['tracking_id'])){

		
		$settings['tracking_id'] = $_POST['tracking_id'];

		if(!preg_match('/^ua-\d{4,9}-\d{1,4}$/i', strval( $_POST['tracking_id'] ))){
			// invalid Analytics Tracking ID
			// http://code.google.com/apis/analytics/docs/concepts/gaConceptsAccounts.html#webProperty
			add_action( 'admin_notices', 'machete_utils_error_bad_ua' );
			return false;
		}

		$settings['tracking_format'] = $_POST['tracking_format'];

		if( !in_array( $_POST['tracking_format'], array('standard','machete','none') )){
			// I don't know that tracking format
			add_action( 'admin_notices', 'machete_utils_error_bad_format' );
			return false;
		}

		if ( isset( $_POST['tacking_anonymize'] )){
			$settings['tacking_anonymize'] = 1;
			$anonymizeIp = ',{anonymizeIp: true}';
		}else{
			$settings['tacking_anonymize'] = 0;
			$anonymizeIp = '';
		}

		// let's generate the Google Analytics tracking code
		if($_POST['tracking_format'] == 'machete'){
			$header_content .= 'if (!navigator.userAgent || ('."\n";
			$header_content .= '  (navigator.userAgent.indexOf("Speed Insights") == -1) &&'."\n";
			$header_content .= '  (navigator.userAgent.indexOf("Googlebot") == -1)'."\n";
			$header_content .= ')) {'."\n";
		}
		if($_POST['tracking_format'] != 'none'){
			
			$header_content .= '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){'."\n";
			$header_content .= '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),'."\n";
			$header_content .= 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)'."\n";
			$header_content .= '})(window,document,\'script\',\'https://www.google-analytics.com/analytics.js\',\'ga\');'."\n";

			$header_content .= 'ga(\'create\', \''.$_POST['tracking_id'].'\', \'auto\''.$anonymizeIp.');'."\n";
			$header_content .= 'ga(\'send\', \'pageview\');'."\n";
			
		}
		if($_POST['tracking_format'] == 'machete'){
			$header_content .= '}'."\n";
		}
		if($_POST['tracking_format'] != 'none'){
			$header_content = "<script>\n".$header_content."</script>\n<!-- Machete Header -->\n";
		}
	}else{
		$settings['tracking_id'] = '';
		$settings['tracking_format'] = 'none';
	}

	if(!empty($_POST['header_content'])){
		$header_content .= stripslashes(wptexturize($_POST['header_content']));
	}

	if(!empty($header_content)){
		file_put_contents(MACHETE_DATA_PATH.'header.html', $header_content, LOCK_EX);
	}else{
		if (file_exists(MACHETE_DATA_PATH.'header.html')){
			unlink(MACHETE_DATA_PATH.'header.html');
		}
	}


	if(!empty($_POST['alfonso_content_injection_method']) &&
		($settings['alfonso_content_injection_method'] == 'auto')){
		$settings['alfonso_content_injection_method'] = 'auto';
	}else{
		$settings['alfonso_content_injection_method'] = 'manual';
	}


	if(!empty($_POST['alfonso_content'])){
		$alfonso_content = stripslashes(wptexturize($_POST['alfonso_content']));
		file_put_contents(MACHETE_DATA_PATH.'body.html', $alfonso_content, LOCK_EX);
	}else{
		if (file_exists(MACHETE_DATA_PATH.'body.html')){
			unlink(MACHETE_DATA_PATH.'body.html');
		}
	}


	if(!empty($_POST['footer_content'])){
		$footer_content = stripslashes(wptexturize($_POST['footer_content']));
		file_put_contents(MACHETE_DATA_PATH.'footer.html', $footer_content, LOCK_EX);
	}else{
		if (file_exists(MACHETE_DATA_PATH.'footer.html')){
			unlink(MACHETE_DATA_PATH.'footer.html');
		}
	}

	// option saved WITHOUT autoload
	update_option( 'machete_utils_settings', $settings, 'no' );
		
	return true;

}
endif; // machete_utils_save_options()


/* Machete Utils */
if (isset($_POST['machete-utils-saved'])){
	check_admin_referer( 'machete_save_utils' );
	if(machete_utils_save_options()){
		new Machete_Notice(__( 'Options saved!', 'machete' ), 'success');
	}
}