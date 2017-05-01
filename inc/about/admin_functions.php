<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

function machete_about_page() {
  add_submenu_page(
    'machete',
    __('About Machete','machete'),
    __('About Machete','machete'),
    'manage_options',
    'machete',
    'machete_about_page_content'
  );
}
add_action('admin_menu', 'machete_about_page');

function machete_about_page_content() {
	require('admin_content.php');
}