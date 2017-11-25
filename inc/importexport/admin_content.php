<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;




?>

<div class="wrap machete-wrap machete-section-wrap">
	<h1><?php _e('Import/Export Options','machete') ?></h1>

	<p class="tab-description"><?php _e('You don\'t need a zillion plugins to perform easy task like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom styleseet (Google Fonts, Print Styles, accesibility tweaks...).','machete') ?></p>
	<?php $machete->admin_tabs('machete-importexport'); ?>
	
<?php /*
	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php _e('Performance impact:','machete') ?></strong> <?php _e('This tool generates up to three static HTML files that are loaded via PHP on each pageview. When enabled, custom body content requires one aditional database request.','machete') ?></span></p> */ ?>




<form id="mache-importexport-options" action="" method="POST">

<?php wp_nonce_field( 'machete_importexport_export' ); ?>
<input type="hidden" name="machete-importexport-export" value="true">




<h3><?php _e('Export Machete Options','machete') ?> </h3>

		<p><?php _e('This section goes futher disabling optional features. All options can be safely activated, but keep an eye on potiential plugin compatibility issues.','machete') ?></p>



		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-optimize-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_cleanup_checkall_fld" <?php if ($this->all_exportable_modules_checked) echo 'checked' ?>></td>
				<th class="column-title manage-column column-primary"><?php _e('Modules to export','machete') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($this->exportable_modules as $machete_module => $args){ ?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="moduleChecked[]" value="<?php echo $machete_module ?>" id="<?php echo $machete_module ?>_fld" <?php if ($args['checked']) echo 'checked' ?>></th>
				<td class="column-title column-primary"><strong><?php echo $args['full_title'] ?></strong>
				<button type="button" class="toggle-row"><span class="screen-reader-text"><?php _e('Show more details','machete') ?></span></button>
				</td>
				
			</tr>

		<?php } ?>

		</tbody>
		</table>

<?php submit_button(__('Download config backup', 'machete')); ?>
		
</form>
</div>



<?php

$machete_export_data = $this->export();


echo '<pre>';
print_r(unserialize(base64_decode($machete_export_data)));
echo '</pre>';
?>