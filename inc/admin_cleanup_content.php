<?php if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;?>

<div class="wrap machete-wrap machete-section-wrap">

		<h1><?php _e('Header Cleanup','machete') ?></h1>
		<p class="tab-description"></span><?php _e('WordPress places a lot of code inside the &lt;head&gt; tag just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer.','machete') ?></p>
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
	'emojicons' => array(
		'title' => __('Emojicons','machete'),
		'description' => __('Remove lots of emoji styles and scripts from the header, RSS, mail function, tinyMCE editor...','machete')
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

?>

	

		
	<!--<p class="card ">Your Purchase Must Be Registered To Receive Theme Support & Auto Updates</p>-->

	<div class="feature-section">
		<form id="mache-cleanup-options" action="" method="POST">

			<?php wp_nonce_field( 'machete_save_cleanup' ); ?>

			<input type="hidden" name="machete-cleanup-saved" value="true">
		<table class="wp-list-table widefat fixed striped posts machete-options-table">
		<thead>
			<tr>
				<td class="manage-column column-cb check-column " ><input type="checkbox" name="check_all" id="machete_checkall_fld" <?php if (count($machete_cleanup_settings) == count(array_keys($machete_cleanup_array))) echo 'checked' ?>></td>
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
		<?php submit_button(); ?>
		</form>

	</div>
			
  </div>

<script>
(function($){

	$('#mache-cleanup-options :checkbox').change(function() {
	    // this will contain a reference to the checkbox
	    console.log(this.id); 
	    var checkBoxes = $("#mache-cleanup-options input[name=optionEnabled\\[\\]]");

	    if (this.id == 'machete_checkall_fld'){
			if (this.checked) {
				checkBoxes.prop("checked", true);
			} else {
				checkBoxes.prop("checked", false);
				// the checkbox is now no longer checked
			}
	    }else{
	    	var checkBoxes_checked = $("#mache-cleanup-options input[name=optionEnabled\\[\\]]:checked");
	    	if(checkBoxes_checked.length == checkBoxes.length){
	    		$('#machete_checkall_fld').prop("checked", true);
	    	}else{
	    		$('#machete_checkall_fld').prop("checked", false);
	    	}
	    }
	});

})(jQuery);
</script>