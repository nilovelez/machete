<?php
/**
 * Machete module abstract class, used as parent class for all Machete modules

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Machete module abstract class
 */
abstract class MACHETE_MODULE {
	/**
	 * Default module properties, can be overridden by child modules.
	 *
	 * @var array
	 */
	public $params = array(
		'slug'            => '',
		'title'           => '',
		'full_title'      => '',
		'description'     => '',
		'is_external'     => false,
		'is_active'       => true,
		'has_config'      => true,
		'can_be_disabled' => true,
		'can_be_enabled'  => true,
		'role'            => 'manage_options',
	);
	/**
	 * Temporal container for the module's database-stored settings
	 *
	 * @var array
	 */
	protected $settings = array();
	/**
	 * Default module settings, default settings for unconfigured modules.
	 * Can be overriden by child modules.
	 *
	 * @var array
	 */
	protected $default_settings = array();
	/**
	 * Initialises the module.
	 *
	 * @param array $params params array with the immutable module properties.
	 */
	protected function init( $params = array() ) {
		$this->params = array_merge( $this->params, $params );
		if ( array_key_exists( 'path', $this->params ) ) {
			$this->path = $this->params['path'];
		} else {
			$this->path = MACHETE_BASE_PATH . 'inc/' . $this->params['slug'] . '/';
		}
	}
	/**
	 * Reads the modules settings to the settings proerty,
	 * also returns them in an array.
	 *
	 * @return array module settings array
	 */
	protected function read_settings() {
		$this->settings = get_option(
			'machete_' . $this->params['slug'] . '_settings',
			$this->default_settings
		);
		$this->settings = array_merge( $this->default_settings, $this->settings );
		return $this->settings;
	}
	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {
		if ( $this->params['has_config'] ) {
			$this->read_settings();
		}
		require $this->path . 'admin-functions.php';
		if ( $this->params['has_config'] ) {
			add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
		}
	}
	/**
	 * Returns the absolute URL to the module's icon.
	 */
	public function icon() {
		$icon_url = MACHETE_BASE_URL . 'inc/' . $this->params['slug'] . '/icon.svg';
		echo '<img src="' . esc_attr( $icon_url ) . '" style="width: 96px; height: 96px;">';
	}
	/**
	 * Adds the modules configuration link to the dashboard menu.
	 */
	public function register_sub_menu() {
		add_submenu_page(
			'machete',
			$this->params['full_title'],
			$this->params['title'],
			$this->params['role'],
			'machete-' . $this->params['slug'],
			array( $this, 'submenu_page_callback' )
		);
	}
	/**
	 * Callback for the module's configuration page content.
	 */
	public function submenu_page_callback() {
		global $machete;
		require $this->path . 'admin-content.php';
	}
	/**
	 * Executes code related to the front-end.
	 */
	public function frontend() {
		if ( $this->params['has_config'] ) {
			$this->read_settings();
		}
		require $this->path . 'frontend-functions.php';
	}
	/**
	 * Function model to serve options to database
	 * real function must be defined in each module
	 *
	 * @param array $settings options array, normally $_POST.
	 * @param bool  $silent   prevent the function from generating admin notices.
	 */
	protected function save_settings( $settings = array(), $silent = false ) {
		return true;
	}

	/**
	 * Returns a module settings array to use for backups.
	 *
	 * @return array modules settings array.
	 */
	protected function export() {
		return $this->read_settings();
	}

	/**
	 * Restores module settings from a backup
	 *
	 * @param array $settings modules settings array.
	 * @return string success/error message.
	 */
	protected function import( $settings = array() ) {
		if ( $this->save_settings( $settings, true ) ) {
			return __( 'Settings successfully restored from backup', 'machete' ) . "\n";
		} else {
			return __( 'Error restoring settings backup', 'machete' ) . "\n";
		}
	}
	/* Dashboard notices */

	/**
	 * Displays standard WordPress dashboard notice.
	 *
	 * @param string $message     Message to display.
	 * @param string $level       Can be error, warning, info or success.
	 * @param bool   $dismissible determines if the notice can be dismissed via javascript.
	 */
	public function notice( $message, $level = 'info', $dismissible = true ) {
		global $machete;
		$machete->notice( $message, $level, $dismissible );
	}

