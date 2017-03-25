<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

function machete_do_activation_redirect() {
  // Bail if no activation redirect
    if ( ! get_transient( '_machete_welcome_redirect' ) ) {
    return;
  }
  // Delete the redirect transient
  delete_transient( '_machete_welcome_redirect' );

  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }
  // Redirect to about page
  wp_safe_redirect( add_query_arg( array( 'page' => 'machete' ), admin_url( 'admin.php' ) ) );
}
add_action( 'admin_init', 'machete_do_activation_redirect' );


function machete_filter_plugin_action_links( $plugin_actions, $plugin_file ) {

  $new_actions = array();

  if ( basename( dirname( __FILE__ ) ) . '/machete.php' === $plugin_file ) {
    $new_actions['sc_settings'] = sprintf( __( '<a href="%s">Settings</a>', 'machete' ), esc_url( admin_url( 'admin.php?page=machete' ) ) );
  }
  return array_merge( $new_actions, $plugin_actions );
}
add_filter( 'plugin_action_links', 'machete_filter_plugin_action_links', 10, 2 );


function machete_remove_help_tabs() {
  if(!$screen = get_current_screen()) return;
  if (strpos($screen->id, 'machete') === false) return;
    $screen->remove_help_tabs();
}
add_action('admin_head', 'machete_remove_help_tabs' );



