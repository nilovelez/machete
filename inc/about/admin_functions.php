<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

function machete_about_page() {
  add_submenu_page(
    'machete',
    __('About Machete','machete'),
    __('About Machete','machete'),
    'publish_posts', // targeting Author role
    'machete',
    'machete_about_page_content'
  );
}
add_action('admin_menu', 'machete_about_page');

function machete_about_page_content() {
	global $machete_modules;
	require('admin_content.php');
}

function machete_manage_modules ($module, $action){
	global $machete_modules;
	
	if (empty($module) || empty($action) || in_array($action, array('enable','disable'))) {
		wp_die(__( 'Bad request', 'machete' ));
	}

	if ( ! array_key_exists( $module, $machete_modules)) {
		new Machete_Notice(__( 'Uknown module:', 'machete' ) . ' ' . $module, 'error');
		return false;
	}

	if(! $disabled_modules = get_option('machete_disabled_modules')){
		$disabled_modules = array();
	}
	
	if ($action == 'deactivate') {
		if(in_array($module, $disabled_modules)){
			new Machete_Notice(__( 'Nothing to do. The module was already disabled.', 'machete' ), 'notice');
			return false;
		}
		if ( ! $machete_modules[$module]['can_be_disabled'] ) {
			new Machete_Notice(__( 'Sorry, you can\'t disable that module', 'machete' ), 'warning');
			return false;
		} 

		$disabled_modules[] = $module;

		if (update_option('machete_disabled_modules',$disabled_modules)){
			$machete_modules[$module]['is_active'] = false;
			return true;

		}else{
			new Machete_Notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
			return false;
		}


	}

	if ($action == 'activate') {
		if($machete_modules[$module]['is_active']){
			new Machete_Notice(__( 'Nothing to do. The module was already active.', 'machete' ), 'notice');
			return false;
		}
		if ( $module == 'powertools' ) {
			new Machete_Notice(__( 'Sorry, you can\'t enable that module', 'machete' ), 'warning');
			return false;
		} 

		$disabled_modules = array_diff($disabled_modules, array($module));

		if (update_option('machete_disabled_modules',$disabled_modules)){
			$machete_modules[$module]['is_active'] = true;
			return true;

		}else{
			new Machete_Notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
			return false;
		}


	}



	

}

if (isset($_GET['machete-action'])){
  	check_admin_referer( 'machete_action_' . $_GET['module'] );
	if(machete_manage_modules($_GET['module'], $_GET['machete-action'])){
		new Machete_Notice(__( 'Options saved!', 'machete' ), 'success');
	}
}