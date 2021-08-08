<?php
/**
 * Content of the "WordPress Optimization" page.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
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
	<h1><?php $this->icon(); ?> <?php esc_html_e( 'WooCommerce Utils', 'machete' ); ?></h1>

	<p class="tab-description"><?php esc_html_e( 'WooCommerce was designed to work for every possible use case, but that often leads to unexpected behavior. These simple fixes can improve the WooCommerce user experience by making it behave as clients expect.', 'machete' ); ?></p>

	<?php $machete->admin_tabs( 'machete-cleanup' ); ?>

	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php esc_html_e( 'This section stores all its settings in a single autoloaded configuration variable.', 'machete' ); ?></span></p>

	<div class="feature-section">
		<form id="machete-woo-options" action="" method="POST">

			<?php wp_nonce_field( 'machete_save_woo' ); ?>

			<input type="hidden" name="machete-woo-saved" value="true">

		<h3><?php esc_html_e( 'WooCommerce Utils', 'machete' ); ?></h3>

		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-woo-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_woo_checkall_fld" <?php checked( true, $this->all_woo_checked, true ); ?>></td>
				<th class="column-title manage-column column-primary"><?php esc_html_e( 'Option', 'machete' ); ?></th>
				<th><?php esc_html_e( 'Explanation', 'machete' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $this->woo_array as $machete_option_slug => $machete_option ) { ?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="optionEnabled[]" value="<?php echo esc_attr( $machete_option_slug ); ?>" id="<?php echo esc_attr( $machete_option_slug . '_fld' ); ?>" <?php checked( true, in_array( $machete_option_slug, $this->settings, true ), true ); ?>></th>
				<td class="column-title column-primary"><strong><?php echo esc_html( $machete_option['title'] ); ?></strong>
				<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'machete' ); ?></span></button>
				</td>
				<td data-colname="<?php echo esc_attr( _e( 'Explanation', 'machete' ) ); ?>"><?php echo wp_kses( $machete_option['description'], $machete_allowed_description_tags ); ?></td>
			</tr>

		<?php } ?>

		</tbody>
		</table>

		<?php submit_button(); ?>
		</form>

	</div>
</div>

<script>
(function($){
	$('#machete-woo-options .machete-woo-table :checkbox').change(function() {
		// this will contain a reference to the checkbox
		console.log(this.id);
		var checkBoxes = $("#machete-woo-options .machete-woo-table input[name=optionEnabled\\[\\]]");

		if (this.id == 'machete_woo_checkall_fld'){
			if (this.checked) {
				checkBoxes.prop("checked", true);
			} else {
				checkBoxes.prop("checked", false);
				// the checkbox is now no longer checked
			}
		}else{
			var checkBoxes_checked = $("#machete-woo-options .machete-woo-table input[name=optionEnabled\\[\\]]:checked");
			if(checkBoxes_checked.length == checkBoxes.length){
				$('#machete_woo_checkall_fld').prop("checked", true);
			}else{
				$('#machete_woo_checkall_fld').prop("checked", false);
			}
		}
	});
})(jQuery);
</script>
