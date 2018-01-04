<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class machete_cookies_module extends machete_module {

	function __construct(){
		$this->init( array(
			'slug' => 'cookies',
			'title' => __('Cookie Law','machete'),
			'full_title' => __('Cookie Law Warning','machete'),
			'description' => __('Light and responsive cookie law warning bar that won\'t affect your PageSpeed score and plays well with static cache plugins.','machete'),
			//'is_active' => true,
			//'has_config' => true,
			//'can_be_disabled' => true,
			'role' => 'publish_posts' // targeting Author role
			)
		);
		$this->default_settings = array(
			'bar_status' => 'disabled',
			'warning_text' => __('By continuing to browse the site, you are agreeing to our use of cookies as described in our <a href="/cookies/" style="color: #007FFF">cookie policy</a>.','machete'),
			'accept_text' => __('Accept cookies','machete'),
			'bar_theme' => 'light',
			'cookie_filename' => ''
			);

		// Si continúas navegando por esta web, entendemos que aceptas las cookies que usamos para mejorar nuestros servicios.
		// By continuing to browse the site, you are agreeing to our use of cookies as described in our cookie policy.
	
		$this->themes = array(
			'light' => array(
				'template' => $this->path.'templates/light.html',
				'extra_css' => ''
			),
			'dark' => array(
				'template' => $this->path.'templates/dark.html',
				'extra_css' => ''
			),
			'new_light' => array(
				'template' => $this->path.'templates/new_light.html',
				'extra_css' => ''
			),
			'new_dark' => array(
				'template' => $this->path.'templates/new_dark.html',
				'extra_css' => ''
			),
			'cookie' => array(
				'template' => $this->path.'templates/cookie.html',
				'extra_css' => '#machete_cookie_bar {background-image: url('.MACHETE_BASE_URL.'img/cookie_monster.svg);}'
			)
		);

	}
	
	public function admin(){
		$this->read_settings();

		if (isset($_POST['machete-cookies-saved'])){
  			check_admin_referer( 'machete_save_cookies' );
			$this->save_settings();
		}

		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}

	public function frontend() {
		$this->read_settings();
		add_action( 'wp_footer', array(&$this,'render_cookie_bar'));
	}
	
	protected function save_settings() {

		/*
		bar_status: disabled | enabled
		warning_text
		accept_text
		bar_theme: light | dark
		*/
		
		if (!is_dir(MACHETE_DATA_PATH)){
			if(!@mkdir(MACHETE_DATA_PATH)){
				$this->notice(sprintf( __( 'Error creating data dir %s please check file permissions', 'machete' ), MACHETE_RELATIVE_DATA_PATH), 'error');
				return false;
			}
		}

		if(!$cookies_bar_js = @file_get_contents($this->path.'templates/cookies_bar_js.min.js')){
			$this->notice(sprintf( __( 'Error reading cookie bar template %s', 'machete' ), $this->path.'templates/cookies_bar_js.js'), 'error');
			return false;
		}

		$html_replaces = array();

		

		
		if (!empty($_POST['bar_theme']) && (array_key_exists($_POST['bar_theme'], $this->themes))){
			$settings['bar_theme'] = $_POST['bar_theme'];
		}else{
			$settings['bar_theme'] = 'light';
		}

		if(!$cookies_bar_html = @file_get_contents($this->themes[$settings['bar_theme']]['template'])){
			$this->notice(sprintf( __( 'Error reading cookie bar template %s', 'machete' ), $cookies_bar_themes[$settings['bar_theme']]['template']), 'error');
			return false;
		}

		$cookies_bar_html = "var machete_cookies_bar_html = '".addslashes($cookies_bar_html)."'; \n";

		$settings['bar_status'] = sanitize_text_field($_POST['bar_status']);
		if (empty($settings['bar_status']) || ($settings['bar_status'] != 'disabled')){
			$settings['bar_status'] = 'enabled';
		}

		$settings['warning_text'] = trim(wptexturize($_POST['warning_text']));
		if (empty($settings['warning_text'])){
			$ths->notice ( __('Cookie warning text can\'t be blank', 'machete' ), 'warning' );
			return false;
		}
		$html_replaces['{{warning_text}}'] = $settings['warning_text'];

		$settings['accept_text'] = trim(sanitize_text_field($_POST['accept_text']));
		if (empty($settings['accept_text'])){
			$ths->notice ( __('Accept button text can\'t be blank', 'machete' ), 'warning' );
			return false;
		}
		$html_replaces['{{accept_text}}']  = $settings['accept_text'];

		$html_replaces['{{extra_css}}'] = $this->themes[$settings['bar_theme']]['extra_css'];

		$cookies_bar_js = str_replace(
			array_keys($html_replaces),
			array_values($html_replaces),
			$cookies_bar_html
			)."\n".$cookies_bar_js;


		// delete old .js file and generate a new one to prevent caching
		$old_cookie_filename = $this->settings['cookie_filename'];

		// cheap and dirty pseudo-random filename generation
		$settings['cookie_filename'] = 'cookies_'.strtolower(substr(MD5(time()),0,8)).'.js';
		
		
		if($settings['bar_status'] == 'enabled'){
			if(!@file_put_contents(MACHETE_DATA_PATH.$settings['cookie_filename'], $cookies_bar_js)){
				$this->notice(sprintf( __( 'Error writing static javascript file to %s please check file permissions. Aborting to prevent inconsistent state.', 'machete' ), MACHETE_RELATIVE_DATA_PATH), 'error');
				return false;
			}
		}

		if (!empty($old_cookie_filename) && file_exists(MACHETE_DATA_PATH.$old_cookie_filename)){
			if(!unlink(MACHETE_DATA_PATH.$old_cookie_filename)){
				$this->notice (sprintf( __( 'Could not delete old javascript file from %s please check file permissions . Aborting to prevent inconsistent state.', 'machete' ), MACHETE_RELATIVE_DATA_PATH), 'warning');
				return false;
			}
		}
		
		// option saved WITH autoload
		if(update_option( 'machete_cookies_settings', $settings, 'yes' )){
			$this->settings = $settings;
			$this->save_success_notice();
			return true;
		}else{
			$this->save_error_notice();
			return false;
		}

	}

	protected function export(){
		$export = $this->settings;
		if ( !empty( stripslashes( $export['warning_text'] ) ) ){
			$export['warning_text'] = stripslashes($export['warning_text']);
		}
		return $export;
	}

	protected function preview_cookie_bar(){ 

		if(!isset($this->settings['bar_status']) || ($this->settings['bar_status'] != 'enabled') ){
				return false;
		}
		if(!isset($this->settings['cookie_filename'])){
			return false;
		}
		if(!file_exists(MACHETE_DATA_PATH.$this->settings['cookie_filename'])){
			return false;
		}

		echo '<script>';
		readfile(MACHETE_DATA_URL.$this->settings['cookie_filename']);
		echo '</script>';

	}

	public function render_cookie_bar(){ 
		
		if(!isset($this->settings['bar_status']) || ($this->settings['bar_status'] != 'enabled') ){
				return false;
		}
		if(!isset($this->settings['cookie_filename'])){
			return false;
		}
		if(!file_exists(MACHETE_DATA_PATH.$this->settings['cookie_filename'])){
			return false;
		}

		?><script>
	if (!navigator.userAgent || (
	    (navigator.userAgent.indexOf("Speed Insights") == -1) &&
	    (navigator.userAgent.indexOf("Googlebot") == -1)
	)) {(function(){
	    var s = document.createElement('script'); s.type = 'text/javascript';
	    s.async = true; s.src = '<?php echo MACHETE_DATA_URL.$this->settings['cookie_filename'] ?>';
	    var body = document.getElementsByTagName('body')[0];
	    body.appendChild(s);
	})()}
	</script><?php 
	}
}
$machete->modules['cookies'] = new machete_cookies_module();