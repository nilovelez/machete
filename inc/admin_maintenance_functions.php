<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

if ( ! function_exists( 'machete_maintenance_save_options' ) ) :


function machete_maintenance_error_save_options() { ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'Error saving configuration to database.', 'machete' ); ?></p>
    </div>
<?php }


function machete_maintenance_error_bad_page_id() { ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e( 'Content page id is not a valid page id', 'machete' ); ?></p>
    </div>
<?php }

function machete_maintenance_save_options() {

	/*
	page_id: int
	site_status: online | coming_soon | maintenance
	token: string
	*/
	if(!$settings = get_option('machete_maintenance_settings')){
		array(
			'page_id' => '',
			'site_status' => 'online',
			'token' => strtoupper(substr(MD5(rand()),0,12))
		);
	};

	if (!empty($_POST['site_status']) && (in_array($_POST['site_status'], array('online','coming_soon','maintenance')))){
		$settings['site_status'] = $_POST['site_status'];
	}

	if (!empty($_POST['token'])){
		$settings['token'] = sanitize_text_field($_POST['token']);
	}

	if (!empty($_POST['page_id'])){
		$settings['page_id'] = (int) sanitize_text_field($_POST['page_id']);
		if (empty($settings['page_id'])){
			add_action( 'admin_notices', 'machete_maintenance_error_bad_page_id' );
			return false;
		}
	}else{
		$settings['page_id'] = '';
	}

	// option saved WITH autoload
	if(update_option( 'machete_maintenance_settings', $settings, 'yes' )){
		return true;
	}else{
		add_action( 'admin_notices', 'machete_maintenance_error_save_options' );
		return false;
	}

}
endif; // machete_maintenance_save_options()
