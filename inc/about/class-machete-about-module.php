<?php
/**
 * Machete About Module class
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete About Module class
 */
class MACHETE_ABOUT_MODULE extends MACHETE_MODULE {
	/**
	 * Module constructor, inite method overrides parent module default params
	 */
	public function __construct() {
		$this->init( array(
			'slug'            => 'about',
			'title'           => __( 'About Machete', 'machete' ),
			'full_title'      => __( 'About Machete', 'machete' ),
			'is_active'       => true,
			'has_config'      => false,
			'can_be_disabled' => false,
			'role'            => 'publish_posts', // targeting Author role.
		));
	}
	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {
		global $machete;
		// if this is called after the admin_menu hook, the modules you disable
		// are still shown in the side menu until you reload.
		$machete_action = filter_input( INPUT_GET, 'machete-action' );
		$module         = filter_input( INPUT_GET, 'module' );

		if ( ( ! is_null( $machete_action ) ) && ( ! is_null( $module ) ) ) {
			check_admin_referer( 'machete_action_' . $module );
			$machete->manage_modules( $module, $machete_action );
		}

		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}
	/**
	 * This modules has no front-end.
	 */
	public function frontend() {}

	/**
	 * Adds the module's configuration link to the dashboard menu.
	 * This can't use the default register_sub_menu,
	 * it has to replace the parent Machete menu link.
	 */
	public function register_sub_menu() {
		add_submenu_page(
			'machete',
			$this->params['full_title'],
			$this->params['title'],
			$this->params['role'],
			'machete'
		);
	}
}
$machete->modules['about'] = new MACHETE_ABOUT_MODULE();
