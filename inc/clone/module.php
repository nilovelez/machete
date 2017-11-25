<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_clone_module extends machete_module {
	
	function __construct(){
		$this->init( array(
			'slug' => 'clone',
			'title' => __('Post & Page Cloner','machete'),
			'full_title' => __('Post & Page Cloner','machete'),
			'description' => __('Adds a "duplicate" link to post, page and most post types lists. Also adds "copy to new draft" function to the post editor.','machete'),
			//'is_active' => true,
			'has_config' => false,
			//'can_be_disabled' => true,
			//'role' => 'manage_options'
			)
		);
	}
	public function frontend() {
		return;
	}
}
$machete->modules['clone'] = new machete_clone_module();