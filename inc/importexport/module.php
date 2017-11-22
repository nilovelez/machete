<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_importexport_module extends machete_module {
	
	function __construct(){
		$this->init( array(
			'slug' => 'importexport',
			'title' => __('Import/Export Options','machete'),
			'full_title' => __('Import/Export Options','machete'),
			'description' => __('','machete'),
			//'is_active' => true,
			//'has_config' => true,
			'can_be_disabled' => false,
			//'role' => 'manage_options'
			)
		);

		


	}

	public function admin(){
		global $machete;
		$this->exportable_modules = array();
		foreach ($machete->modules as $module) {

			$params = $module->params;
			if ( 'importexport' == $params['slug'] ) continue;
		    if ( ! $params['has_config'] ) continue;

		    $this->exportable_modules[$params['slug']] = array(
		    	'title' => $params['title'],
		    	'full_title' => $params['full_title'],
		    	'checked' => true
		    );

		}

		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}


	protected function export() {
		global $machete;
		
		$export = array();

		foreach ($this->exportable_modules as $module => $options) {
			$params = $machete->modules[$module]->params;

			$export[$params['slug']] = array(
		    	'is_active' => $params['is_active'],
		    	'settings' => $machete->modules[$params['slug']]->export()
		    ); 
		    
		}
		return base64_encode(serialize($export));


	}
}
$machete->modules['importexport'] = new machete_importexport_module;