<?php
/**
 * Machete code only usable in the WordPress front-end

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Call to frontend() method of all active modules.
foreach ( $machete->modules as $machete_module ) {
	if ( ! $machete_module->params['is_active'] ) {
		continue;
	}
	$machete_module->frontend();
}
