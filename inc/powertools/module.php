<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_powertools_module extends machete_module {

	function __construct(){
		$this->init( array(
			'slug' => 'powertools',
			//'title' => __('PowerTools','machete'),
			'title' => '<span style="color: #ff9900">'.__('PowerTools','machete').'</span>',
			'full_title' => __('Machete PowerTools','machete'),
			'description' => __('Machete PowerTools is an free upgrade module targeted at WordPress developers and power users.','machete'),
			'is_active' => false,
			//'has_config' => true,
			'can_be_disabled' => false,
			// 'role' => 'manage_options'
			)
		);
	}

}
$machete->modules['powertools'] = new machete_powertools_module;
