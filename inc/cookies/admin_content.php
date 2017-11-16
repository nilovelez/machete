<?php if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit; ?>


<div class="wrap machete-wrap machete-section-wrap">
	<h1><?php _e('Cookie Law Warning','machete') ?></h1>
	<p class="tab-description"><?php _e('We know you hate cookie warning bars. Well, this is the less hateable cookie bar you\'ll find. It is really light, it won\'t affect your PageSpeed score and plays well with static cache plugins.','machete') ?></p>

	<?php $machete->admin_tabs('machete-cookies'); ?>

	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php _e('Performance impact:','machete') ?></strong> <?php _e('This tool adds 0,4Kb and a single database request request to each page load. The remaining <abbr title="~1.1Kb if GZipped">~2.5Kb</abbr> of code is loaded asynchronously via javascript from a pregenerated static file.','machete') ?></span></p>



<form id="mache-cookies-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_cookies' ); ?>
<input type="hidden" name="machete-cookies-saved" value="true">

<table class="form-table">
<tbody><tr>

<tr>

<tr>
<th scope="row"><?php _e('Cookie alert status','machete') ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Cookie alert status','machete') ?></span></legend>
	<label><input name="bar_status" value="enabled" type="radio" <?php if ($this->settings['bar_status'] =='enabled') echo 'checked="checked"'; ?>> <?php _e('Enabled','machete') ?></label><br>
	<label><input name="bar_status" value="disabled" type="radio" <?php if ($this->settings['bar_status'] =='disabled') echo 'checked="checked"'; ?>> <?php _e('Disabled','machete') ?></label><br>
	
</fieldset></td>
</tr>

<tr>
<th scope="row"><label for="warning_text"><?php _e('Cookie warning text','machete') ?></label></th>
<td><textarea name="warning_text" rows="3" cols="50" id="warning_text" class="large-text code"><?php echo esc_textarea(stripslashes($this->settings['warning_text'])) ?></textarea>
<p class="description" style="text-align: right;"><a id="restore_cookie_text_btn"><?php _e('Restore default warning text','machete') ?></a></p>
</fieldset></td>
</tr>
<tr>
<th scope="row"><label for="accept_text"><?php _e('Accept button text','machete') ?></label></th>
<td><input name="accept_text" id="accept_text" value="<?php echo esc_html($this->settings['accept_text']) ?>" class="regular-text ltr" type="text"></td>
</tr>

<tr>
<th scope="row"><?php _e('Cookie bar theme','machete') ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Cookie bar theme','machete') ?></span></legend>
	<label><input name="bar_theme" value="light" type="radio" <?php if ($this->settings['bar_theme'] =='light') echo 'checked="checked"'; ?>> <?php _e('Light','machete') ?></label><br>
	<label><input name="bar_theme" value="dark" type="radio" <?php if ($this->settings['bar_theme'] =='dark') echo 'checked="checked"'; ?>> <?php _e('Dark','machete') ?></label><br>

	<label><input name="bar_theme" value="cookie" type="radio" <?php if ($this->settings['bar_theme'] =='cookie') echo 'checked="checked"'; ?>> <?php _e('Cookie!','machete') ?></label>
	
</fieldset></td>
</tr>
</tbody></table>


<?php submit_button(); ?>
</form>




<div class="postbox machete-helpbox" data-collapsed="false">
<button type="button" class="handlediv button-link"><span class="toggle-indicator" aria-hidden="true"><span class="dashicons dashicons-arrow-up"></span></span></button>
	<h3 class="hndle"><span><span class="dashicons dashicons-sos"></span> <?php echo _e('How do I customize the cookie bar?','machete') ?></span></h2>
	<div class="inside">
		<table class="form-table"><tbody><tr valign="top"><th scope="row"></th><td>

			<p class="description"><?php printf(__('You can customize the cookie bar CSS using the %sAnalytics & Code tab','machete'),
            '<a href="'.admin_url('admin.php?page=machete-utils').'">') ?></a></p>
            <p class="description"><?php _e('For your reference, this is the HTML used to render the cookie bar:','machete') ?></p>
            <pre style="color: #00f; font-weight: bold;">&lt;html&gt;
  &lt;head&gt;
    &lt;title&gt;<span style="color: #000;">[<?php _e('title of the selected page','machete') ?>]</span>&lt;/title&gt;
    <span style="color: #000;">[&hellip;]</span>
  &lt;/head&gt;
  &lt;body <span style="color: #c00;">id</span>=<span style="color: #f0f;">"maintenance_page"</span>&gt;
    &lt;div <span style="color: #c00;">id</span>=<span style="color: #f0f;">"content"</span>&gt;
      <span style="color: #000;">[<?php _e('content of the selected page','machete') ?>]</span>
    &lt;/div&gt;
  &lt;/body&gt;
&lt;/html&gt;</pre>
		</td></tr></tbody></table> 
    </div>
</div>






</div>


<script>

(function($){
	$('#mache-cookies-options').submit(function( e ) {
		
		if ( $( '#warning_text' ).val() == '' ) {
			window.alert('<?php echo esc_js(__('Cookie warning text can\'t be blank', 'machete' )); ?>');
			e.preventDefault();
			return;
		}
		if ( $( '#accept_text' ).val() == '' ) {
			window.alert('<?php echo esc_js(__('Accept button text can\'t be blank', 'machete' )); ?>');
			e.preventDefault();
			return;
		}
	});
	$('#restore_cookie_text_btn').click(function() {
		$( '#warning_text' ).val('<?php echo $this->default_settings['warning_text'] ?>');
		$( '#accept_text' ).val('<?php echo $this->default_settings['accept_text'] ?>');
	});


	$('button.handlediv').click(function( e ){
		$( this ).parent().find('.hndle').click();
	});

	$('.machete-helpbox .hndle').click(function( e ){
		var container  = $( this ).parent();

		if (container.attr('data-collapsed') == 'false'){
			close_helpbox(container);
		} else {
			open_helpbox(container);
		}
		
	});
	
	var close_helpbox  = function(elem){
		elem.attr('data-collapsed','true');
		elem.find('div.inside').hide();
		elem.find('.toggle-indicator span').attr('class','dashicons dashicons-arrow-down');
	}
	var open_helpbox  = function(elem){
		elem.attr('data-collapsed','false');
		elem.find('div.inside').show();
		elem.find('.toggle-indicator span').attr('class','dashicons dashicons-arrow-up');
	}

	$('.machete-helpbox').each(function (index){
		close_helpbox($( this ));
	});


})(jQuery);


</script>