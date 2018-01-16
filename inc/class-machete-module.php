<?php
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class machete_module {
	public $params = array(
		'slug' => '',
		'title' => '',
		'full_title' => '',
		'description' => '',
		'is_active' => true,
		'has_config' => true,
		'can_be_disabled' => true,
		'role' => 'manage_options'
	);
	protected $settings = array();
	protected $default_settings = array();

	protected function init( $params = array() ){
		$this->params = array_merge($this->params, $params);
		if ( ! array_key_exists('path', $this->params)){
			$this->path = MACHETE_BASE_PATH.'inc/'.$this->params['slug'].'/';
		}else{
			$this->path = $this->params['path'];
		}
	}

	protected function read_settings(){
		if(!$this->settings = get_option('machete_'.$this->params['slug'].'_settings')){
			$this->settings = $this->default_settings;
		}else{
			$this->settings = array_merge($this->default_settings, $this->settings);
		}
		return $this->settings;
	}

	public function admin(){
		if ($this->params['has_config']){
			$this->read_settings();
		}
		require($this->path.'admin_functions.php');
		if ($this->params['has_config']){
			add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
		}
	}

	public function register_sub_menu() {
		add_submenu_page(
		  	'machete',
		    $this->params['full_title'],
		    $this->params['title'],
		    $this->params['role'],    
		    'machete-'.$this->params['slug'],
		    array(&$this, 'submenu_page_callback')
		  );
	}
	public function submenu_page_callback(){
		global $machete;
  		require($this->path.'admin_content.php');
  		add_filter('admin_footer_text', 'machete_footer_text');
	}

	public function frontend() {
		if ($this->params['has_config']){
			$this->read_settings();
		}
		require($this->path.'frontend_functions.php');
	}

	protected function save_settings( $settings = array(), $silent = false ) {
		return true;
	}

	protected function export(){
		return $this->read_settings();
	}

	protected function import( $settings = array() ){
		if ( $this->save_settings($settings, true) ){
			return __('Settings succesfully restored from backup', 'machete') . "\n";
		}else{
			return __('Error restoring settings backup', 'machete') . "\n";
		}
	}

	public function notice( $message, $level = 'info', $dismissible = true) {
		global $machete;
		$machete->notice( $message, $level, $dismissible);
	}

	protected function save_success_notice(){
		$this->notice(__( 'Options saved!', 'machete' ), 'success');
	}
	protected function save_error_notice(){
		$this->notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
	}
	protected function save_no_changes_notice(){
		$this->notice(__( 'No changes were needed.', 'machete' ), 'info');
	}

	

	/* utils */
	public function is_equal_array($a, $b) {
	    return (
	         is_array($a) && is_array($b) && 
	         count($a) == count($b) &&
	         array_diff($a, $b) === array_diff($b, $a)
	    );
	}
				
}
