<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_maintenance_module extends machete_module {

	function __construct(){
		$this->init( array(
			'slug' => 'maintenance',
			'title' => __('Maintenance Mode','machete'),
			'full_title' => __('Maintenance Mode','machete'),
			'description' => __('Customizable maintenance page to close your site during updates or development. It has a "magic link" to grant temporary access.','machete'),
			//'is_active' => true,
			//'has_config' => true,
			//'can_be_disabled' => true,
			'role' => 'publish_posts' // targeting Author role
			)
		);
		$this->default_settings = array(
			'page_id' => '',
			'site_status' => 'online',
			'token' => strtoupper(substr(MD5(rand()),0,12))
			);

		// Si continúas navegando por esta web, entendemos que aceptas las cookies que usamos para mejorar nuestros servicios.
		// By continuing to browse the site, you are agreeing to our use of cookies as described in our cookie policy.
	}



	public function admin(){
		$this->read_settings();

		if ( isset( $_POST['machete-maintenance-saved'] ) ){
		    check_admin_referer( 'machete_save_maintenance' );
		  	$this->save_settings();
		}

		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );

		if(is_admin_bar_showing()){
			require_once('admin_bar.php');
		}
	}

	function save_settings() {

		/*
		page_id: int
		site_status: online | coming_soon | maintenance
		token: string
		*/
		$old_settings = $this->settings;


		if (!empty($_POST['site_status']) && (in_array($_POST['site_status'], array('online','coming_soon','maintenance')))){
			$settings['site_status'] = $_POST['site_status'];
		}

		if (!empty($_POST['token'])){
			$settings['token'] = sanitize_text_field($_POST['token']);
		}

		if (!empty($_POST['page_id'])){
			$settings['page_id'] = (int) sanitize_text_field($_POST['page_id']);
			if (empty($settings['page_id'])){
				$this->notice( __( 'Content page id is not a valid page id', 'machete' ), 'warning' );
				return false;
			}
		}else{
			$settings['page_id'] = '';
		}

		function is_equal_array($a, $b) {
		    return (
		         is_array($a) && is_array($b) && 
		         count($a) == count($b) &&
		         array_diff($a, $b) === array_diff($b, $a)
		    );
		}

		if (isset($old_settings) && is_equal_array($old_settings, $settings)){
			$this->notice( __( 'No changes were needed.', 'machete' ), 'notice' );
			return false;
		}


		// option saved WITH autoload
		if(update_option( 'machete_maintenance_settings', $settings, 'yes' )){
			$this->save_success_notice();
			return true;
		}else{
			$this->save_error_notice();
			return false;
		}

	}

}
$machete->modules['maintenance'] = new machete_maintenance_module();

