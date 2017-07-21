<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

function machete_maintenance_page() {
   add_submenu_page(
    'machete',
    __('Coming Soon & Maintenance Mode','machete'),
    __('Maintenance Mode','machete'),
    'publish_posts', // targeting Author role
    'machete-maintenance',
    'machete_maintenance_page_content'
  );
}
add_action('admin_menu', 'machete_maintenance_page');

function machete_maintenance_page_content() {
	require('admin_content.php');
	add_filter('admin_footer_text', 'machete_footer_text');
}


if ( ! function_exists( 'machete_maintenance_save_options' ) ) :
function machete_array_equal($a, $b) {
    return (
         is_array($a) && is_array($b) && 
         count($a) == count($b) &&
         array_diff($a, $b) === array_diff($b, $a)
    );
}


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

function machete_maintenance_notice_no_changes() { ?>
    <div class="notice notice-info is-dismissible">
        <p><?php _e( 'No changes were needed.', 'machete' ); ?></p>
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
	}else{
		$old_settings = $settings;
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

	if (isset($old_settings) && machete_array_equal($old_settings, $settings)){
		add_action( 'admin_notices', 'machete_maintenance_notice_no_changes' );
		return false;
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

/* Machete Maintenance */
if (isset($_POST['machete-maintenance-saved'])){

  check_admin_referer( 'machete_save_maintenance' );
  
  if(machete_maintenance_save_options()){
    new Machete_Notice(__( 'Options saved!', 'machete' ), 'success');
  }
}
if(is_admin_bar_showing()){
	require_once('admin_bar.php');
}