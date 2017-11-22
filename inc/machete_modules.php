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
		$this->path = MACHETE_BASE_PATH.'inc/'.$this->params['slug'].'/';
	}

	protected function read_settings(){
		if(!$this->settings = get_option('machete_'.$this->params['slug'].'_settings')){
			$this->settings = $this->default_settings;
		}else{
			$this->settings = array_merge($this->default_settings, $this->settings);
		}
		return($this->settings);
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

	protected function frontend() {
		if ($this->params['has_config']){
			$this->read_settings();
		}
		require($this->path.'frontend_functions.php');
	}

	protected function export(){
		return $this->settings;
	}

	protected function import(){

	}

	public $notice_message;
	public $notice_class;
	public function notice( $message, $level = 'info', $dismissible = true) {

		$this->notice_message = $message;

		if (!in_array($level, array('error','warning','info','success'))){
			$level = 'info';
		}
		$this->notice_class = 'notice notice-'.$level;
		if ($dismissible){
			$this->notice_class .= ' is-dismissible';
		}
		add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}

	protected function save_success_notice(){
		$this->notice(__( 'Options saved!', 'machete' ), 'success');
	}
	protected function save_error_notice(){
		$this->notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
	}
				
	public function display_notice() {
		if (!empty($this->notice_message)){
		?>
		<div class="<?php echo $this->notice_class ?>">
			<p><?php echo $this->notice_message; ?></p>
		</div>
		<?php }
	}
}


require_once('about/module.php');
require_once('cleanup/module.php');
require_once('cookies/module.php');

require_once('utils/module.php');
require_once('maintenance/module.php');
require_once('clone/module.php');
//var_dump($machete);

require_once('importexport/module.php');
//require_once('importexport/module.php');



/*
$machete_modules['importexport'] = array(
	'title' => __('Import/Export Options','machete'),
	'full_title' => __('Import/Export Options','machete'),
	'description' => __('','machete'),
	'is_active' => true,
	'has_config' => true,
	'can_be_disabled' => false,
	'role' => 'admin'
);
*/


$machete_modules['powertools'] = array(
	'title' => __('PowerTools','machete'),
	'full_title' => __('Machete PowerTools','machete'),
	'description' => __('Machete PowerTools is an free upgrade module targeted at WordPress developers and power users.','machete'),
	'is_active' => false,
	'has_config' => true,
	'can_be_disabled' => false,
	'role' => 'admin'
);

if($machete_disabled_modules = get_option('machete_disabled_modules')){
	foreach ($machete_disabled_modules as $module) {
		/*
		if (isset($machete_modules[$module]) && $machete_modules[$module]['can_be_disabled']){
			$machete_modules[$module]['is_active'] = false;
		}
		*/
		if (isset($machete->modules[$module]) && $machete->modules[$module]->params['can_be_disabled']){
			$machete->modules[$module]->params['is_active'] = false;
		}
	}
}

if (defined('MACHETE_POWERTOOLS_INIT')) {
	$machete_modules['powertools']['is_active'] = true;
	$machete_modules['powertools']['description'] = __('Machete PowerTools are now active! Enjoy your new toy!','machete');
}


