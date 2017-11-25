<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

add_action( 'plugins_loaded', 'machete_load_plugin_textdomain' );

function machete_do_activation_redirect() {
  
  // Bail if no activation redirect
  if (get_option( 'machete_activation_welcome') == 'pending' ){
    delete_option( 'machete_activation_welcome' );
    
    // Bail if activating from network, or bulk
    if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
      return;
    }

    // Redirect to about page
    wp_safe_redirect( add_query_arg( array( 'page' => 'machete' ), admin_url( 'admin.php' ) ) );
  }
  
}
add_action( 'admin_init', 'machete_do_activation_redirect' );


function machete_footer_text() {
    /* translators: %s: five stars */
    return ' '.sprintf( __( 'If you like <strong>Machete</strong>, please %sleave us a rating of %s. Thank you!', 'machete' ), '<a href="https://wordpress.org/support/plugin/machete/reviews/#new-post" target="_blank">','5&starf;</a>' ). ' '  ;
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
    //$screen->remove_help_tabs();
}
add_action('admin_head', 'machete_remove_help_tabs' );



function machete_enqueue_custom_admin_style() {
  wp_register_style( 'machete_admin_css', plugin_dir_url( __FILE__ ) . 'css/admin_v2.css', false, '2.0.0' );
  wp_enqueue_style( 'machete_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'machete_enqueue_custom_admin_style' );


function machete_menu() {
	global $machete;
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


function machete_about_page_content() {
  global $machete;
  require( MACHETE_BASE_PATH . 'inc/about/admin_content.php' );
  add_filter('admin_footer_text', 'machete_footer_text');
}


function machete_save_success() {
  new Machete_Notice(__( 'Options saved!', 'machete' ), 'success');
}

function machete_action_success() {
  new Machete_Notice(__( 'Action succesfully executed!', 'machete' ), 'success');
}

foreach ($machete->modules as $module) {
    if ( ! $module->params['is_active'] ) continue;
    $module->admin();
}

foreach ($machete_modules as $machete_module => $args) {
    if ( ! $args['is_active'] ) continue;
    @require_once('inc/'.$machete_module.'/admin_functions.php');
}