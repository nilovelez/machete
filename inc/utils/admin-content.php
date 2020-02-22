<?php
/**
 * Content of the "Import/Export" page.

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
}

$machete_header_content = $this->get_contents( MACHETE_DATA_PATH . 'header.html' );
if ( false !== $machete_header_content ) {
	$machete_header_content = explode( "<!-- Machete Header -->\n", $machete_header_content );
	switch ( count( $machete_header_content ) ) {
		case 1:
			$machete_header_content = $machete_header_content[0];
			break;
		case 2:
			$machete_header_content = $machete_header_content[1];
			break;
		default:
			$machete_header_content = implode( '', array_slice( $machete_header_content, 1 ) );
	}
} else {
	$machete_header_content = '';
}

$machete_footer_content = $this->get_contents( MACHETE_DATA_PATH . 'footer.html' );
if ( ! $machete_footer_content ) {
	$machete_footer_content = '';
}

$machete_alfonso_content = $this->get_contents( MACHETE_DATA_PATH . 'body.html' );
if ( ! $machete_alfonso_content ) {
	$machete_alfonso_content = '';
}

$machete_settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

if ( false !== $machete_settings ) {

	wp_enqueue_script(
		'machete_codemirror_tabs',
		plugins_url( 'js/codemirror_tabs.js', __FILE__ ),
		array(),
		MACHETE_VERSION,
		true
	);
}
?>

<div class="wrap machete-wrap machete-section-wrap">
	<div class="wp-header-end"></div><!-- admin notices go after .wp-header-end or .wrap>h2:first-child -->
	<h1><?php $this->icon(); ?> <?php esc_html_e( 'Analytics and Custom Code', 'machete' ); ?></h1>
	<p class="tab-description"><?php esc_html_e( 'You don\'t need a zillion plugins to perform easy task like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom styleseet (Google Fonts, Print Styles, accesibility tweaks...).', 'machete' ); ?></p>
	<?php $machete->admin_tabs( 'machete-utils' ); ?>
	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php esc_html_e( 'This tool generates up to three static HTML files that are loaded via PHP on each pageview. When enabled, custom body content requires one aditional database request.', 'machete' ); ?></span></p>

<form id="mache-utils-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_utils' ); ?>
<input type="hidden" name="machete-utils-saved" value="true">




<div id="machete-tabs" class="nav-tab-wrapper" style="display: none;">

	<a href="#machete-tabs-tracking" data-tab="machete-tabs-tracking" class="nav-tab machete-tabs-tracking"><?php esc_html_e( 'Tracking settings', 'machete' ); ?></a>
	<a href="#machete-tabs-header" data-tab="machete-tabs-header" class="nav-tab machete-tabs-header"><?php esc_html_e( '<head> code', 'machete' ); ?></a>
	<a href="#machete-tabs-alfonso" data-tab="machete-tabs-alfonso" class="nav-tab machete-tabs-alfonso"><?php esc_html_e( '<body> code', 'machete' ); ?></a>
	<a href="#machete-tabs-footer" data-tab="machete-tabs-footer" class="nav-tab machete-tabs-footer"><?php esc_html_e( 'Footer code', 'machete' ); ?></a>

</div>

<div class="machete-tabs-content" id="machete-tabs-tracking">

	<table class="form-table">
	<tbody><tr>


	<tr>
	<th scope="row"><?php esc_html_e( 'Tracking Code', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Tracking Code', 'machete' ); ?></span></legend>
		<label><input name="tracking_format" value="standard" type="radio" <?php checked( 'standard', $this->settings['tracking_format'], true ); ?>> <?php esc_html_e( 'Standard Google Analytics tracking code', 'machete' ); ?></label><br>
		<label><input name="tracking_format" value="machete" type="radio" <?php checked( 'machete', $this->settings['tracking_format'], true ); ?>> <acronym title="<?php esc_attr_e( 'Uses JavaScript to hide the tracking code from PageSpeed and GoogleBot', 'machete' ); ?>"><?php esc_html_e( 'PageSpeed-optimized tracking code', 'machete' ); ?></acronym> <span class="machete_deprecated_badge"><?php esc_html_e( 'Deprecated', 'machete' ); ?></span></label><br>
		<label><input name="tracking_format" value="none" type="radio" <?php checked( 'none', $this->settings['tracking_format'], true ); ?>> <?php esc_html_e( 'No tracking code', 'machete' ); ?></label><br>
	</fieldset></td>
	</tr>

	<tr>
	<th scope="row"><label for="tracking_id"><?php esc_html_e( 'Tracking ID', 'machete' ); ?></label></th>
	<td><input name="tracking_id" id="tracking_id" aria-describedby="tracking_id_description" value="<?php echo esc_attr( $this->settings['tracking_id'] ); ?>" class="regular-text" type="text">
	<p class="description" id="tracking_id_description"><?php esc_html_e( 'Format:', 'machete' ); ?> UA-12345678-1</p></td>
	</tr>

	<th scope="row"><?php esc_html_e( 'CF7 Tracking', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Anonymize user IPs', 'machete' ); ?></span></legend>
		<?php // translators: %s: link to the plugin's directory page. ?>
		<label><input name="track_wpcf7" value="1" type="checkbox" <?php checked( '1', $this->settings['track_wpcf7'], true ); ?>> <?php printf( wp_kses_data( __( 'Launch a Google Analytics event whenever a visitor submits a <a href="%s">Contact Form 7</a> form.', 'machete' ) ), 'https://wordpress.org/plugins/contact-form-7/' ); ?></label><br>
	</fieldset></td>
	</tr>
	<tr>
	<th scope="row"><?php esc_html_e( 'Anonymize user IPs', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Anonymize user IPs', 'machete' ); ?></span></legend>
		<label><input name="tacking_anonymize" value="1" type="checkbox" <?php checked( '1', $this->settings['tacking_anonymize'], true ); ?>> <?php esc_html_e( 'Check to anonymize visitor IPs. Required in some countries.', 'machete' ); ?></label><br>
	</fieldset></td>
	</tr>
	</table>


</div>


<div class="machete-tabs-content" id="machete-tabs-header">

	<h2><?php esc_html_e( 'Custom header content', 'machete' ); ?></h2>

	<fieldset><legend class="screen-reader-text"><?php esc_html_e( 'Custom header content', 'machete' ); ?></legend>

	<?php // translators: $s: file path. ?>
	<label for="header_content"><p><?php printf( wp_kses_post( __( 'This code is included before the closing <code>&lt;/head&gt;</code> label.<br>Content is saved to <code>%s</code> and served using PHP\'s <code>readfile()</code> function, so no PHP or shortcodes here.', 'machete' ) ), esc_html( MACHETE_RELATIVE_DATA_PATH . 'header.html' ) ); ?></p></label>

	<textarea name="header_content" rows="8" cols="50" id="header_content" class="large-text code"><?php echo esc_textarea( $machete_header_content ); ?></textarea>
	</fieldset>

</div>


<div class="machete-tabs-content" id="machete-tabs-alfonso">


	<h2><?php esc_html_e( 'Custom body content', 'machete' ); ?></h2>

	<fieldset><legend class="screen-reader-text"><?php esc_html_e( 'Custom body content', 'machete' ); ?></legend>
	<label for="alfonso_content"><p><?php echo wp_kses_post( __( 'This block is meant to be included just after the begining of the <code>&lt;body&gt;</code> label, and it\'s mainly used for conversion tracking codes.', 'machete' ) ); ?><br>
		<?php // translators: $s: file path. ?>
		<?php printf( wp_kses_post( __( 'Content is saved to <code>%s</code> and served using PHP.  No PHP code or shortcodes here.', 'machete' ) ), esc_html( MACHETE_RELATIVE_DATA_PATH . 'body.html' ) ); ?>
	</p></label>

	<textarea name="alfonso_content" rows="8" cols="50" id="alfonso_content" class="large-text code"><?php echo esc_textarea( $machete_alfonso_content ); ?></textarea>
	</fieldset>



<table class="form-table">
	<tbody><tr>
	<tr>
	<th scope="row"><?php esc_html_e( 'Code injection method', 'machete' ); ?></th>
	<td><fieldset>

		<legend class="screen-reader-text"><span><?php esc_html_e( 'Custom body content injection method:', 'machete' ); ?></span></legend>

		<label><input name="alfonso_content_injection_method" value="wp_body" type="radio" <?php checked( 'wp_body', $this->settings['alfonso_content_injection_method'], true ); ?>> <?php echo wp_kses_data( __( 'Include the code using the native <code>wp_body</code> hook (not supported by all themes).' ) ); ?></label><br>

		<?php // translators: %s url of article with explanation. ?> 
		<label><input name="alfonso_content_injection_method" value="auto" type="radio" <?php checked( 'auto', $this->settings['alfonso_content_injection_method'], true ); ?>> <?php printf( wp_kses_data( __( 'Try to inject the code automatically using <a href="%s" target="_blank" rel="nofollow">Yaniv Friedensohn\'s method</a>', 'machete' ) ), 'http://www.affectivia.com/blog/placing-the-google-tag-manager-in-wordpress-after-the-body-tag/' ); ?></label><br>

		<label><input name="alfonso_content_injection_method" value="manual" type="radio" <?php checked( 'manual', $this->settings['alfonso_content_injection_method'], true ); ?>> <?php echo wp_kses_data( __( 'Edit your theme\'s <code>header.php</code> template manually and include this function:', 'machete' ) ); ?> <code>&lt;?php machete_custom_body_content() ?&gt;</code></label>
	</fieldset></td>
	</tr>
	</table>








</div>


<div class="machete-tabs-content" id="machete-tabs-footer">

	<h2><?php esc_html_e( 'Custom footer content', 'machete' ); ?></h2>

	<fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Custom footer content', 'machete' ); ?></span></legend>
	<?php // translators: $s: file path. ?>
	<label for="footer_content"><p><?php printf( wp_kses_post( __( 'This code is included when the <code>wp_footer</code> action is called, normally just before the closing <code>&lt;/body&gt;</code> label.<br>Content is saved to <code>%s</code> and served using PHP\'s <code>readfile()</code> function, so no PHP or shortcodes here.', 'machete' ) ), esc_html( MACHETE_RELATIVE_DATA_PATH . 'footer.html' ) ); ?></p></label>

	<textarea name="footer_content" rows="8" cols="50" id="footer_content" class="large-text code"><?php echo esc_textarea( $machete_footer_content ); ?></textarea>
	</fieldset>

</div>

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
			window.alert('<?php echo esc_js( __( 'That doesn\'t look like a valid Google Analytics tracking ID', 'machete' ) ); ?>');
			e.preventDefault();
			return;
		}
	});
})(jQuery);

</script>
