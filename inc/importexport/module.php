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

	public function frontend() {
		return;
	}

	public function admin(){
		global $machete;
		$this->checked_modules = array();
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

		if (isset($_POST['machete-importexport-export'])){
			// ToDo: nonce
		  	check_admin_referer( 'machete_importexport_export' );
		  	if (isset( $_POST['moduleChecked'] ) && ( count($_POST['moduleChecked']) > 0) ){
				$this->checked_modules = $_POST['moduleChecked'];
				$export_file = $this->export();
				
				header('Content-disposition: attachment; filename=machete_export.json');
				header('Content-Type: application/json');
				header('Pragma: no-cache');
				echo $export_file;
				exit();
		  	}
		}
		$this->all_exportable_modules_checked = true;
		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}


	protected function export() {
		global $machete;
		
		/*
		echo '<pre>';
		print_r ($this->exportable_modules);
		print_r ($this->checked_modules);
		echo '</pre>';
		*/


		$export = array();

		foreach ($this->checked_modules as $slug){
			if ( !in_array( $slug ,  array_keys( $this->exportable_modules ) ) ) continue;

			$params = $machete->modules[$slug]->params;

			$export[$params['slug']] = array(
		    	'is_active' => $params['is_active'],
		    	'settings' => $machete->modules[$slug]->export()
		    ); 
		    
		}
		//return base64_encode(serialize($export));
		return json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

	}
}
$machete->modules['importexport'] = new machete_importexport_module;