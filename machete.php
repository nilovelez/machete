<?php
/*
Plugin Name: Machete
Plugin URI: https://machetewp.com
Description: Machete is a lean and simple suite of tools that solve common WordPress anoyances: cookie bar, tracking codes, header cleanup
Version: 3.0.1
Author: Nilo Velez
Author URI: http://www.nilovelez.com
License: WTFPL
License URI: http://www.wtfpl.net/txt/copying/

Text Domain: machete
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$machete_get_upload_dir = wp_upload_dir();
define('MACHETE_BASE_PATH', plugin_dir_path( __FILE__ ));
define('MACHETE_RELATIVE_BASE_PATH', substr(MACHETE_BASE_PATH, strlen(ABSPATH)-1));
define('MACHETE_BASE_URL',  plugin_dir_url( __FILE__ ));

define('MACHETE_DATA_PATH', $machete_get_upload_dir['basedir'].'/machete/');
define('MACHETE_RELATIVE_DATA_PATH', substr(MACHETE_DATA_PATH, strlen(ABSPATH)-1));
define('MACHETE_DATA_URL',  $machete_get_upload_dir['baseurl'].'/machete/');


register_activation_hook( __FILE__, function(){
	add_option( 'machete_activation_welcome', 'pending');
});

function machete_load_plugin_textdomain() {
    load_plugin_textdomain( 'machete', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

class machete {
	public $modules = array();

	public function __construct(){
		add_action('init',array($this,'init'));
	}

	public function init(){
		global $machete;
		
		require ('inc/machete_modules.php');

		if ( ! is_admin() ) {
			define('MACHETE_FRONT_INIT',true);
			require_once('machete_frontend.php');
		} else {
			define('MACHETE_ADMIN_INIT',true);
			require_once('machete_admin.php');	
		}
	}

	function admin_tabs($current = '') {
				
		$is_admin = current_user_can('manage_options') ? true : false;

		echo '<h2 class="nav-tab-wrapper">';
		foreach($this->modules as $module) {

			$params = $module->params;

			if (!$is_admin && ($params['role'] == 'manage_options')) continue;
			
			if ( ! $params['is_active'] ) continue;
			if ( ! $params['has_config'] ) continue;

			$slug = 'machete-'.$params['slug'];
			if ($slug == $current){
				echo '<a href="#" class="nav-tab-active nav-tab '.$slug.'-tab">'.$params['title'].'</a>';
			}else{
				echo '<a href="'.admin_url('admin.php?page='.$slug).'" class="nav-tab '.$slug.'-tab">'.$params['title'].'</a>';
			}
		}
		echo '</h2>';
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
				
	public function display_notice() {
		if (!empty($this->notice_message)){
		?>
		<div class="<?php echo $this->notice_class ?>">
			<p><?php echo $this->notice_message; ?></p>
		</div>
		<?php }
	}

	public function manage_modules ($module, $action, $silent = false){
		
		if (empty($module) || empty($action) || in_array($action, array('enable','disable'))) {
			if (!$silent) $this->notice(__( 'Bad request', 'machete' ), 'error');
			return false;
		}

		if ( ! array_key_exists( $module, $this->modules)) {
			if (!$silent) $this->notice(__( 'Uknown module:', 'machete' ) . ' ' . $module, 'error');
			return false;
		}

		if(! $disabled_modules = get_option('machete_disabled_modules')){
			$disabled_modules = array();
		}
		
		if ($action == 'deactivate') {
			if(in_array($module, $disabled_modules)){
				if (!$silent) $this->notice(__( 'Nothing to do. The module was already disabled.', 'machete' ), 'notice');
				return false;
			}
			if ( ! $this->modules[$module]->params['can_be_disabled'] ) {
				if (!$silent) $this->notice(__( 'Sorry, you can\'t disable that module', 'machete' ), 'warning');
				return false;
			} 

			$disabled_modules[] = $module;

			if (update_option('machete_disabled_modules',$disabled_modules)){
				$this->modules[$module]->params['is_active'] = false;
				if (!$silent) $this->notice(sprintf(__('Module %s disabled succesfully', 'machete'),
					$this->modules[$module]->params['title'] ), 'success');
				return true;

			}else{
				if (!$silent) $this->notice(__( 'Error saving configuration to database.', 'machete' ), 'error');;
				return false;
			}


		}

		if ($action == 'activate') {
			if($this->modules[$module]->params['is_active']){
				if (!$silent) $this->notice(__( 'Nothing to do. The module was already active.', 'machete' ), 'notice');
				return false;
			}
			if ( $module == 'powertools' ) {
				if (!$silent) $this->notice(__( 'Sorry, you can\'t enable that module', 'machete' ), 'warning');
				return false;
			} 

			$disabled_modules = array_diff($disabled_modules, array($module));

			if (update_option('machete_disabled_modules',$disabled_modules)){
				$this->modules[$module]->params['is_active'] = true;
				if (!$silent) $this->notice(sprintf(__('Module %s enabled succesfully', 'machete'),
					$this->modules[$module]->params['title'] ), 'success');
				return true;

			}else{
				if (!$silent) $this->notice(__( 'Error saving configuration to database.', 'machete' ), 'error');;
				return false;
			}
		}
	}

}
$machete = new machete();