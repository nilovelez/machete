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

$allowed_description_tags = array(
	'br'   => array(),
	'span' => array(
		'style' => array(),
	),
);

?>

<style>
	.machete_security_badge {
		padding: 2px 5px;
		display: inline-block;
		font-weight: normal;
		font-size: 14px;
		border-radius: 4px;
		color: #fff;
		background-color: #999;
		margin-left: 10px;
	}
	.machete_safe_badge {background-color: #4ab866;}
	.machete_warning_badge {background-color: #f0b849;}
	.machete_danger_badge {background-color: #d94f4f;}
</style>

<div class="wrap machete-wrap machete-section-wrap">

	<h1><?php esc_html_e( 'WordPress Optimization', 'machete' ); ?></h1>

	<p class="tab-description"><?php esc_html_e( 'WordPress has a los of code just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer', 'machete' ); ?></p>

	<?php $machete->admin_tabs( 'machete-cleanup' ); ?>

	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php esc_html_e( 'This section stores all its settings in a single autoloaded configuration variable.', 'machete' ); ?></span></p>		

	<div class="feature-section">
		<form id="machete-cleanup-options" action="" method="POST">

			<?php wp_nonce_field( 'machete_save_cleanup' ); ?>

			<input type="hidden" name="machete-cleanup-saved" value="true">

		<h3><?php esc_html_e( 'Header Cleanup', 'machete' ); ?>  <span class="machete_security_badge machete_safe_badge"><?php esc_html_e( 'Completely safe', 'machete' ); ?></span></h3>

		<p><?php esc_html_e( 'This section removes code from the &lt;head&gt; tag. This makes your site faster and reduces the amount of information revealed to a potential attacker.', 'machete' ); ?></p>

		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-cleanup-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_cleanup_checkall_fld" <?php checked( true, $this->all_cleanup_checked, true ); ?>></td>
				<th class="column-title manage-column column-primary"><?php esc_html_e( 'Remove', 'machete' ); ?></th>
				<th><?php esc_html_e( 'Explanation', 'machete' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $this->cleanup_array as $option_slug => $option ) { ?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="optionEnabled[]" value="<?php echo esc_attr( $option_slug ); ?>" id="<?php echo esc_attr( $option_slug . '_fld' ); ?>" <?php checked( true, in_array( $option_slug, $this->settings, true ), true ); ?>></th>
				<td class="column-title column-primary"><strong><?php echo esc_html( $option['title'] ); ?></strong>
				<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'machete' ); ?></span></button>
				</td>
				<td data-colname="<?php echo esc_attr( _e( 'Explanation', 'machete' ) ); ?>"><?php echo wp_kses( $option['description'], $allowed_description_tags ); ?></td>
			</tr>

		<?php } ?>

		</tbody>
		</table>


		<h3><?php esc_html_e( 'Feature Cleanup', 'machete' ); ?> <span class="machete_security_badge machete_warning_badge"><?php esc_html_e( 'Mostly safe', 'machete' ); ?></span></h3>

		<p><?php esc_html_e( 'This section goes futher disabling optional features. All options can be safely activated, but keep an eye on potiential plugin compatibility issues.', 'machete' ); ?></p>

		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-optimize-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_cleanup_checkall_fld" <?php checked( true, $this->all_optimize_checked, true ); ?>></td>
				<th class="column-title manage-column column-primary"><?php esc_html_e( 'Remove', 'machete' ); ?></th>
				<th><?php esc_html_e( 'Explanation', 'machete' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $this->optimize_array as $option_slug => $option ) { ?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="optionEnabled[]" value="<?php echo esc_attr( $option_slug ); ?>" id="<?php echo esc_attr( $option_slug . '_fld' ); ?>" <?php checked( true, in_array( $option_slug, $this->settings, true ), true ); ?>></th>
				<td class="column-title column-primary"><strong><?php echo esc_html( $option['title'] ); ?></strong>
				<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'machete' ); ?></span></button>
				</td>
				<td data-colname="<?php echo esc_attr( _e( 'Explanation', 'machete' ) ); ?>"><?php echo wp_kses( $option['description'], $allowed_description_tags ); ?></td>
			</tr>

		<?php } ?>

		</tbody>
		</table>



		<h3><?php esc_html_e( 'Optimization Tweaks', 'machete' ); ?>  <span class="machete_security_badge machete_danger_badge"><?php esc_html_e( 'Handle with care', 'machete' ); ?></span></h3>

		<p><?php esc_html_e( 'Options in this section have the most impact on WordPress performance, but also the most potential of screwing things up.', 'machete' ); ?></p>

		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-tweaks-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_cleanup_checkall_fld" <?php checked( true, $this->all_tweaks_checked, true ); ?>></td>
				<th class="column-title manage-column column-primary"><?php echo esc_html_e( 'Remove', 'machete' ); ?></th>
				<th><?php esc_html_e( 'Explanation', 'machete' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $this->tweaks_array as $option_slug => $option ) { ?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="optionEnabled[]" value="<?php echo esc_attr( $option_slug ); ?>" id="<?php echo esc_attr( $option_slug . '_fld' ); ?>" <?php checked( true, in_array( $option_slug, $this->settings, true ), true ); ?>></th>
				<td class="column-title column-primary"><strong><?php echo esc_html( $option['title'] ); ?></strong>
				<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'machete' ); ?></span></button>
				</td>
				<td data-colname="<?php echo esc_attr( _e( 'Explanation', 'machete' ) ); ?>"><?php echo wp_kses( $option['description'], $allowed_description_tags ); ?></td>
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
	$('#machete-cleanup-options .machete-cleanup-table :checkbox').change(function() {
		// this will contain a reference to the checkbox
		console.log(this.id); 
		var checkBoxes = $("#machete-cleanup-options .machete-cleanup-table input[name=optionEnabled\\[\\]]");

		if (this.id == 'machete_cleanup_checkall_fld'){
			if (this.checked) {
				checkBoxes.prop("checked", true);
			} else {
				checkBoxes.prop("checked", false);
				// the checkbox is now no longer checked
			}
		}else{
			var checkBoxes_checked = $("#machete-cleanup-options .machete-cleanup-table input[name=optionEnabled\\[\\]]:checked");
			if(checkBoxes_checked.length == checkBoxes.length){
				$('#machete_cleanup_checkall_fld').prop("checked", true);
			}else{
				$('#machete_cleanup_checkall_fld').prop("checked", false);
			}
		}
	});


	$('#machete-cleanup-options .machete-optimize-table :checkbox').change(function() {
		// this will contain a reference to the checkbox
		console.log(this.id); 
		var checkBoxes = $("#machete-cleanup-options .machete-optimize-table input[name=optionEnabled\\[\\]]");

		if (this.id == 'machete_optimize_checkall_fld'){
			if (this.checked) {
				checkBoxes.prop("checked", true);
			} else {
				checkBoxes.prop("checked", false);
				// the checkbox is now no longer checked
			}
		}else{
			var checkBoxes_checked = $("#machete-cleanup-options .machete-optimize-table input[name=optionEnabled\\[\\]]:checked");
			if(checkBoxes_checked.length == checkBoxes.length){
				$('#machete_optimize_checkall_fld').prop("checked", true);
			}else{
				$('#machete_optimize_checkall_fld').prop("checked", false);
			}
		}
	});
})(jQuery);
</script>
