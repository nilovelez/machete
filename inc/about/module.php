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

	function manage_modules ($module, $action){
		global $machete;
		//global $machete_modules;
		
		if (empty($module) || empty($action) || in_array($action, array('enable','disable'))) {
			wp_die(__( 'Bad request', 'machete' ));
		}

		if ( ! array_key_exists( $module, $machete->modules)) {
			$this->notice(__( 'Uknown module:', 'machete' ) . ' ' . $module, 'error');
			return false;
		}

		if(! $disabled_modules = get_option('machete_disabled_modules')){
			$disabled_modules = array();
		}
		
		if ($action == 'deactivate') {
			if(in_array($module, $disabled_modules)){
				$this->notice(__( 'Nothing to do. The module was already disabled.', 'machete' ), 'notice');
				return false;
			}
			if ( ! $machete->modules[$module]['can_be_disabled'] ) {
				$this->notice(__( 'Sorry, you can\'t disable that module', 'machete' ), 'warning');
				return false;
			} 

			$disabled_modules[] = $module;

			if (update_option('machete_disabled_modules',$disabled_modules)){
				$machete->modules[$module]['is_active'] = false;
				$this->notice(__( 'Options saved!', 'machete' ), 'success');
				return true;

			}else{
				$this->notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
				return false;
			}


		}

		if ($action == 'activate') {
			if($machete_modules[$module]['is_active']){
				$this->notice(__( 'Nothing to do. The module was already active.', 'machete' ), 'notice');
				return false;
			}
			if ( $module == 'powertools' ) {
				$this->notice(__( 'Sorry, you can\'t enable that module', 'machete' ), 'warning');
				return false;
			} 

			$disabled_modules = array_diff($disabled_modules, array($module));

			if (update_option('machete_disabled_modules',$disabled_modules)){
				$machete->modules[$module]['is_active'] = true;
				$this->notice(__( 'Options saved!', 'machete' ), 'success');
				return true;

			}else{
				$this->notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
				return false;
			}
		}
	}


}
$machete->modules['about'] = new machete_about_module();

