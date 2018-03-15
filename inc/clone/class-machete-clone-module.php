<?php
/**
 * Machete Clone Module class
 *
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
	 * Executes code related to the front-end.
	 * Adds a maintenance status button to the admin bar
	 */
	public function frontend() {
		if ( is_admin_bar_showing() ) {
			require_once $this->path . 'admin-bar.php';
		}
	}
	/**
	 * Executes code related to the WordPress admin.
	 * Adds a maintenance status button to the admin bar
	 */
	public function admin() {

		$this->read_settings();
		require $this->path . 'admin-functions.php';

		if ( is_admin_bar_showing() ) {
			require_once $this->path . 'admin-bar.php';
		}
	}
}
$machete->modules['clone'] = new MACHETE_CLONE_MODULE();
