<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;

$machete_utils_default_settings = array(
		'tracking_id' => '',
		'tracking_format' => 'none',
		'tacking_anonymize' => 0,
		'alfonso_content_injection_method' => 'manual'
		);

if(!$machete_utils_settings = get_option('machete_utils_settings')){
	$machete_utils_settings = $machete_utils_default_settings;
}else{
	$machete_utils_settings = array_merge($machete_utils_default_settings, $machete_utils_settings);
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
}else{
	$machete_header_content = '';
}

if(!$machete_footer_content = @file_get_contents(MACHETE_DATA_PATH.'footer.html')){
	$machete_footer_content = '';
}
if(!$machete_alfonso_content = @file_get_contents(MACHETE_DATA_PATH.'body.html')){
	$machete_alfonso_content = '';
}


if (defined ('MACHETE_POWERTOOLS_INIT') ) {
	include (MACHETE_BASE_PATH.'inc/powertools/utils_highlight.php');
} ?>

<div class="wrap machete-wrap machete-section-wrap">
	<h1><?php _e('Analytics and Custom Code','machete') ?></h1>

	<p class="tab-description"><?php _e('You don\'t need a zillion plugins to perform easy task like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom styleseet (Google Fonts, Print Styles, accesibility tweaks...).','machete') ?></p>
	<?php $machete->admin_tabs('machete-utils'); ?>
	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php _e('Performance impact:','machete') ?></strong> <?php _e('This tool generates up to three static HTML files that are loaded via PHP on each pageview. When enabled, custom body content requires one aditional database request.','machete') ?></span></p>




<form id="mache-utils-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_utils' ); ?>
<input type="hidden" name="machete-utils-saved" value="true">

<table class="form-table">
<tbody><tr>

<tr>
<th scope="row"><label for="tracking_id"><?php _e('Tracking ID','machete') ?></label></th>
<td><input name="tracking_id" id="tracking_id" aria-describedby="tracking_id_description" value="<?php echo $machete_utils_settings['tracking_id'] ?>" class="regular-text" type="text">
<p class="description" id="tracking_id_description"><?php _e('Format:','machete') ?> UA-12345678-1</p></td>
</tr>

<tr>
<th scope="row"><?php _e('Tracking Code','machete') ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Tracking Code','machete') ?></span></legend>
	<label><input name="tracking_format" value="standard" type="radio" <?php if ($machete_utils_settings['tracking_format'] =='standard') echo 'checked="checked"'; ?>> <?php _e('Standard Google Analytics tracking code','machete') ?></label><br>
	<label><input name="tracking_format" value="machete" type="radio" <?php if ($machete_utils_settings['tracking_format'] =='machete') echo 'checked="checked"'; ?>> <?php _e('PageSpeed-optimized tracking code','machete') ?></label><br>
	<label><input name="tracking_format" value="none" type="radio" <?php if ($machete_utils_settings['tracking_format'] =='none') echo 'checked="checked"'; ?>> <?php _e('No tracking code','machete') ?></label><br>
</fieldset></td>
</tr>
<tr>
<th scope="row"><?php _e('Anonymize user IPs','machete') ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Anonymize user IPs','machete') ?></span></legend>
	<label><input name="tacking_anonymize" value="1" type="checkbox" <?php if ($machete_utils_settings['tacking_anonymize'] =='1') echo 'checked="checked"'; ?>> <?php _e('Check to anonymize visitor IPs. This feature is designed to help site owners comply with their own privacy policies or, in some countries, recommendations from local data protection authorities.','machete') ?></label><br>
</fieldset></td>
</tr>


<tr>
<th scope="row"><?php _e('Custom header content','machete') ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Custom header content','machete') ?></span></legend>


