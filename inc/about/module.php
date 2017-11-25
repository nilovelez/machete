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
		
		// if this is called after the admin_menu hook, the modules you disable
		// are still shown in the side menu until you reload
		if (isset($_GET['machete-action'])){
		  	check_admin_referer( 'machete_action_' . $_GET['module'] );
			$this->manage_modules($_GET['module'], $_GET['machete-action']);
		}
		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}
	public function frontend() {}
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

	protected function manage_modules ($module, $action){
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
			if ( ! $machete->modules[$module]->params['can_be_disabled'] ) {
				$this->notice(__( 'Sorry, you can\'t disable that module', 'machete' ), 'warning');
				return false;
			} 

			$disabled_modules[] = $module;

			if (update_option('machete_disabled_modules',$disabled_modules)){
				$machete->modules[$module]->params['is_active'] = false;
				$this->save_success_notice();
				return true;

			}else{
				$this->save_error_notice();
				return false;
			}


		}

		if ($action == 'activate') {
			if($machete->modules[$module]->params['is_active']){
				$this->notice(__( 'Nothing to do. The module was already active.', 'machete' ), 'notice');
				return false;
			}
			if ( $module == 'powertools' ) {
				$this->notice(__( 'Sorry, you can\'t enable that module', 'machete' ), 'warning');
				return false;
			} 

			$disabled_modules = array_diff($disabled_modules, array($module));

			if (update_option('machete_disabled_modules',$disabled_modules)){
				$machete->modules[$module]->params['is_active'] = true;
				$this->save_success_notice();
				return true;

			}else{
				$this->save_error_notice();
				return false;
			}
		}
	}


}
$machete->modules['about'] = new machete_about_module();