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
	'a'      => array(
		'href'   => array(),
		'target' => array(),
		'rel'    => array(),
	),
	'br'     => array(),
	'strong' => array(),
	'span'   => array(
		'style' => array(),
	),
);

$machete_expired_transients_count = $this->count_expired_transients();
$machete_post_revisions_count     = $this->count_post_revisions();
$machete_orphaned_postmeta_count  = $this->count_orphaned_postmeta();
$machete_expired_cron_count       = $this->count_expired_cron_events();

?>
<div class="wrap machete-wrap machete-section-wrap">
	<div class="wp-header-end"></div><!-- admin notices go after .wp-header-end or .wrap>h2:first-child -->
	<h1><?php $this->icon(); ?> <?php esc_html_e( 'Machete PowerTools', 'machete' ); ?></h1>

	<p class="tab-description"><?php esc_html_e( 'PowerTools bundles advanced utilities and maintenance actions for WordPress developers and power users. As with every Machete tool, do not enable any option you do not understand.', 'machete' ); ?></p>
	<?php $machete->admin_tabs( 'machete-powertools' ); ?>
	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php esc_html_e( 'This section stores all its settings in a single autoloaded configuration variable.', 'machete' ); ?></span></p>


<form id="machete-powertools-actions" action="" method="POST">

	<?php wp_nonce_field( 'machete_powertools_action' ); ?>

	<table class="form-table machete-powertools-actions-table">
	<tbody><tr>

	<th scope="row"><button type="submit" name="machete-powertools-action" id="purge_transients" value="purge_transients" class="button button-primary"><?php esc_html_e( 'Purge transients', 'machete' ); ?></button></th>
	<td><label for="purge_transients"><?php
		echo wp_kses(
			'<strong>' . sprintf(
				/* translators: %1$s is the number of expired transients */
				_n(
					'Remove %1$s expired transient from wp_options.',
					'Remove %1$s expired transients from wp_options.',
					$machete_expired_transients_count,
					'machete'
				),
				number_format_i18n( $machete_expired_transients_count )
			) . '</strong><br>' . __( 'These are temporary cache entries that WordPress and plugins should have deleted automatically.', 'machete' ),
			$machete_allowed_description_tags
		);
	?></label></td>
	</tr>
	<tr>
	<th scope="row"><button type="submit" name="machete-powertools-action" id="purge_post_revisions" value="purge_post_revisions" class="button button-primary"><?php esc_html_e( 'Purge post revisions', 'machete' ); ?></button></th>
	<td><label for="purge_post_revisions"><?php
		echo wp_kses(
			'<strong>' . sprintf(
				/* translators: %1$s is the number of post revisions */
				_n(
					'Remove %1$s post revision from the database.',
					'Remove %1$s post revisions from the database.',
					$machete_post_revisions_count,
					'machete'
				),
				number_format_i18n( $machete_post_revisions_count )
			) . '</strong><br>' . __( 'Revisions are automatic snapshots saved each time a post or page is edited.', 'machete' ),
			$machete_allowed_description_tags
		);
	?></label></td>
	</tr>
	<tr>
	<th scope="row"><button type="submit" name="machete-powertools-action" id="purge_orphaned_meta" value="purge_orphaned_meta" class="button button-primary"><?php esc_html_e( 'Purge orphaned meta', 'machete' ); ?></button></th>
	<td><label for="purge_orphaned_meta"><?php
		echo wp_kses(
			'<strong>' . sprintf(
				/* translators: %1$s is the number of orphaned postmeta rows */
				_n(
					'Remove %1$s orphaned postmeta row.',
					'Remove %1$s orphaned postmeta rows.',
					$machete_orphaned_postmeta_count,
					'machete'
				),
				number_format_i18n( $machete_orphaned_postmeta_count )
			) . '</strong><br>' . __( 'These custom field records reference a post ID that no longer exists.', 'machete' ),
			$machete_allowed_description_tags
		);
	?></label></td>
	</tr>
	<tr>
	<th scope="row"><button type="submit" name="machete-powertools-action" id="purge_expired_cron" value="purge_expired_cron" class="button button-primary"><?php esc_html_e( 'Purge expired Cron', 'machete' ); ?></button></th>
	<td><label for="purge_expired_cron"><?php
		echo wp_kses(
			'<strong>' . sprintf(
				/* translators: %1$s is the number of expired cron events */
				_n(
					'Remove %1$s expired cron event from wp_options.',
					'Remove %1$s expired cron events from wp_options.',
					$machete_expired_cron_count,
					'machete'
				),
				number_format_i18n( $machete_expired_cron_count )
			) . '</strong><br>' . __( 'These scheduled tasks were missed, usually because WP-Cron did not run on time.', 'machete' ),
			$machete_allowed_description_tags
		);
	?></label></td>
	</tr>
	<tr>
	<th scope="row"><button type="submit" name="machete-powertools-action" id="flush_rewrites" value="flush_rewrites" class="button button-primary">flush_rewrite_rules()</button></th>
	<td><label for="flush_rewrites"><?php
		echo wp_kses(
			'<strong>' . __( 'Regenerate WordPress permalink rewrite rules.', 'machete' ) . '</strong><br>' . __( 'Use this after changing permalink settings, registering custom post types, or when valid URLs return 404 errors.', 'machete' ),
			$machete_allowed_description_tags
		);
	?></label></td>
	</tr>

	<?php if ( function_exists( 'opcache_reset' ) ) { ?>
	<tr>
	<th scope="row"><button type="submit" name="machete-powertools-action" id="flush_opcache" value="flush_opcache" class="button button-primary">opcache_reset()</button></th>
	<td><label for="flush_opcache"><?php
		echo wp_kses(
			'<strong>' . __( 'Clear the PHP OPcache bytecode cache.', 'machete' ) . '</strong><br>' . __( 'Use this after deploying PHP changes when the server keeps serving an older version of your files.', 'machete' ),
			$machete_allowed_description_tags
		);
	?></label></td>
	</tr>
	<?php } ?>

	<tr>
	<th scope="row"><button type="submit" name="machete-powertools-action" id="flush_wpcache" value="flush_wpcache" class="button button-primary">wp_cache_flush()</button></th>
	<td><label for="flush_wpcache"><?php
		echo wp_kses(
			'<strong>' . __( 'Flush the WordPress object cache.', 'machete' ) . '</strong><br>' . __( 'Use this when a persistent cache (Redis, Memcached, etc.) keeps serving stale data after updates.', 'machete' ),
			$machete_allowed_description_tags
		);
	?></label></td>
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
			<th class="column-title manage-column column-primary"><?php esc_html_e( 'Enable', 'machete' ); ?></th>
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
