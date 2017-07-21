<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

add_action( 'plugins_loaded', 'machete_load_plugin_textdomain' );

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


function machete_footer_text() {
    /* translators: %s: five stars */
    return sprintf( __( 'If you like <strong>Machete</strong> please %sleave us a rating of 5 stars%s. Thank you in advance!', 'machete' ), '<a href="https://wordpress.org/support/plugin/machete/reviews/#new-post" target="_blank">','</a>' );
}

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




function machete_menu() {
	
  /*
  if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
  */

  add_menu_page(
  	'Machete',
  	'Machete',
  	'publish_posts', // targeting Author role
  	'machete',
  	'machete_about_page_content',
  	plugin_dir_url( __FILE__ ) . 'img/machete.svg'
  );

}
add_action('admin_menu', 'machete_menu');



function machete_admin_tabs($current = '') {
  global $machete_modules;

  $is_admin = current_user_can('manage_options') ? true : false;
  	
  echo '<h2 class="nav-tab-wrapper">';
	foreach($machete_modules as $slug => $args) {

    if (!$is_admin && ($args['role'] == 'admin')) continue;
    //if (!$is_admin && ($args['role'] == 'admin')) continue;

    if ( ! $args['is_active'] ) continue;
    if ( ! $args['has_config'] ) continue;

    $slug = 'machete-'.$slug;
		if ($slug == $current){
			echo '<a href="#" class="nav-tab-active nav-tab '.$slug.'-tab">'.$args['title'].'</a>';
		}else{
			echo '<a href="'.admin_url('admin.php?page='.$slug).'" class="nav-tab '.$slug.'-tab">'.$args['title'].'</a>';
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

require('inc/about/admin_functions.php');

foreach ($machete_modules as $machete_module => $args) {
    if ( ! $args['is_active'] ) continue;
    @require_once('inc/'.$machete_module.'/admin_functions.php');
}