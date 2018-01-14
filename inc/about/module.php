<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_about_module extends machete_module {
	
	function __construct(){
		$this->init( array(
			'slug' => 'about',
			'title' => __('About Machete','machete'),
			'full_title' => __('About Machete','machete'),
			//'is_active' => true,
			'has_config' => false,
			'can_be_disabled' => false,
			'role' => 'publish_posts' // targeting Author role
			)
		);
	}
	public function admin(){
		global $machete;
		// if this is called after the admin_menu hook, the modules you disable
		// are still shown in the side menu until you reload
		if (isset($_GET['machete-action'])){
		  	check_admin_referer( 'machete_action_' . $_GET['module'] );
			$machete->manage_modules($_GET['module'], $_GET['machete-action']);
		}
		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}
	public function frontend() {}
	public function register_sub_menu() {
		// this can't use the default register_sub_menu
		// it has to replace the parent Machete menu link
		add_submenu_page(
		  	'machete',
		    $this->params['full_title'],
		    $this->params['title'],
		    $this->params['role'],    
		    'machete',
		    'machete_about_page_content'
		);
	}


}
$machete->modules['about'] = new machete_about_module();