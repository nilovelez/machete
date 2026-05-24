<?php
/**
 * Content of the "Machete PowerTools" page.

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$machete_allowed_description_tags = array(
	'br'   => array(),
	'span' => array(
		'style' => array(),
	),
);

?>
<div class="wrap machete-wrap machete-section-wrap">
	<div class="wp-header-end"></div><!-- admin notices go after .wp-header-end or .wrap>h2:first-child -->
	<h1><?php $this->icon(); ?> <?php esc_html_e( 'Machete PowerTools', 'machete' ); ?></h1>

	<p class="tab-description"><?php esc_html_e( 'PowerTools bundles advanced utilities and maintenance actions for WordPress developers and power users. As with every Machete tool, do not enable any option you do not understand.', 'machete' ); ?></p>
	<?php $machete->admin_tabs( 'machete-powertools' ); ?>
	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php esc_html_e( 'This section stores all its settings in a single autoloaded configuration variable.', 'machete' ); ?></span></p>


<form id="mache-powertools-actions" action="" method="POST">

	<?php wp_nonce_field( 'machete_powertools_action' ); ?>

	<table class="form-table">
	<tbody><tr>

	<th scope="row"><label for="tracking_id"><?php esc_html_e( 'Delete Expired Transients', 'machete' ); ?></label></th>
	<td><input type="submit" name="machete-powertools-action" value="purge_transients" class="button button-primary">
	<p class="description" id="tracking_id_description" style="display: none;"><?php esc_html_e( 'Format:', 'machete' ); ?></p></td>
	</tr>
	<tr>
	<th scope="row"><label for="purge_post_revisions"><?php esc_html_e( 'Delete Post Revisions', 'machete' ); ?></label></th>
	<td><input type="submit" name="machete-powertools-action" value="purge_post_revisions" class="button button-primary"></td>
	</tr>
	<tr>
	<th scope="row"><label for="tracking_id"><?php esc_html_e( 'Delete Permalink Cache', 'machete' ); ?></label></th>
	<td><input type="submit" name="machete-powertools-action" value="flush_rewrites" class="button button-primary">
	<p class="description" id="tracking_id_description" style="display: none;"><?php esc_html_e( 'Format:', 'machete' ); ?></p></td>
	</tr>

	<?php if ( function_exists( 'opcache_reset' ) ) { ?>
	<tr>
	<th scope="row"><label for="tracking_id"><?php esc_html_e( 'Delete Opcache contents', 'machete' ); ?></label></th>
	<td><input type="submit" name="machete-powertools-action" value="flush_opcache" class="button button-primary">
	<p class="description" id="tracking_id_description" style="display: none;"><?php esc_html_e( 'Format:', 'machete' ); ?></p></td>
	</tr>
	<?php } ?>

	<tr>
	<th scope="row"><label for="tracking_id"><?php esc_html_e( 'Delete WordPress object cache contents', 'machete' ); ?></label></th>
	<td><input type="submit" name="machete-powertools-action" value="flush_wpcache" class="button button-primary">
	<p class="description" id="tracking_id_description" style="display: none;"><?php esc_html_e( 'Format:', 'machete' ); ?></p></td>
	</tr>

	</tbody></table>
</form>



<form id="machete-powertools-options" action="" method="POST">

	<?php wp_nonce_field( 'machete_save_powertools' ); ?>

	<input type="hidden" name="machete-powertools-saved" value="true">
	<h3><?php esc_html_e( 'Machete Toolbox', 'machete' ); ?></h3>


	<table class="wp-list-table widefat fixed striped posts machete-options-table machete-powertools-table">
	<thead>
		<tr>
			<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_powertools_checkall_fld" <?php checked( true, $this->all_powertools_checked, true ); ?>></td>
			<th class="column-title manage-column column-primary"><?php esc_html_e( 'Remove', 'machete' ); ?></th>
			<th><?php esc_html_e( 'Explanation', 'machete' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ( $this->powertools_array as $machete_option_slug => $machete_option ) { ?>
		<tr>
			<th scope="row" class="check-column"><input type="checkbox" name="optionEnabled[]" value="<?php echo esc_attr( $machete_option_slug ); ?>" id="<?php echo esc_attr( $machete_option_slug . '_fld' ); ?>" <?php checked( true, in_array( $machete_option_slug, $this->settings, true ), true ); ?>></th>
			<td class="column-title column-primary"><strong><?php echo esc_html( $machete_option['title'] ); ?></strong>
			<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'machete' ); ?></span></button>
			</td>
			<td data-colname="<?php esc_html_e( 'Explanation', 'machete' ); ?>"><?php echo wp_kses( $machete_option['description'], $machete_allowed_description_tags ); ?></td>
		</tr>

	<?php } ?>

	</tbody>
	</table>
	<?php submit_button(); ?>
</form>

</div>


<script>


( function( $ ) {
	$('#machete-powertools-options .machete-powertools-table :checkbox').change(function() {
		var checkBoxes = $("#machete-powertools-options .machete-powertools-table input[name=optionEnabled\\[\\]]");

		if ( this.id === 'machete_powertools_checkall_fld' ) {
			checkBoxes.prop( 'checked', this.checked );
		} else {
			var checkBoxes_checked = $("#machete-powertools-options .machete-powertools-table input[name=optionEnabled\\[\\]]:checked");
			$('#machete_powertools_checkall_fld').prop( 'checked', checkBoxes_checked.length === checkBoxes.length );
		}
	});
})( jQuery );

</script>
