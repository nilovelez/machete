<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

if ( ! function_exists( 'machete_cookies_save_options' ) ) :



function machete_cookies_error_mkdir() { ?>
    <div class="notice notice-error is-dismissible">
        <p><?php printf( __( 'Error creating data dir %s please check file permissions', 'machete' ), MACHETE_RELATIVE_DATA_PATH); ?></p>
    </div>
<?php }

function machete_cookies_error_read_template() { ?>
    <div class="notice notice-error is-dismissible">
        <p><?php printf( __( 'Error reading cookie bar template %s', 'machete' ), MACHETE_RELATIVE_BASE_PATH.'templates/cookies_bar_js.js'); ?></p>
    </div>
<?php }

function machete_cookies_error_warning_text() { ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e('Cookie warning text can\'t be blank', 'machete' ); ?></p>
    </div>
<?php }

function machete_cookies_error_accept_text() { ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e('Accept button text can\'t be blank', 'machete' ); ?></p>
    </div>
<?php }

function machete_cookies_error_write_file() { ?>
    <div class="notice notice-error is-dismissible">
        <p><?php printf( __( 'Error writing static javascript file to %s please check file permissions. Aborting to prevent inconsistent state.', 'machete' ), MACHETE_RELATIVE_DATA_PATH); ?></p>
    </div>
<?php }

function machete_cookies_error_delete_old_file() { ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php printf( __( 'Could not delete old javascript file from %s please check file permissions . Aborting to prevent inconsistent state.', 'machete' ), MACHETE_RELATIVE_DATA_PATH); ?></p>
    </div>
<?php }

function machete_cookies_error_save_options() { ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'Error saving configuration to database.', 'machete' ); ?></p>
    </div>
<?php }

function machete_cookies_save_options() {

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
		add_action( 'admin_notices', 'machete_cookies_error_read_template' );
		return false;
	}

	$machete_cookies_bar_html = 'var machete_cookies_bar_html = \'<style>@media (min-width: 1024px) {#machete_cookie_bar {margin-bottom: 5px; border-radius: 4px; border-width: 1px; border-style: solid;} #machete_accept_cookie_btn {margin: -5px -5px 10px 10px;}}</style><div id="machete_cookie_bar" style="font-family: sans-serif; font-size: 13px; color: {{color}}; background-color: {{background_color}}; border-color: {{border_color}}; padding: 15px; margin-left: auto; margin-right: auto; max-width: 960px; border-top-style: solid; border-top-width: 1px;"><a id="machete_accept_cookie_btn" style="text-decoration: none; display: block; padding: 5px 10px; float: right; margin-left: 10px; white-space: nowrap; border-radius: 4px; background-color: {{btn_background_color}}; border-bottom: 2px solid {{btn_border_color}}; color: {{btn_color}};">{{accept_text}}</a> {{warning_text}}</div>\';';

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
endif; // machete_utils_save_options()
