<?php
/**
 * Machete Directory Module class
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete Directory Module class
 */
class MACHETE_DIRECTORY_MODULE extends MACHETE_MODULE {
	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init( array(
			'slug'        => 'machete',
			'title'       => __( 'Resource Directory', 'machete' ),
			'full_title'  => __( 'Resource Directory', 'machete' ),
			'description' => __( 'Resource Directory', 'machete' ),
			'has_config'  => false,
			'role'        => 'publish_posts', // targeting Author role.
		) );
	}
	/**
	 * This module doesn't have front-end functionality.
	 */
	public function frontend() {}
}
$machete->modules['clone'] = new MACHETE_DIRECTORY_MODULE();
