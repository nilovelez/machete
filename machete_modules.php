<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class machete_module {
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
	public $settings = array();
	public $default_settings = array();

	private function read_settings(){
		if(!$this->settings = get_option('machete_'.$this->params['slug'].'_settings')){
			$this->settings = $this->default_settings;
		};
	}

	public function admin(){
		if ($this->params['has_config']){
			$this->read_settings();
		}
		require('inc/'.$this->params['slug'].'/admin_functions.php');
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
		require('inc/'.$this->params['slug'].'/admin_content.php');
  		add_filter('admin_footer_text', 'machete_footer_text');
	}

	public function frontend() {
		if ($this->params['has_config']){
			$this->read_settings();
		}
		require('inc/'.$this->params['slug'].'/frontend_functions.php');
	}

	public function export(){

	}

	public function import(){

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
		//add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}

	public function save_success_notice(){
		$this->notice(__( 'Options saved!', 'machete' ), 'success');
	}
	public function save_error_notice(){
		$this->notice(__( 'Error saving configuration to database.', 'machete' ), 'error');
	}
				

	function display_notice() {

		if (!empty($this->notice_message)){
		?>
		<div class="<?php echo $this->notice_class ?>">
			<p><?php echo $this->notice_message; ?></p>
		</div>
		<?php }
	}
}


require_once('inc/about/module.php');
require_once('inc/cleanup/module.php');

//var_dump($machete);


$machete_modules['cookies'] = array(
	'title' => __('Cookie Law','machete'),
	'full_title' => __('Cookie Law Warning','machete'),
	'description' => __('Light and responsive cookie law warning bar that won\'t affect your PageSpeed score and plays well with static cache plugins.','machete'),
	'is_active' => true,
	'has_config' => true,
	'can_be_disabled' => true,
	'role' => 'author'
);
$machete_modules['utils'] = array(
	'title' => __('Analytics & Code','machete'),
	'full_title' => __('Analytics and Custom Code','machete'),
	'description' => __('Google Analytics tracking code manager and a simple editor to insert HTML, CSS and JS snippets or site verification tags.'),
	'is_active' => true,
	'has_config' => true,
	'can_be_disabled' => true,
	'role' => 'admin'
);
$machete_modules['maintenance'] = array(
	'title' => __('Maintenance Mode','machete'),
	'full_title' => __('Maintenance Mode','machete'),
	'description' => __('Customizable maintenance page to close your site during updates or development. It has a "magic link" to grant temporary access.','machete'),
	'is_active' => true,
	'has_config' => true,
	'can_be_disabled' => true,
	'role' => 'author'
);

require_once('inc/clone/module.php');


$machete_modules['importexport'] = array(
	'title' => __('Import/Export Options','machete'),
	'full_title' => __('Import/Export Options','machete'),
	'description' => __('','machete'),
	'is_active' => true,
	'has_config' => true,
	'can_be_disabled' => false,
	'role' => 'admin'
);


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
		if (isset($machete_modules[$module]) && $machete_modules[$module]['can_be_disabled']){
			$machete_modules[$module]['is_active'] = false;
		}
		if (isset($machete->modules[$module]) && $machete->modules[$module]['can_be_disabled']){
			$machete->modules[$module]['is_active'] = false;
		}
	}
}

if (defined('MACHETE_POWERTOOLS_INIT')) {
	$machete_modules['powertools']['is_active'] = true;
	$machete_modules['powertools']['description'] = __('Machete PowerTools are now active! Enjoy your new toy!','machete');
}


