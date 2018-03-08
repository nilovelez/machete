<?php
/**
 * Content of the "Import/Export" page.

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
} ?>

<div class="wrap machete-wrap machete-section-wrap">
	<h1><?php esc_html_e( 'Import/Export Options', 'machete' ); ?></h1>

	<p class="tab-description"><?php esc_html_e( 'You can use this section to backup and restore your Machete configuration. You can also take a backup from one site and restore to another. Be careful, your current configuration will be overwritten when you import a backup file', 'machete' ); ?></p>
	<?php $machete->admin_tabs( 'machete-importexport' ); ?>

<form id="mache-importexport-options" action="" method="POST">

	<?php wp_nonce_field( 'machete_importexport_export' ); ?>
	<input type="hidden" name="machete-importexport-export" value="true">

	<h3><?php esc_html_e( 'Export Machete Options', 'machete' ); ?> </h3>

		<p><?php esc_html_e( 'Select the modules from which you want to export the settings and click "download config backup" to get a .json file with the backup.', 'machete' ); ?></p>

		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-optimize-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_cleanup_checkall_fld" <?php if ($this->all_exportable_modules_checked) echo 'checked' ?>></td>
				<th class="column-title manage-column column-primary"><?php esc_html_e( 'Modules to export', 'machete' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $this->exportable_modules as $machete_module => $args ) { ?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="moduleChecked[]" value="<?php echo esc_attr( $machete_module ); ?>" id="<?php echo esc_attr( $machete_module . '_fld' ); ?>" <?php if ($args['checked']) echo 'checked' ?>></th>
				<td class="column-title column-primary"><strong><?php echo esc_html( $args['full_title'] ); ?></strong>
				<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'machete' ); ?></span></button>
				</td>
			</tr>

		<?php } ?>

		</tbody>
		</table>

		<?php submit_button( __( 'Download config backup', 'machete' ) ); ?>
</form>

<form id="mache-importexport-restore" action="" method="POST" enctype="multipart/form-data">

	<?php wp_nonce_field( 'machete_importexport_import' ); ?>
	<input type="hidden" name="machete-importexport-import" value="true">
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />

	<h3><?php esc_html_e( 'Import Machete Options', 'machete' ); ?> </h3>

	<p><?php esc_html_e( 'Select a Machete backup file and click the "upload config backup" to import it. Please keep in mind that the module settings included in the backup file will overwrite your current setting for those modules.', 'machete' ); ?></p>

	<label class="screen-reader-text" for="machete-backup-file"><?php esc_html_e( 'Machete backup file to restore', 'machete' ); ?></label>
	<input type="file" id="machete-backup-file" name="machete-backup-file">

	<?php submit_button( __( 'Upload config backup', 'machete' ) ); ?>

</form>	

<?php
if ( ! empty( $this->import_log ) ) {
	echo '<div style="background-color: #ffe; border: 1px solid #ddd; padding: 15px; overflow-x: auto;"><pre>';
	echo esc_html( $this->import_log );
	echo '</pre></div>';
}
?>
</div>