function machete_enqueue_custom_admin_style() {
        wp_register_style( 'custom_wp_admin_css', plugin_dir_url( __FILE__ ) . 'css/admin.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'machete_enqueue_custom_admin_style' );



if ( ! class_exists( 'Machete_Notice' ) ):
class Machete_Notice {
  private $message;
  private $css_classes;
  public function __construct( $message, $level = 'info', $dismissible = true) {
    $this->message = $message;
    
    if (!in_array($level, array('error','warning','info','success'))){
      $level = 'info';
    }
    $this->css_classes = 'notice notice-'.$level;
    if ($dismissible){
      $this->css_classes .= ' is-dismissible';
    }
    add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
  }
  public function display_admin_notice() {
    ?>
    <div class="<?php echo $this->css_classes ?>">
      <p><?php echo $this->message; ?></p>
    </div>
    <?php
  }
}
endif;











function machete_pages() {

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

  add_menu_page(
  	'Machete',
  	'Machete',
  	'manage_options',
  	'machete',
  	'machete_about_page_content',
  	plugin_dir_url( __FILE__ ) . 'img/machete.svg'
  	);

  add_submenu_page(
    'machete',
    __('About Machete','machete'),
    __('About Machete','machete'),
    'manage_options',
    'machete',
    'machete_about_page_content'
  );

  add_submenu_page(
  	'machete',
    __('WordPress Optimization','machete'),
    __('Optimization','machete'),
    'manage_options',
    'machete-cleanup',
    'machete_cleanup_page_content'
  );

  add_submenu_page(
  	'machete',
  	__('Cookie Law Warning','machete'),
    __('Cookie Law','machete'),
    'manage_options',
    'machete-cookies',
    'machete_cookies_page_content'
  );

  add_submenu_page(
  	'machete',
  	__('Analytics and Custom Code','machete'),
    __('Analytics & Code','machete'),
    'manage_options',
    'machete-utils',
    'machete_utils_page_content'
  );

  add_submenu_page(
    'machete',
    __('Coming Soon & Maintenance Mode','machete'),
    __('Maintenance Mode','machete'),
    'manage_options',
    'machete-maintenance',
    'machete_maintenance_page_content'
  );
  /*
  add_submenu_page(
    'machete',
    __('Resource Directory','machete'),
    __('Resource Directory','machete'),
    'manage_options',
    'machete-directory',
    'machete_directory_page_content'
  );
  */
  if (defined ('MACHETE_POWERTOOLS_INIT') ) {
    add_submenu_page(
      'machete',
      __('PowerTools','machete'),
      '<span style="color: #ff9900">'.__('PowerTools','machete').'</span>',
      'manage_options',
      'machete-powertools',
      'machete_powertools_page_content'
    );
  }
}
add_action('admin_menu', 'machete_pages');



function machete_admin_tabs($current = '') {
	$tabs = array(
		'machete-cleanup' => __('Optimization','machete'),
		'machete-cookies' => __('Cookie Law','machete'),
		'machete-utils' => __('Analytics & Code','machete'),
		//'machete-maintenance' => __('Maintenance Mode','machete'),
    'machete-directory' => __('Resource','machete')
    
    //'machete' => __('About Machete','machete')
	);
  if (defined ('MACHETE_POWERTOOLS_INIT') ) {
    $tabs['machete-powertools'] = __('PowerTools','machete');
  }

	echo '<h2 class="nav-tab-wrapper">';
	foreach($tabs as $slug => $title) {
		if ($slug == $current){
			echo '<a href="#" class="nav-tab-active nav-tab '.$slug.'-tab">'.$title.'</a>';
		}else{
			echo '<a href="'.admin_url('admin.php?page='.$slug).'" class="nav-tab '.$slug.'-tab">'.$title.'</a>';
		}
	}
	echo '</h2>';
}


function machete_save_success() {
  new Machete_Notice(__( 'Options saved!', 'machete' ), 'success');
}

function machete_action_success() {
  new Machete_Notice(__( 'Action succesfully executed!', 'machete' ), 'success');
}


//update_option( $option, $new_value, $autoload );
if (isset($_POST['machete-cleanup-saved'])){

  check_admin_referer( 'machete_save_cleanup' );
	require('inc/cleanup/admin_functions.php');
	
	if(machete_cleanup_save_options()){
		add_action( 'admin_notices', 'machete_save_success' );
	}
}

function machete_cleanup_page_content() {
	require('inc/cleanup/admin_content.php');
}

/* Machete Cookies Bar */
if (isset($_POST['machete-cookies-saved'])){

  check_admin_referer( 'machete_save_cookies' );
	require('inc/cookies/admin_functions.php');
	
	if(machete_cookies_save_options()){
		add_action( 'admin_notices', 'machete_save_success' );
	}
}
function machete_cookies_page_content() {
	require('inc/cookies/admin_content.php');
}

/* Machete Utils */
if (isset($_POST['machete-utils-saved'])){

  check_admin_referer( 'machete_save_utils' );
	require('inc/utils/admin_functions.php');
	
	if(machete_utils_save_options()){
		add_action( 'admin_notices', 'machete_save_success' );
	}
}
function machete_utils_page_content() {
	require('inc/utils/admin_content.php');
}


/* Machete Maintenance */
if (isset($_POST['machete-maintenance-saved'])){

  check_admin_referer( 'machete_save_maintenance' );
  require('inc/maintenance/admin_functions.php');
  
  if(machete_maintenance_save_options()){
    add_action( 'admin_notices', 'machete_save_success' );
  }
}
function machete_maintenance_page_content() {
  require('inc/maintenance/admin_content.php');
}




/* Machete About */
function machete_about_page_content() {
	require('inc/about/admin_content.php');
}

/* Machete Directory */
function machete_directory_page_content() {
  require('inc/directory/admin_content.php');
}


/* Machete PowerTools */
if (isset($_POST['machete-powertools-saved'])){

  check_admin_referer( 'machete_save_powertools' );
  require('inc/powertools/admin_functions.php');
  
  if(machete_powertools_save_options()){
    add_action( 'admin_notices', 'machete_save_success' );
  }
}

if (isset($_POST['machete-powertools-action'])){

  check_admin_referer( 'machete_powertools_action' );
  require('inc/powertools/admin_functions.php');
  
  if(machete_powertools_do_action()){
    add_action( 'admin_notices', 'machete_action_success' );
  }
}

function machete_powertools_page_content() {
  require('inc/powertools/admin_content.php');
}


// Machete cleanup actions specific to the back-end
if(
  ($machete_cleanup_settings = get_option('machete_cleanup_settings')) &&
  (count($machete_cleanup_settings) > 0)){

  if (in_array('emojicons',$machete_cleanup_settings)) {
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    //remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    //remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    //remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    //remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    add_filter( 'tiny_mce_plugins', 'machete_disable_emojicons_tinymce' );

  }

  if (in_array('pdf_thumbnails',$machete_cleanup_settings)) {
    function machete_disable_pdf_previews() {
      $fallbacksizes = array();
      return $fallbacksizes;
    }
    add_filter('fallback_intermediate_image_sizes', 'machete_disable_pdf_previews');
  }

  if (in_array('limit_revisions',$machete_cleanup_settings)) {
    if ( defined('WP_POST_REVISIONS') && (WP_POST_REVISIONS != false)) {
      add_filter( 'wp_revisions_to_keep', 'machete_revisions_filter', 10, 2 );
      function machete_revisions_filter( $num, $post ) {
         return 5;
      }
    }
  }

}

if ( ! function_exists( 'machete_disable_emojicons_tinymce' ) ) :
function machete_disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  }
  return array();
}
endif;


// Machete powertools actions specific to the back-end
if(
  ($machete_powertools_settings = get_option('machete_powertools_settings')) &&
  (count($machete_powertools_settings) > 0)){

    // enable page_excerpts
    if (in_array('page_excerpts',$machete_powertools_settings)) {
      add_post_type_support( 'page', 'excerpt' );
    }

}