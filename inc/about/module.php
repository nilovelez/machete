<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_about_module extends machete_module {
	function __construct(){
		$this->params = array_merge($this->params, array(
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
		require('admin_functions.php');
		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}
	public function register_sub_menu() {
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

