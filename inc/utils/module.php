<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_utils_module extends machete_module {

	function __construct(){
		$this->init( array(
			'slug' => 'utils',
			'title' => __('Analytics & Code','machete'),
			'full_title' => __('Analytics and Custom Code','machete'),
			'description' => __('Google Analytics tracking code manager and a simple editor to insert HTML, CSS and JS snippets or site verification tags.','machete'),
			//'is_active' => true,
			//'has_config' => true,
			//'can_be_disabled' => true,
			// 'role' => 'manage_options'
			)
		);
	}

}
$machete->modules['utils'] = new machete_utils_module();