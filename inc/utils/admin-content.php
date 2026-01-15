<?php
/**
 * Content of the "Optimization" page.

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
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

$machete_editor_settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

if ( false !== $machete_editor_settings ) {

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
	<p class="tab-description"><?php esc_html_e( 'You don\'t need a zillion plugins to perform easy tasks like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom stylesheet (Google Fonts, Print Styles, accessibility tweaks...).', 'machete' ); ?></p>
	<?php $machete->admin_tabs( 'machete-utils' ); ?>
	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php esc_html_e( 'This tool generates up to three static HTML files that are loaded via PHP on each pageview. When enabled, custom body content requires one additional database request.', 'machete' ); ?></span></p>

<form id="mache-utils-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_utils' ); ?>
<input type="hidden" name="machete-utils-saved" value="true">




<div id="machete-tabs" class="nav-tab-wrapper" style="display: none;">

	<a href="#machete-tabs-tracking" data-tab="machete-tabs-tracking" class="nav-tab machete-tabs-tracking"><?php esc_html_e( 'Tracking settings', 'machete' ); ?></a>
	<?php // Translators: <head> or <body>. ?>
	<a href="#machete-tabs-header" data-tab="machete-tabs-header" class="nav-tab machete-tabs-header"><?php echo esc_html( sprintf( __( '%s code', 'machete' ), '<head>' ) ); ?></a>
	<?php // Translators: <head> or <body>. ?>
	<a href="#machete-tabs-alfonso" data-tab="machete-tabs-alfonso" class="nav-tab machete-tabs-alfonso"><?php echo esc_html( sprintf( __( '%s code', 'machete' ), '<body>' ) ); ?></a>
	<a href="#machete-tabs-footer" data-tab="machete-tabs-footer" class="nav-tab machete-tabs-footer"><?php esc_html_e( 'Footer code', 'machete' ); ?></a>

</div>

<div class="machete-tabs-content" id="machete-tabs-tracking">

	<table class="form-table">
	<tbody>

	<tr>
	<th scope="row"><?php esc_html_e( 'Tracking Codes', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Tracking Codes', 'machete' ); ?></span></legend>
		<label><input name="tracking_format" value="standard" type="radio" <?php checked( 'standard', $this->settings['tracking_format'], true ); ?>> <?php esc_html_e( 'Active', 'machete' ); ?></label><br>
		<label><input name="tracking_format" value="none" type="radio" <?php checked( 'none', $this->settings['tracking_format'], true ); ?>> <?php esc_html_e( 'Inactive', 'machete' ); ?></label><br>
	</fieldset></td>
	</tr>

	<tr>
	<th scope="row"><label for="tracking_ga4"><?php esc_html_e( 'Google Analytics 4', 'machete' ); ?></label></th>
	<td><input name="tracking_ga4" id="tracking_ga4" aria-describedby="tracking_ga4_description" value="<?php echo esc_attr( $this->settings['tracking_ga4'] ); ?>" class="regular-text" type="text">
	<p class="description" id="tracking_ga4_description"><?php esc_html_e( 'Google Analytics 4 property ID', 'machete' ); ?><br><?php esc_html_e( 'Valid format:', 'machete' ); ?> G-123456ABCD</p></td>
	</tr>

	<?php if ( $this->settings['tracking_id'] ) { ?>
	<tr>
	<th scope="row"><label for="tracking_id"><?php esc_html_e( 'Universal Analytics', 'machete' ); ?></label></th>
	<td><input aria-describedby="tracking_id_description" value="<?php echo esc_attr( $this->settings['tracking_id'] ); ?>" class="regular-text" type="text" disabled>
	<p class="description" id="tracking_id_description"><?php esc_html_e( 'Deprecated. The property id will be deleted as soon as you save settings.', 'machete' ); ?></p></td>
	</tr>
	<?php } ?>

	<tr>
	<th scope="row"><?php esc_html_e( 'Anonymize user IPs', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Anonymize user IPs', 'machete' ); ?></span></legend>
		<label><input name="tacking_anonymize" value="1" type="checkbox" <?php checked( '1', $this->settings['tacking_anonymize'], true ); ?>> <?php esc_html_e( 'Check to anonymize visitor IPs. Required in some countries.', 'machete' ); ?></label><br>
	</fieldset></td>
	</tr>

	<tr>
	<th scope="row"><label for="tracking_gtm"><?php esc_html_e( 'Google Tag Manager', 'machete' ); ?></label></th>
	<td><input name="tracking_gtm" id="tracking_gtm" aria-describedby="tracking_gtm_description" value="<?php echo esc_attr( $this->settings['tracking_gtm'] ); ?>" class="regular-text" type="text">
	<p class="description" id="tracking_gtm_description"><?php esc_html_e( 'Tag Manager container ID', 'machete' ); ?><br><?php esc_html_e( 'Valid format:', 'machete' ); ?> GTM-123456ABCD</p></td>
	</tr>

	</tbody>
	</table>


</div>


<div class="machete-tabs-content" id="machete-tabs-header">

	<h2><?php esc_html_e( 'Custom header content', 'machete' ); ?></h2>

	<fieldset><legend class="screen-reader-text"><?php esc_html_e( 'Custom header content', 'machete' ); ?></legend>

	<?php // translators: $s: file path. ?>
	<label for="header_content"><p><?php printf( wp_kses_post( __( 'This code is included before the closing <code>&lt;/head&gt;</code> tag.<br>Content is saved to <code>%s</code> and served using PHP\'s <code>readfile()</code> function, so no PHP or shortcodes here.', 'machete' ) ), esc_html( MACHETE_RELATIVE_DATA_PATH . 'header.html' ) ); ?></p></label>

	<textarea name="header_content" rows="8" cols="50" id="header_content" class="large-text code" data-lpignore="true"><?php echo esc_textarea( $machete_header_content ); ?></textarea>
	</fieldset>

</div>


<div class="machete-tabs-content" id="machete-tabs-alfonso">


	<h2><?php esc_html_e( 'Custom body content', 'machete' ); ?></h2>

	<fieldset><legend class="screen-reader-text"><?php esc_html_e( 'Custom body content', 'machete' ); ?></legend>
	<label for="alfonso_content"><p><?php echo wp_kses_post( __( 'This block is meant to be included just after the beginning of the <code>&lt;body&gt;</code> tag, and it\'s mainly used for conversion tracking codes.', 'machete' ) ); ?><br>
		<?php // translators: $s: file path. ?>
		<?php printf( wp_kses_post( __( 'Content is saved to <code>%s</code> and served using PHP. No PHP code or shortcodes here.', 'machete' ) ), esc_html( MACHETE_RELATIVE_DATA_PATH . 'body.html' ) ); ?>
	</p></label>

	<textarea name="alfonso_content" rows="8" cols="50" id="alfonso_content" class="large-text code" data-lpignore="true"><?php echo esc_textarea( $machete_alfonso_content ); ?></textarea>
	</fieldset>



<table class="form-table">
	<tbody>
	<tr>
	<th scope="row"><?php esc_html_e( 'Code injection method', 'machete' ); ?></th>
	<td><fieldset>

		<legend class="screen-reader-text"><span><?php esc_html_e( 'Custom body content injection method:', 'machete' ); ?></span></legend>

		<label><input name="alfonso_content_injection_method" value="wp_body_open" type="radio" <?php checked( 'wp_body_open', $this->settings['alfonso_content_injection_method'], true ); ?>> <?php echo wp_kses_data( __( 'Include the code using the native <code>wp_body_open</code> hook (not supported by all themes).', 'machete' ) ); ?></label><br>

		<?php // translators: %s url of article with explanation. ?>
		<label><input name="alfonso_content_injection_method" value="auto" type="radio" <?php checked( 'auto', $this->settings['alfonso_content_injection_method'], true ); ?>> <?php printf( wp_kses_data( __( 'Try to inject the code automatically using <a href="%s" target="_blank" rel="nofollow">Yaniv Friedensohn\'s method</a>', 'machete' ) ), 'https://machetewp.com/hack-placing-the-google-tag-manager-in-wordpress-after-the-body-tag/' ); ?></label><br>

		<label><input name="alfonso_content_injection_method" value="manual" type="radio" <?php checked( 'manual', $this->settings['alfonso_content_injection_method'], true ); ?>> <?php echo wp_kses_data( __( 'Edit your theme\'s <code>header.php</code> template manually and include this function:', 'machete' ) ); ?> <code>&lt;?php machete_custom_body_content() ?&gt;</code></label>
	</fieldset></td>
	</tr>
	</tbody>
	</table>

</div>


<div class="machete-tabs-content" id="machete-tabs-footer">

	<h2><?php esc_html_e( 'Custom footer content', 'machete' ); ?></h2>

	<fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Custom footer content', 'machete' ); ?></span></legend>
	<?php // translators: $s: file path. ?>
	<label for="footer_content"><p><?php printf( wp_kses_post( __( 'This code is included when the <code>wp_footer</code> action is called, normally just before the closing <code>&lt;/body&gt;</code> tag.<br>Content is saved to <code>%s</code> and served using PHP\'s <code>readfile()</code> function, so no PHP or shortcodes here.', 'machete' ) ), esc_html( MACHETE_RELATIVE_DATA_PATH . 'footer.html' ) ); ?></p></label>

	<textarea name="footer_content" rows="8" cols="50" id="footer_content" class="large-text code" data-lpignore="true"><?php echo esc_textarea( $machete_footer_content ); ?></textarea>
	</fieldset>

</div>

<?php submit_button(); ?>

</form>
</div>


<script>

MACHETE = window.MACHETE || {};

MACHETE.utils = (function($){

	return {
		isGA4: function(str){
			if (!str) return false;
			return (/^(g-[a-z0-9]{4,11})$/i).test(str.toString());			
		},
		isAnalytics: function(str){
			if (!str) return false;
			return (/^(ua-\d{4,11}-\d{1,4})$/i).test(str.toString());			
		},
		isGTM: function(str){
			if (!str) return false;
			return (/^(gtm-[a-z0-9]{4,11})$/i).test(str.toString());			
		}
	}

})(jQuery);

(function($){
	$('#mache-utils-options').submit(function( e ) {
		var tracking_format = $('input[name=tracking_format]:checked', '#mache-utils-options').val();

		console.log(tracking_format);

		if (tracking_format != 'none') {
			var tracking_ga4 = $('#tracking_ga4').val();
			if ( (!! tracking_ga4) && (!MACHETE.utils.isGA4(tracking_ga4)) ) {
				window.alert('<?php echo esc_js( __( 'That doesn\'t look like a valid Google Analytics 4 Property ID', 'machete' ) ); ?>');
				e.preventDefault();
				return;
			}

			var tracking_id = $('#tracking_id').val();
			if ( (!! tracking_id) && (!MACHETE.utils.isAnalytics(tracking_id)) ) {
				window.alert('<?php echo esc_js( __( 'That doesn\'t look like a valid Universal Analytics Property ID', 'machete' ) ); ?>');
				e.preventDefault();
				return;
			}

			var tracking_gtm = $('#tracking_gtm').val();
			if ( (!! tracking_gtm) && (!MACHETE.utils.isGTM(tracking_gtm)) ) {
				window.alert('<?php echo esc_js( __( 'That doesn\'t look like a valid Google Tag Manager container ID', 'machete' ) ); ?>');
				e.preventDefault();
				return;
			}

		}

	});
})(jQuery);

</script>