<p><label for="header_content"><?php printf(__('This code is included before the closing <code>&lt;/head&gt;</code> label.<br>Content is saved to <code>%s</code> and served using PHP\'s <code>readfile()</code> function, so no PHP or shortcodes here.','machete'), MACHETE_RELATIVE_DATA_PATH.'header.html') ?></label></p>
<p><textarea name="header_content" rows="8" cols="50" id="header_content" class="large-text code"><?php if (!empty($machete_header_content)) echo esc_textarea($machete_header_content) ?></textarea></p>
</fieldset></td>
</tr>
<tr>
<th scope="row"><?php _e('Custom body content','machete') ?></th>
<td>
<p><label for="alfonso_content"><?php _e('This block is meant to be included just after the begining of the <code>&lt;body&gt;</code> label, and it\'s mainly used for conversion tracking codes. There isn\'t any standard hook here, which leave you with two options:','machete') ?></label></p>


<fieldset style="margin: 1em 0;"><legend class="screen-reader-text"><span><?php _e('Custom body content injection method','machete') ?></span></legend>
	<label><input name="alfonso_content_injection_method" value="auto" type="radio" <?php if ($machete_utils_settings['alfonso_content_injection_method'] =='auto') echo 'checked="checked"'; ?>> <?php printf(__('Try to inject the code automatically using <a href="%s" target="_blank" rel="nofollow">Yaniv Friedensohn\'s method</a>','machete'), 'http://www.affectivia.com/blog/placing-the-google-tag-manager-in-wordpress-after-the-body-tag/') ?></label><br>
	<label><input name="alfonso_content_injection_method" value="manual" type="radio" <?php if ($machete_utils_settings['alfonso_content_injection_method'] =='manual') echo 'checked="checked"'; ?>> <?php _e('Edit your theme\'s <code>header.php</code> template manually and include this function:','machete') ?> <code>&lt;?php machete_custom_body_content() ?&gt;</code></label>
</fieldset>

<fieldset><legend class="screen-reader-text"><span><?php _e('Custom body content','machete') ?></span></legend>
<p><?php printf(__('Content is saved to <code>%s</code> and served using PHP.  Inclusion method varies, so no PHP code or shortcodes here.','machete'), MACHETE_RELATIVE_DATA_PATH.'body.html') ?></p>
<p>
<textarea name="alfonso_content" rows="8" cols="50" id="alfonso_content" class="large-text code"><?php if (!empty($machete_alfonso_content)) echo esc_textarea($machete_alfonso_content)  ?></textarea>
</p>
</fieldset></td>
</tr>
<tr>
<th scope="row"><?php _e('Custom footer content','machete') ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Custom footer content','machete') ?></span></legend>
<p><label for="footer_content"><?php printf(__('This code is included when the <code>wp_footer</code> action is called, normally just before the closing <code>&lt;/body&gt;</code> label.<br>Content is saved to <code>%s</code> and served using PHP\'s <code>readfile()</code> function, so no PHP or shortcodes here.','machete'), MACHETE_RELATIVE_DATA_PATH.'header.html') ?></label></p>
<p>
<textarea name="footer_content" rows="8" cols="50" id="footer_content" class="large-text code"><?php if (!empty($machete_footer_content)) echo esc_textarea($machete_footer_content)  ?></textarea>
</p>
</fieldset></td>
</tr>
</tbody></table>

<?php submit_button(); ?>
		
</form>
</div>


<script>

MACHETE = window.MACHETE || {};

MACHETE.utils = (function($){

	return {
		isAnalytics: function(str){
			if (!str) return false;
	    	// http://code.google.com/apis/analytics/docs/concepts/gaConceptsAccounts.html#webProperty
	    	return (/^ua-\d{4,9}-\d{1,4}$/i).test(str.toString());
		}
	}

})(jQuery);


(function($){
	$('#mache-utils-options').submit(function( e ) {
	

		var tracking_id = $('#tracking_id').val();
		var tracking_format = $('input[name=tracking_format]:checked', '#mache-utils-options').val();

		console.log(tracking_format);

		if (!MACHETE.utils.isAnalytics(tracking_id) && (tracking_format != 'none')){
			window.alert('<?php echo esc_js(__('That doesn\'t look like a valid Google Analytics tracking ID', 'machete' )); ?>');
			e.preventDefault();
			return;
		}
	});
})(jQuery);


</script>