	/**
	 * Displays a generic 'Options saved!' success notice
	 */
	protected function save_success_notice() {
		$this->notice( __( 'Options saved!', 'machete' ), 'success' );
	}
	/**
	 * Displays a generic save error notice
	 */
	protected function save_error_notice() {
		$this->notice( __( 'Error saving configuration to database.', 'machete' ), 'error' );
	}
	/**
	 * Displays a generic 'No changes were needed.' info notice
	 */
	protected function save_no_changes_notice() {
		$this->notice( __( 'No changes were needed.', 'machete' ), 'info' );
	}

	/* utils */
	/**
	 * Checks if two arrays are exactly equal
	 *
	 * @param array $a first array to compare.
	 * @param array $b second array to compare.
	 */
	public function is_equal_array( $a, $b ) {
		return (
			is_array( $a ) && is_array( $b ) &&
			count( $a ) === count( $b ) &&
			$this->array_recursive_diff( $a, $b ) === $this->array_recursive_diff( $b, $a )
		);
	}
	/**
	 * Custom version of array_diff that works with multidimensional arrays
	 *
	 * @param array $a array to substract from.
	 * @param array $b array to substract from the first array.
	 */
	public function array_recursive_diff( $a, $b ) {
		$return = array();

		foreach ( $a as $key => $value ) {
			if ( array_key_exists( $key, $b ) ) {
				if ( is_array( $value ) ) {
					$recursive_diff = $this->array_recursive_diff( $value, $b[ $key ] );
					if ( count( $recursive_diff ) ) {
						$return[ $key ] = $recursive_diff;
					}
				} else {
					if ( $value !== $b[ $key ] ) {
						$return[ $key ] = $value;
					}
				}
			} else {
				$return[ $key ] = $value;
			}
		}
		return $return;
	}

	/* filesystem */

	/**
	 * Initialises wp_filesystem and gets access credentials.
	 *
	 * @return bool false if not correctly initialised
	 */
	protected function init_filesystem() {
		global $machete;
		$access_type = get_filesystem_method();
		if ( 'direct' !== $access_type ) {
			$machete->notice( __( 'This function needs direct access to the filesystem.', 'machete' ), 'error' );
			return false;
		} else {
			$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );
			if ( ! WP_Filesystem( $creds ) ) {
				/* any problems and we exit */
				$machete->notice( __( 'There was a problem accessing the filesystem. Check your permissions.', 'machete' ), 'error' );
				return false;
			}
			return true;
		}
	}
	/**
	 * Abstraction layer over $wp_filesystem->put-contents() function.
	 *
	 * @param string $file     Remote path to the file where to write the data.
	 * @param string $contents The data to write.
	 * @return bool False on failure.
	 */
	protected function put_contents( $file, $contents ) {
		if ( ! $this->init_filesystem() ) {
			return false;
		}
		global $wp_filesystem;
		return $wp_filesystem->put_contents( $file, $contents );
	}
	/**
	 * Read entire file into a string.
	 *
	 * @since 2.5.0
	 * @abstract
	 *
	 * @param string $file Name of the file to read.
	 * @return mixed|bool Returns the read data or false on failure.
	 */
	protected function get_contents( $file ) {
		if ( is_admin() ) {
			// WP_filesystem is only available at the back-end.
			if ( ! $this->init_filesystem() ) {
				return false;
			}
			global $wp_filesystem;
			return $wp_filesystem->get_contents( $file );
		} else {
			// fallback method for use at the front-end.
			if ( ! file_exists( $file ) ) {
				return false;
			}
			ob_start();
			require $file;
			return ob_get_clean();
		}
	}
	/**
	 * Delete a file.
	 *
	 * @param string $file Path to the file.
	 * @return bool True if the file was deleted, false on failure.
	 */
	public function delete( $file ) {
		if ( ! $this->init_filesystem() ) {
			return false;
		}
		global $wp_filesystem;
		return $wp_filesystem->delete( $file );
	}
	/**
	 * File pass-through
	 *
	 * @param string $file Path to the file.
	 * @return bool false on failure.
	 */
	protected function readfile( $file ) {
		if ( ! file_exists( $file ) ) {
			return false;
		}
		return readfile( $file ); // phpcs:ignore
	}
}
