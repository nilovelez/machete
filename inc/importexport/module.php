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
}
$machete->modules['importexport'] = new machete_importexport_module;