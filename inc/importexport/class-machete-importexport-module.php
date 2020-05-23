<?php
/**
 * Machete import/export Module class
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Machete import/export Module class
 */
class MACHETE_IMPORTEXPORT_MODULE extends MACHETE_MODULE {
	/**
	 * Module constructor, init method overrides parent module default params
	 */
	public function __construct() {
		$this->init(
			array(
				'slug'            => 'importexport',
				'title'           => __( 'Import/Export', 'machete' ),
				'full_title'      => __( 'Import/Export Options', 'machete' ),
				'description'     => __( 'You can use this section to backup and restore your Machete configuration. You can also take a backup from one site and restore it to another.', 'machete' ),
				'can_be_disabled' => false,
			)
		);
	}

	/**
	 * This modules has no front-end functions
	 */
	public function frontend() {}

	/**
	 * Executes code related to the WordPress admin.
	 */
	public function admin() {
		global $machete;

		foreach ( $machete->modules as $module ) {

			$params = $module->params;
			if ( 'importexport' === $params['slug'] ) {
				continue;
			}
			if ( ! $params['has_config'] && ! $params['can_be_disabled'] ) {
				continue;
			}
			$this->exportable_modules[ $params['slug'] ] = array(
				'title'      => $params['title'],
				'full_title' => $params['full_title'],
				'checked'    => true,
			);

		}
		add_action(
			'admin_init',
			function() {
				if ( filter_input( INPUT_POST, 'machete-importexport-export' ) !== null ) {

					check_admin_referer( 'machete_importexport_export' );

					$this->checked_modules = filter_input( INPUT_POST, 'moduleChecked', FILTER_DEFAULT, FILTER_FORCE_ARRAY );

					if ( count( $this->checked_modules ) > 0 ) {
						$export_file     = $this->export();
						$export_filename = 'machete_backup_' . strtolower( gmdate( 'jMY' ) ) . '.txt';

						header( 'Content-disposition: attachment; filename=' . $export_filename );
						header( 'Content-Type: application/json' );
						header( 'Pragma: no-cache' );
						echo $export_file; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						exit();
					}
				}
				if ( filter_input( INPUT_POST, 'machete-importexport-import' ) !== null ) {
					check_admin_referer( 'machete_importexport_import' );
					$this->import();
				}
			}
		);

		// ToDo: check $this->checked_modules for actual status.
		$this->all_exportable_modules_checked = true;
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}

	/**
	 * Returns a module settings array to use for backups.
	 *
	 * @return array modules settings array.
	 */
	protected function export() {
		global $machete;

		$export = array();

		foreach ( $this->checked_modules as $slug ) {
			if ( ! in_array( $slug, array_keys( $this->exportable_modules ), true ) ) {
				continue;
			}
			$params = $machete->modules[ $slug ]->params;

			$export[ $params['slug'] ] = array();
			if ( $params['can_be_disabled'] ) {
				$export[ $params['slug'] ]['is_active'] = $params['is_active'];
			}
			if ( $params['has_config'] ) {
				$export[ $params['slug'] ]['settings'] = $machete->modules[ $slug ]->export();
			}
		}
		return wp_json_encode( $export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

	}

	/**
	 * Restores module settings from a backup
	 *
	 * @param array $settings modules settings array.
	 * @return string success/error message.
	 */
	protected function import( $settings = array() ) {
		global $machete;

		// Verify the current user can upload files.
		if ( ! current_user_can( 'upload_files' ) ) {
			$this->notice( __( 'You do not have permission to upload files.', 'machete' ), 'warning' );
			return false;
		}

		if ( isset( $_FILES['machete-backup-file']['name'] ) ) {

			$allowed_mimes = array(
				'txt' => 'text/plain',
			);
			$upload_info   = wp_handle_upload(
				$_FILES['machete-backup-file'], //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				array(
					'test_form' => false,
					'mimes'     => $allowed_mimes,
				)
			);
		} else {
			$this->notice( __( 'You haven\'t uploaded a backup file', 'machete' ), 'warning' );
			return false;
		}

		if ( ! $upload_info ) {
			$this->notice( __( 'An error happened while trying to upload your file', 'machete' ), 'warning' );
			return false;
		}

		if ( isset( $upload_info['error'] ) ) {
			$this->notice( __( 'An error happened: ', 'machete' ) . $upload_info['error'], 'warning' );
			return false;
		}

		$backup_data = file_get_contents( $upload_info['file'] );
		if ( $backup_data ) {
			wp_delete_file( $upload_info['file'] );
		} else {
			$this->notice( __( 'You haven\'t uploaded a backup file', 'machete' ), 'warning' );
			return false;
		}

		$this->uploaded_backup_data = json_decode( $backup_data, true );
		if (
			( null === $this->uploaded_backup_data ) ||
			( ! is_array( $this->uploaded_backup_data ) )
		) {
			$this->notice( __( 'You haven\'t uploaded a valid Machete backup file', 'machete' ), 'warning' );
			return false;
		}

		$this->import_log = '';
		foreach ( $this->uploaded_backup_data as $module => $module_data ) {

			if ( ! array_key_exists( $module, $machete->modules ) ) {
				$this->import_log .= __( 'Ignored unknown module:', 'machete' ) . ' ' . $module . "\n\n";
				continue;
			}
			$this->import_log .= __( 'Importing module: ', 'machete' ) . ' ' . $module . "\n";
			$this->import_log .= "===============================\n";

			// manage module activation/deactivation.
			if ( array_key_exists( 'is_active', $module_data ) && is_bool( $module_data['is_active'] ) && ( 'powertools' !== $module ) ) {
				if ( $module_data['is_active'] ) {
					if ( $machete->manage_modules( $module, 'activate', true ) ) {
						$this->import_log .= __( 'Module activated successfully', 'machete' ) . "\n";
					}
				} else {
					if ( $machete->manage_modules( $module, 'deactivate', true ) ) {
						$this->import_log .= __( 'Module deactivated successfully', 'machete' ) . "\n";
					}
				}
			}

			if ( array_key_exists( 'settings', $module_data ) &&
				is_array( $module_data['settings'] ) &&
				$machete->modules[ $module ]->params['has_config'] &&
				( count( $module_data['settings'] ) > 0 )
				) {

				$this->import_log .= wp_json_encode(
					$module_data['settings'],
					JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
				);
				$this->import_log .= "\n";

				$this->import_log .= $machete->modules[ $module ]->import( $module_data['settings'] );
			}
			$this->import_log .= "\n";

		}
	}
}
$machete->modules['importexport'] = new MACHETE_IMPORTEXPORT_MODULE();
