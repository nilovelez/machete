<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_cleanup_module extends machete_module {

	function __construct(){
		$this->init( array(
			'slug' => 'cookies',
			'title' => __('Cookie Law','machete'),
			'full_title' => __('Cookie Law Warning','machete'),
			'description' => __('Light and responsive cookie law warning bar that won\'t affect your PageSpeed score and plays well with static cache plugins.','machete'),
			//'is_active' => true,
			//'has_config' => true,
			//'can_be_disabled' => true,
			'role' => 'author'
			)
		);
	}
	public function admin(){
		$this->read_settings();

		if (isset($_POST['machete-cookies-saved'])){
  			check_admin_referer( 'machete_save_cookies' );
			$this->save_settings();
		}

		if( count( $this->settings ) > 0 ) { 
			require( $this->path . 'admin_functions.php' );
		}
		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}




	function save_settings() {

		/*
		bar_status: disabled | enabled
		warning_text
		accept_text
		bar_theme: light | dark
		*/
		if(!$settings = get_option('machete_cookies_settings')){
			$settings = array();
		};
		
		if (!is_dir(MACHETE_DATA_PATH)){
			if(!@mkdir(MACHETE_DATA_PATH)){
				add_action( 'admin_notices', 'machete_cookies_error_mkdir' );
				return false;
			}
		}


		if(!$cookies_bar_js = @file_get_contents(MACHETE_BASE_PATH.'templates/cookies_bar_js.js')){
			$this->notice(printf( __( 'Error creating data dir %s please check file permissions', 'machete' ), MACHETE_RELATIVE_DATA_PATH), 'error');
			add_action( 'admin_notices', 'machete_cookies_error_read_template' );
			return false;
		}

		$machete_cookies_bar_html = 'var machete_cookies_bar_html = \'<style>@media (min-width: 1024px) {#machete_cookie_bar {margin-bottom: 5px; border-radius: 4px; border-width: 1px; border-style: solid;} #machete_accept_cookie_btn {margin: -5px -5px 10px 10px;}}</style><div id="machete_cookie_bar" style="font-family: sans-serif; font-size: 13px; color: {{color}}; background-color: {{background_color}}; border-color: {{border_color}}; padding: 15px; margin-left: auto; margin-right: auto; max-width: 960px; border-top-style: solid; border-top-width: 1px;"><a id="machete_accept_cookie_btn" style="cursor: pointer; text-decoration: none; display: block; padding: 5px 10px; float: right; margin-left: 10px; white-space: nowrap; border-radius: 4px; background-color: {{btn_background_color}}; border-bottom: 2px solid {{btn_border_color}}; color: {{btn_color}};">{{accept_text}}</a> {{warning_text}}</div>\';';

		$cookies_bar_themes = array(
		'light' => array(
			'{{color}}' => '#222',
			'{{background_color}}' => '#fff',
			'{{border_color}}' => '#ddd',
			'{{btn_color}}' => '#000',
			'{{btn_background_color}}' => '#ddd',
			'{{btn_border_color}}' => '#999',
			),
		'dark' => array(
			'{{color}}' => '#999',
			'{{background_color}}' => '#111',
			'{{border_color}}' => '#999',
			'{{btn_color}}' => '#000',
			'{{btn_background_color}}' => '#999',
			'{{btn_border_color}}' => '#000',
			)
		);


		if (!empty($_POST['bar_theme']) && (array_key_exists($_POST['bar_theme'], $cookies_bar_themes))){
			$settings['bar_theme'] = $_POST['bar_theme'];
		}else{
			$settings['bar_theme'] = 'light';
		}
		$html_replaces = $cookies_bar_themes[$settings['bar_theme']];
		

		$settings['bar_status'] = sanitize_text_field($_POST['bar_status']);
		if (empty($settings['bar_status']) || ($settings['bar_status'] != 'disabled')){
			$settings['bar_status'] = 'enabled';
		}

		$settings['warning_text'] = trim(wptexturize($_POST['warning_text']));
		if (empty($settings['warning_text'])){
			add_action( 'admin_notices', 'machete_cookies_error_empty_warning_text' );
			return false;
		}
		$html_replaces['{{warning_text}}'] = $settings['warning_text'];


		$settings['accept_text'] = trim(sanitize_text_field($_POST['accept_text']));
		if (empty($settings['accept_text'])){
			add_action( 'admin_notices', 'machete_cookies_error_empty_accept_text' );
			return false;
		}
		$html_replaces['{{accept_text}}']  = $settings['accept_text'];

		
		


		$cookies_bar_js = str_replace(
			array_keys($html_replaces),
			array_values($html_replaces),
			$machete_cookies_bar_html
			)."\n".$cookies_bar_js;


		// delete old .js file and generate a new one to prevent caching
		$old_cookie_filename = $settings['cookie_filename'];

		// cheap and dirty pseudo-random filename generation
		$settings['cookie_filename'] = 'cookies_'.strtolower(substr(MD5(time()),0,8)).'.js';
		
		
		if($settings['bar_status'] == 'enabled'){
			if(!@file_put_contents(MACHETE_DATA_PATH.$settings['cookie_filename'], $cookies_bar_js)){
				add_action( 'admin_notices', 'machete_cookies_error_write_file' );
				return false;
			}
		}


		if (!empty($old_cookie_filename) && file_exists(MACHETE_DATA_PATH.$old_cookie_filename)){
			if(!unlink(MACHETE_DATA_PATH.$old_cookie_filename)){
				add_action( 'admin_notices', 'machete_cookies_error_delete_old_file' );
				return false;
			}
		}
		
		
		// option saved WITH autoload
		if(update_option( 'machete_cookies_settings', $settings, 'yes' )){
			return true;
		}else{
			add_action( 'admin_notices', 'machete_cookies_error_save_options' );
			return false;
		}

	}
}
