<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

function machete_directory_page() {
  add_submenu_page(
    'machete',
    __('Resource Directory','machete'),
    __('Resource Directory','machete'),
    'manage_options',
    'machete-directory',
    'machete_directory_page_content'
  );
}
add_action('admin_menu', 'machete_directory_page');

function machete_directory_page_content() {
	require('admin_content.php');
}