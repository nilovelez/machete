<?php if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;?>

<div class="wrap machete-wrap machete-section-wrap">

		<h1><?php _e('WordPress Optimization','machete') ?></h1>
		<p class="tab-description"><?php _e('WordPress has a los of code just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer','machete') ?></p>
		<?php machete_admin_tabs('machete-cleanup'); ?>
		<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php _e('Performance impact:','machete') ?></strong> <?php _e('This section stores all its settings in a single autoloaded configuration variable.','machete') ?></span></p>
		
		
<?php
if(!$machete_cleanup_settings = get_option('machete_cleanup_settings')){
	$machete_cleanup_settings = array();
};


$machete_cleanup_array = array(
	'rsd_link' => array(
		'title' => __('RSD','machete'),
		'description' => __('Remove Really Simple Discovery(RSD) links. They are used for automatic pingbacks.','machete')
	),
	'wlwmanifest' => array(
		'title' => __('WLW','machete'),
		'description' => __('Remove the link to wlwmanifest.xml. It is needed to support Windows Live Writer. Yes, seriously.','machete')
	),
	'feed_links' => array(
		'title' => __('feed_links','machete'),
		'description' => __('Remove Automatics RSS links. RSS will still work, but you will need to provide your own links.','machete')
	),
	'next_prev' => array(
		'title' => __('adjacent_posts','machete'),
		'description' => __('Remove the next and previous post links from the header','machete')
	),
	'shortlink' => array(
		'title' => __('shortlink','machete'),
		'description' => __('Remove the shortlink url from header','machete')
	),
	'wp_generator' => array(
		'title' => __('wp_generator','machete'),
		'description' => __('Remove WordPress meta generator tag. Used by atackers to detect the WordPress version.','machete')
	),
	'ver' => array(
		'title' => __('version','machete'),
		'description' => __('Remove WordPress version var (?ver=) after styles and scripts. Used by atackers to detect the WordPress version.','machete')
	),
	'json_api' => array(
		'title' => __('Json API','machete'),
		'description' => __('Disable Json API and remove link from header. Use with care.','machete')
	),
	'recentcomments' => array(
		'title' => __('recent_comments_style','machete'),
		'description' => __('Removes a block of inline CSS used by old themes from the header','machete')
	),
	'wp_resource_hints' => array(
		'title' => __('dns-prefetch','machete'),
		'description' => __('Removes dns-prefetch links from the header','machete')
	),
	
	
);

$machete_optimize_array = array(
	'emojicons' => array(
		'title' => __('Emojicons','machete'),
		'description' => __('Remove lots of emoji styles and scripts from the header, RSS, mail function, tinyMCE editor...','machete')
	),
	'pdf_thumbnails' => array(
		'title' => __('PDF Thumbnails','machete'),
		'description' => __('Starting with 4.7, WordPress tries to make thumbnails from each PDF you upload, potentially crashing your server if GhostScript and Imagemagick aren\'t properly configured. This option disables PDF thumbnails.','machete')
	),
	'limit_revisions' => array(
		'title' => __('Limit Post Revisions','machete'),
		'description' => __('Limits the number of stored revisions to 5 only WP_POST_REVISIONS constant has been defined.','machete')
	),
	'jquery-migrate' => array(
		'title' => __('remove jQuery-migrate','machete'),
		'description' => __('jQuery-migrate provides diagnostics that can simplify upgrading to new versions of jQuery, you can safely disable it.','machete')
	),
);

$machete_all_optimize_checked = (count(array_intersect(array_keys($machete_optimize_array), $machete_cleanup_settings)) == count($machete_optimize_array)) ? true : false;

$machete_all_cleanup_checked = (count(array_intersect(array_keys($machete_cleanup_array), $machete_cleanup_settings)) == count($machete_cleanup_array)) ? true : false;
?>

	

		
	<!--<p class="card ">Your Purchase Must Be Registered To Receive Theme Support & Auto Updates</p>-->

	<div class="feature-section">
		<form id="machete-cleanup-options" action="" method="POST">

			<?php wp_nonce_field( 'machete_save_cleanup' ); ?>

			<input type="hidden" name="machete-cleanup-saved" value="true">

		<h3><?php _e('Header Cleanup','machete') ?></h3>

		<p><?php _e('This section removes code from the &lt;head&gt; tag. This makes your site faster and reduces the amount of information revealed to a potential attacker.','machete') ?></p>

		
		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-cleanup-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_cleanup_checkall_fld" <?php if ($machete_all_cleanup_checked) echo 'checked' ?>></td>
				<th class="column-title"><?php _e('Remove','machete') ?></th>
				<th><?php _e('Explanation','machete') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($machete_cleanup_array as $option_slug => $option){ ?>
			<tr>
				<td><input type="checkbox" name="optionEnabled[]" value="<?php echo $option_slug ?>" id="<?php echo $option_slug ?>_fld" <?php if (in_array($option_slug, $machete_cleanup_settings)) echo 'checked' ?>></td>
				<td class="column-title column-primary"><strong><?php echo $option['title'] ?></strong></td>
				<td><?php echo $option['description'] ?></td>
			</tr>

		<?php } ?>

		</tbody>
		</table>

		<h3><?php _e('Optimization Tweaks','machete') ?></h3>

		<p><?php _e('This section goes futher disabling optional features. All options can be safely activated, but keep an eye on potiential plugin compatibility issues.','machete') ?></p>



		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-optimize-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_optimize_checkall_fld" <?php if ($machete_all_optimize_checked) echo 'checked' ?>></td>
				<th class="column-title"><?php _e('Remove','machete') ?></th>
				<th><?php _e('Explanation','machete') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($machete_optimize_array as $option_slug => $option){ ?>
			<tr>
				<td><input type="checkbox" name="optionEnabled[]" value="<?php echo $option_slug ?>" id="<?php echo $option_slug ?>_fld" <?php if (in_array($option_slug, $machete_cleanup_settings)) echo 'checked' ?>></td>
				<td class="column-title column-primary"><strong><?php echo $option['title'] ?></strong></td>
				<td><?php echo $option['description'] ?></td>
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