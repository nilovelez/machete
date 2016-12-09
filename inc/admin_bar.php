<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if($machete_maintenance_settings = get_option('machete_maintenance_settings') ) {
    if (!empty($machete_maintenance_settings['site_status']) &&
     ($machete_maintenance_settings['site_status'] != 'online') ){

		function machete_admin_bar_scripts() {
		    wp_register_style(
			'machete-maintenance-styles',
			MACHETE_BASE_URL.'css/maintenance-admin-bar.css',
			false);
		    wp_enqueue_style('machete-maintenance-styles');
		}
		add_action( 'wp_enqueue_scripts', 'machete_admin_bar_scripts' );
		add_action( 'admin_enqueue_scripts', 'machete_admin_bar_scripts' );

		function machete_coming_soon_admin_bar() {
			global $wp_admin_bar;

			if (current_user_can('manage_options')){
		    	$href = admin_url('admin.php?page=machete-maintenance');
		    }else{
		    	$href = admin_url('index.php');
		    }
		    
			//Add the main siteadmin menu item
		    $wp_admin_bar->add_menu( array(
		        'id'     => 'machete-maintenance-notice',
		        'href' => $href,
		        'parent' => 'top-secondary',
		        'title'  => __('Coming Soon','machete'),
		        'meta'   => array( 'class' => 'machete-coming-soon-active' ),
		    ) );
		}		    
		function machete_maintenance_admin_bar() {
		    global $wp_admin_bar;

		    if (current_user_can('manage_options')){
		    	$href = admin_url('admin.php?page=machete-maintenance');
		    }else{
		    	$href = admin_url('index.php');
		    }
		     
			//Add the main siteadmin menu item
		    $wp_admin_bar->add_menu( array(
		        'id'     => 'machete-maintenance-notice',
		        'href' => $href,
		        'parent' => 'top-secondary',
		        'title'  => __('Maintenance','machete'),
		        'meta'   => array( 'class' => 'machete-maintenance-active' ),
		    ) );
		}

		if ($machete_maintenance_settings['site_status'] == 'maintenance'){
			add_action( 'admin_bar_menu','machete_maintenance_admin_bar', 1000 );
		}
		if ($machete_maintenance_settings['site_status'] == 'coming_soon'){
			add_action( 'admin_bar_menu','machete_coming_soon_admin_bar', 1000 );
		}
	}
}