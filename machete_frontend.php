<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( is_user_logged_in() ){
	// only logged in users need l10n
	add_action( 'plugins_loaded', 'machete_load_plugin_textdomain' );
}

foreach ($machete->modules as &$module) {
    if ( ! $module->params['is_active'] ) continue;
    $module->frontend();
}