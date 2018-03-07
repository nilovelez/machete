<?php
/**
 * Machete Clone Module class

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete Clone Module class
 */
class MACHETE_CLONE_MODULE extends MACHETE_MODULE {
	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init( array(
			'slug'        => 'clone',
			'title'       => __( 'Post & Page Cloner', 'machete' ),
			'full_title'  => __( 'Post & Page Cloner', 'machete' ),
			'description' => __( 'Adds a "duplicate" link to post, page and most post types lists. Also adds "copy to new draft" function to the post editor.', 'machete' ),
			'has_config'  => false,
		) );
	}
	/**
	 * This module doesn't have front-end functionality.
	 */
	public function frontend() {}
}
$machete->modules['clone'] = new MACHETE_CLONE_MODULE();
