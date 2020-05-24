<?php
/**
 * Machete main class, used as controller of all the modules

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete main class
 */
class MACHETE {
	/**
	 * Container for module instances
	 *
	 * @var Array $modules
	 **/
	public $modules = array();

	/**
	 * Placeholder for the old tabs navigation.
	 *
	 * @param string $current Current (active) tab slug.
	 */
	public function admin_tabs( $current = '' ) {
		echo '<div class="machete-wrap-divider" id="' . esc_attr( $current . '-divider' ) . '"></div>';
	}
	/**
	 * Displays navigation tabs on Machete tabs (deactivated)
	 *
	 * @param string $current Current (active) tab slug.
	 */
	public function admin_tabs_old( $current = '' ) {

		$is_admin = current_user_can( 'manage_options' ) ? true : false;

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->modules as $module ) {

			$params = $module->params;

			if ( ! $is_admin && ( 'manage_options' === $params['role'] ) ) {
				continue;
			}
			if ( ! $params['is_active'] ) {
				continue;
			}
			if ( ! $params['has_config'] ) {
				continue;
			}
			$slug = 'machete-' . $params['slug'];

			$allowed_title_tags = array(
				'span' => array(
					'style' => array(),
				),
			);

			if ( $slug === $current ) {
				echo '<a href="#" class="nav-tab-active nav-tab ' . esc_attr( $slug ) . '-tab">' . wp_kses( $params['title'], $allowed_title_tags ) . '</a>';
			} else {
				$tab_link = add_query_arg( array( 'page' => $slug ), admin_url( 'admin.php' ) );
				echo '<a href="' . esc_url( $tab_link ) . '" class="nav-tab ' . esc_attr( $slug ) . '-tab">' . wp_kses( $params['title'], $allowed_title_tags ) . '</a>';
			}
		}
		echo '</h2>';
	}

	/* Dashboard notices */

	/**
	 * Displays standar WordPress dashboard notice.
	 *
	 * @param string $message     Message to display.
	 * @param string $level       Can be error, warning, info or success.
	 * @param bool   $dismissible determines if the notice can be dismissed via javascript.
	 */
	public function notice( $message, $level = 'info', $dismissible = true ) {
		$this->notice_message = $message;

		if ( ! in_array( $level, array( 'error', 'warning', 'info', 'success' ), true ) ) {
			$level = 'info';
		}
		$this->notice_class = 'notice notice-' . $level;
		if ( $dismissible ) {
			$this->notice_class .= ' is-dismissible';
		}
		add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}
	/**
	 * Callback function for the admin_notices action in the notice() function.
	 */
	public function display_notice() {
		if ( ! empty( $this->notice_message ) ) {
			?>
		<div class="<?php echo esc_attr( $this->notice_class ); ?>">
			<p><?php echo esc_html( $this->notice_message ); ?></p>
		</div>
			<?php
		}
	}

	/**
	 * Module activation and deactivation
	 *
	 * @param string $module module to work on.
	 * @param string $action action to execute (activate|deactivate).
	 * @param bool   $silent Supress admin notices.
	 */
	public function manage_modules( $module, $action, $silent = false ) {

		if ( empty( $module ) || empty( $action ) || in_array( $action, array( 'enable', 'disable' ), true ) ) {
			if ( ! $silent ) {
				$this->notice( __( 'Bad request', 'machete' ), 'error' );
			}
			return false;
		}

		if ( ! array_key_exists( $module, $this->modules ) ) {
			if ( ! $silent ) {
				$this->notice( __( 'Unknown module:', 'machete' ) . ' ' . $module, 'error' );
			}
			return false;
		}

		$disabled_modules = get_option( 'machete_disabled_modules', array() );

		if ( 'deactivate' === $action ) {
			if ( in_array( $module, $disabled_modules, true ) ) {
				if ( ! $silent ) {
					$this->notice( __( 'Nothing to do. The module was already disabled.', 'machete' ), 'notice' );
				}
				return false;
			}
			if ( ! $this->modules[ $module ]->params['can_be_disabled'] ) {
				if ( ! $silent ) {
					$this->notice( __( 'Sorry, you can\'t disable that module', 'machete' ), 'warning' );
				}
				return false;
			}

			$disabled_modules[] = $module;

			if ( update_option( 'machete_disabled_modules', $disabled_modules ) ) {
				$this->modules[ $module ]->params['is_active'] = false;
				if ( ! $silent ) {
					$this->notice(
						sprintf(
							/* Translators: module title */
							__( 'Module %s disabled successfully', 'machete' ),
							$this->modules[ $module ]->params['title']
						),
						'success'
					);
				}
				return true;
			} else {
				if ( ! $silent ) {
					$this->notice( __( 'Error saving configuration to database.', 'machete' ), 'error' );
				}
				return false;
			}
		}

		if ( 'activate' === $action ) {
			if ( $this->modules[ $module ]->params['is_active'] ) {
				if ( ! $silent ) {
					$this->notice( __( 'Nothing to do. The module was already active.', 'machete' ), 'notice' );
				}
				return false;
			}
			if ( 'powertools' === $module ) {
				if ( ! $silent ) {
					$this->notice( __( 'Sorry, you can\'t enable that module', 'machete' ), 'warning' );
				}
				return false;
			}

			$disabled_modules = array_diff( $disabled_modules, array( $module ) );

			if ( update_option( 'machete_disabled_modules', $disabled_modules ) ) {
				$this->modules[ $module ]->params['is_active'] = true;
				if ( ! $silent ) {
					$this->notice(
						sprintf(
							/* Translators: module title */
							__( 'Module %s enabled successfully', 'machete' ),
							$this->modules[ $module ]->params['title']
						),
						'success'
					);
				}
				return true;

			} else {
				if ( ! $silent ) {
					$this->notice( __( 'Error saving configuration to database.', 'machete' ), 'error' );
				}
				return false;
			}
		}
	}
}
