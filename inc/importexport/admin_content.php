<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;


$machete_export = array();

$machete_export['utils']= array(
	'settings' => array(
		'tracking_id' => '',
		'tracking_format' => 'none',
		'tacking_anonymize' => 0,
		'alfonso_content_injection_method' => 'manual'
	),
	'header_content' => '',
	'alfonso_content' => '',
	'footer_content' => ''
);


if($machete_utils_settings = get_option('machete_utils_settings')){
	$machete_export['utils']['settings'] = $machete_utils_settings;
}

if($machete_header_content = @file_get_contents(MACHETE_DATA_PATH.'header.html')){
	$machete_header_content = explode('<!-- Machete Header -->', $machete_header_content);
	switch(count($machete_header_content)){
		case 1:
			$machete_header_content = $machete_header_content[0];
			break;
		case 2:
			$machete_header_content = $machete_header_content[1];
			break;
		default:
			$machete_header_content = implode('',array_slice($machete_header_content, 1));
	}
	$machete_export['utils']['header_content'];
}

$machete_export['utils']['footer_content'] = @file_get_contents(MACHETE_DATA_PATH.'footer.html');
$machete_export['utils']['alfonso_content'] = @file_get_contents(MACHETE_DATA_PATH.'body.html');

$machete_export_data = base64_encode(serialize($machete_export));



$machete_exportable_modules = array();

foreach ($machete_modules as $machete_module => $args) {
    if ( ! $args['has_config'] ) continue;
    $machete_exportable_modules[$machete_module] = array(
    	'title' => $args['title'],
    	'full_title' => $args['full_title'],
    	'checked' => true
    );
    $machete_all_exportable_modules_checked = true;
}


?>

<div class="wrap machete-wrap machete-section-wrap">
	<h1><?php _e('Import/Export Options','machete') ?></h1>

	<p class="tab-description"><?php _e('You don\'t need a zillion plugins to perform easy task like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom styleseet (Google Fonts, Print Styles, accesibility tweaks...).','machete') ?></p>
	<?php machete_admin_tabs('machete-importexport'); ?>
	
<?php /*
	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php _e('Performance impact:','machete') ?></strong> <?php _e('This tool generates up to three static HTML files that are loaded via PHP on each pageview. When enabled, custom body content requires one aditional database request.','machete') ?></span></p> */ ?>




<form id="mache-utils-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_utils' ); ?>
<input type="hidden" name="machete-utils-saved" value="true">




<h3><?php _e('Export Machete Options','machete') ?> </h3>

		<p><?php _e('This section goes futher disabling optional features. All options can be safely activated, but keep an eye on potiential plugin compatibility issues.','machete') ?></p>



		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-optimize-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_cleanup_checkall_fld" <?php if ($machete_all_exportable_modules_checked) echo 'checked' ?>></td>
				<th class="column-title manage-column column-primary"><?php _e('Modules to export','machete') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($machete_exportable_modules as $machete_module => $args){ ?>
			<tr>
				<th scope="row" class="check-column"><input type="checkbox" name="moduleEnabled[]" value="<?php echo $option_slug ?>" id="<?php echo $args['slug'] ?>_fld" <?php if ($args['checked']) echo 'checked' ?>></th>
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
echo '<pre>';
print_r(unserialize(base64_decode($machete_export_data)));
echo '</pre>';
?>