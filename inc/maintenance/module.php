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

	}

	public function frontend(){
		$this->read_settings();
		if( count( $this->settings ) > 0 ) { 
			require($this->path . 'frontend_functions.php' );
			if(is_admin_bar_showing()){
        		require_once($this->path . 'admin_bar.php');
    		}
    		$machete_maintenance = new machete_maintenance_page($this->settings);
		}
	}

	public function admin(){

		$this->read_settings();
		
		// the maintenance token should be saved as soon as possible
		//to keep it from changing on every page load
		if ( ! get_option('machete_'.$this->params['slug'].'_settings') ){
			// default option values saved WITHOUT autoload
    		update_option( 'machete_'.$this->params['slug'].'_settings', $this->default_settings, 'no' );
		}

		if ( isset( $_POST['machete-maintenance-saved'] ) ){
		    check_admin_referer( 'machete_save_maintenance' );
		  	$this->save_settings();
		}

		$this->preview_base_url = home_url( '/?mct_preview=' . wp_create_nonce('maintenance_preview_nonce') );

		if ($this->settings['page_id']) {
			$this->preview_url = $this->preview_base_url . '&mct_page_id=' . $this->settings['page_id'];
		} else {
			$this->preview_url = $this->preview_base_url;
		}

		$this->magic_base_url = home_url( '/?mct_token=');
		$this->magic_url      = home_url( '/?mct_token=' . $this->settings['token']);



		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );

		if(is_admin_bar_showing()){
			require_once($this->path . 'admin_bar.php');
		}
	}

	protected function save_settings() {

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
			$this->settings = $settings;
			$this->save_success_notice();
			return true;
		}else{
			$this->save_error_notice();
			return false;
		}

	}

}
$machete->modules['maintenance'] = new machete_maintenance_module();