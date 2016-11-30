<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;


$machete_maintenance_default_settings = array(
		'page_id' => '',
		'site_status' => 'online',
		'token' => strtoupper(substr(MD5(rand()),0,12))
		);

if(!$machete_maintenance_settings = get_option('machete_maintenance_settings')){
	$machete_maintenance_settings = $machete_maintenance_default_settings;
	
	// default option values saved WITHOUT autoload
        update_option( 'machete_maintenance_settings', $machete_maintenance_settings, 'yes' );

};

?>




<div class="wrap machete-wrap machete-section-wrap">
	<h1><?php _e('Coming Soon & Maintenance Mode','machete') ?></h1>

	<p class="tab-description"><?php _e('If you have to close yout website temporarly to the public, the navive WordPress maintenance mode falls short and most coming soon plugins are bulky, incomplete or expensive. Machete maintenance mode is light, simple and versatile.','machete') ?></p>
	<?php machete_admin_tabs('machete-maintenance'); ?>
	<!--<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php _e('Performance impact:','machete') ?></strong> <?php _e('This tool generates up to three static HTML files that are loaded via PHP on each pageview. When enabled, custom body content requires one aditional database request.','machete') ?></span></p>-->




<form id="mache-maintenance-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_maintenance' ); ?>
<input type="hidden" name="machete-maintenance-saved" value="true">

    <table class="form-table">

    	<tr>
		<th scope="row"><?php _e('Set site status','machete') ?></th>
		<td><fieldset>
			<label>
				<input name="site_status" value="standard" type="radio"
				<?php checked($machete_maintenance_settings['site_status'],'online') ?>>
				<strong><?php _e('Online','machete') ?></strong> - <?php _e('WordPress works as usual','machete') ?>
			</label><br>

			<label>
				<input name="site_status" value="machete" type="radio"
				<?php checked($machete_maintenance_settings['site_status'],'coming_soon') ?>>
				<strong><?php _e('Coming soon','machete') ?></strong> - <?php _e('Site closed. All pages have a meta robots noindex, nofollow','machete') ?>
			</label><br>
			<label>
				<input name="site_status" value="none" type="radio" 
				<?php checked($machete_maintenance_settings['site_status'],'maintenance') ?>>
				<strong><?php _e('Maintenance','machete') ?></strong> - <?php _e('Site closed. All pages return 503 Service unavailable','machete') ?></label><br>
		</fieldset></td>
		</tr>

		 <tr valign="top"><th scope="row"><?php _e('Magic Link','machete') ?></th>
            <td>
                <?php echo esc_url( home_url( '/?mct_token='.$machete_maintenance_settings['token'] ) ); ?>
		<p class="description"><?php _e('You can use this link to grant anyone access to the website when it is in maintenance mode.','machete') ?></p>

            </td>
        </tr>

        <tr valign="top"><th scope="row"><?php _e('Choose a page for the content','machete') ?></th>
            <td>
                <select name="content_page_id">
                	<option value=""><?php _e('Use default content','machete') ?></option>
                    <?php
                    if( $pages = get_pages() ){
                        foreach( $pages as $page ){
                            echo '<option value="' . $page->ID . '" ' . selected( $page->ID, $options['content_page_id'] ) . '>' . $page->post_title . '</option>';
                        }
                    }
                    ?>
                </select>

                <a href="<?php echo esc_url( home_url( '/?mct_preview=true')); ?>" target="machete_preview" class="button action"><?php _e('Preview','machete') ?></a>
                <input name="submit" id="submit" class="button button-primary" value="<?php _e('Save','machete') ?>" type="submit">
            </td>
        </tr>
        <tr valign="top"><th scope="row"><?php _e('Customize maintenance page','machete') ?></th>
            <td>

            <p class="description"><?php printf(__('You can customize the maintenance page CSS using the %sAnalytics & Code tab','machete'),
            '<a href="'.admin_url('admin.php?page=machete-utils').'">') ?></a></p>
            <p class="description"><?php _e('For your reference, this is the HTML used to render the Maintenance page:','machete') ?></p>
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
            </td>
    </table>
   
</form>


		

</div>


<script>

MACHETE = window.MACHETE || {};

MACHETE.maintenance = (function($){

	return {
		isAnalytics: function(str){
			if (!str) return false;
	    	// http://code.google.com/apis/analytics/docs/concepts/gaConceptsAccounts.html#webProperty
	    	return (/^ua-\d{4,9}-\d{1,4}$/i).test(str.toString());
		}
	}

})(jQuery);


(function($){
	$('#mache-maintenance-options').submit(function( e ) {
	
		/*
		var tracking_id = $('#tracking_id').val();
		var site_status = $('input[name=site_status]:checked', '#mache-maintenance-options').val();

		console.log(site_status);

		if (!MACHETE.maintenance.isAnalytics(tracking_id) && (site_status != 'none')){
			window.alert('<?php echo esc_js(__('That doesn\'t look like a valid Google Analytics tracking ID', 'machete' )); ?>');
			e.preventDefault();
			return;
		}
		*/
	});
})(jQuery);


</script>
