<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_cleanup_module extends machete_module {

	function __construct(){
		$this->params = array_merge($this->params, array(
			'slug' => 'cleanup',
			'title' => __('Optimization','machete'),
			'full_title' => __('WordPress Optimization','machete'),
			'description' => __('Reduces much of the legacy code bloat in WordPress page headers. It also has some tweaks to make you site faster and safer.','machete'),
			//'is_active' => true,
			//'has_config' => true,
			//'can_be_disabled' => true,
			//'role' => 'manage_options'
			)
		);
		

	}

	function save_settings() {

		
		if (isset($_POST['optionEnabled'])){

			$settings = $_POST['optionEnabled'];

			for($i = 0; $i < count($settings); $i++){
				$settings[$i] = sanitize_text_field($settings[$i]);
			}
			
			if ($old_options = $this->settings){
				if(
					(0 == count(array_diff($settings, $old_options))) &&
					(0 == count(array_diff($old_options, $settings)))
					){
					// no removes && no adds
					$this->notice(__( 'No changes were needed.', 'machete' ), 'info');
					return false;
				}
			}
			if (update_option('machete_cleanup_settings',$settings)){
				$this->settings = $settings;
				$this->notice(__( 'Options saved!', 'machete' ), 'success');
				return true;
			}else{
				$this->notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
				return false;
			}

		}else{
			if (delete_option('machete_cleanup_settings')){
				$this->settings = array();
				$this->notice(__( 'Options saved!', 'machete' ), 'success');
				return true;
			}else{
				$this->notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
				return false;
			}
		}
		return false;
	}
}
$machete->modules['cleanup'] = new machete_cleanup_module();