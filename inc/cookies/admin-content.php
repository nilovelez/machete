<?php
/**
 * Content of the "Cookie Law" page.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
}
?>

<div class="wrap machete-wrap machete-section-wrap">
	<div class="wp-header-end"></div><!-- admin notices go after .wp-header-end or .wrap>h2:first-child -->
	<h1><?php $this->icon(); ?> <?php esc_html_e( 'Cookie Law Warning', 'machete' ); ?></h1>
	<p class="tab-description"><?php esc_html_e( 'We know you hate cookie warning bars. Well, this is the less hateable cookie bar you\'ll find. It is really light, it won\'t affect your PageSpeed score and plays well with static cache plugins.', 'machete' ); ?></p>
	<?php $machete->admin_tabs( 'machete-cookies' ); ?>


	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php echo wp_kses( _e( 'This tool adds 0,4Kb and a single database request request to each page load. The remaining <abbr title="~1.1Kb if GZipped">~2.5Kb</abbr> of code is loaded asynchronously via javascript from a pregenerated static file.', 'machete' ), wp_kses_allowed_html() ); ?></span></p>

<form id="mache-cookies-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_cookies' ); ?>
<input type="hidden" name="machete-cookies-saved" value="true">

<table class="form-table">
<tbody><tr>

	<th scope="row"><?php esc_html_e( 'Cookie alert status', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Cookie alert status', 'machete' ); ?></span></legend>
		<label><input name="bar_status" value="enabled" type="radio" <?php checked( 'enabled', $this->settings['bar_status'], true ); ?>> <?php esc_html_e( 'Enabled', 'machete' ); ?></label><br>
		<label><input name="bar_status" value="disabled" type="radio" <?php checked( 'disabled', $this->settings['bar_status'], true ); ?>> <?php esc_html_e( 'Disabled', 'machete' ); ?></label><br>
	</fieldset></td>
</tr><tr>
	<th scope="row"><label for="warning_text"><?php esc_html_e( 'Cookie warning text', 'machete' ); ?></label></th>
	<td><textarea name="warning_text" rows="3" cols="50" id="warning_text" class="large-text code"><?php echo esc_textarea( stripslashes( $this->settings['warning_text'] ) ); ?></textarea>
	<p class="description" style="text-align: right;"><a id="restore_cookie_text_btn"><?php esc_html_e( 'Restore default warning text', 'machete' ); ?></a></p>
	</fieldset></td>
</tr><tr>
	<th scope="row"><label for="accept_text"><?php esc_html_e( 'Accept button text', 'machete' ); ?></label></th>
	<td><input name="accept_text" id="accept_text" value="<?php echo esc_html( $this->settings['accept_text'] ); ?>" class="regular-text ltr" type="text"></td>
</tr><tr>
	<th scope="row"><?php esc_html_e( 'Cookie bar theme', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Cookie bar theme', 'machete' ); ?></span></legend>

	<?php foreach ( $this->themes as $machete_bar_theme_slug => $machete_bar_theme ) { ?>
		<label><input type="radio" name="bar_theme" class="bar-theme-radio" value="<?php echo esc_attr( $machete_bar_theme_slug ); ?>" <?php checked( $machete_bar_theme_slug, $this->settings['bar_theme'], true ); ?>> <?php echo esc_html( $machete_bar_theme['name'] ); ?></label><br>
	<?php } ?>
	</fieldset></td>
</tr>
</tbody></table>

<?php submit_button(); ?>
</form>


<div class="postbox machete-helpbox" data-collapsed="false">
<button type="button" class="handlediv button-link"><span class="toggle-indicator" aria-hidden="true"><span class="dashicons dashicons-arrow-up"></span></span></button>
	<h3 class="hndle"><span><span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'How do I customize the cookie bar?', 'machete' ); ?></span></h2>
	<div class="inside">
		<table class="form-table"><tbody><tr valign="top"><th scope="row"></th><td>
			<?php /* Translators: $s: a href part of a link */ ?>
			<p class="description"><?php echo sprintf( esc_html( __( 'You can customize the cookie bar CSS using the %sAnalytics & Code tab', 'machete' ) ), '<a href="' . esc_url( add_query_arg( array( 'page' => 'machete-utils' ), admin_url( 'admin.php' ) ) ) . '">' ); ?></a></p>
			<p class="description"><?php esc_html_e( 'For your reference, this is the HTML used to render the cookie bar:', 'machete' ); ?></p>
			<pre style="color: #00f; font-weight: bold;">&lt;div <span style="color: #c00;">id</span>=<span style="color: #f0f;">"machete_cookie_container"</span> <span style="color: #c00;">class</span>=<span style="color: #f0f;">"machete_cookie_container"</span>&gt;
&nbsp;&nbsp;&lt;div <span style="color: #c00;">id</span>=<span style="color: #f0f;">"machete_cookie_bar"</span> <span style="color: #c00;">class</span>=<span style="color: #f0f;">"machete_cookie_bar"</span>&gt;
&nbsp;&nbsp;&nbsp;&nbsp;&lt;a <span style="color: #c00;">id</span>=<span style="color: #f0f;">"machete_accept_cookie_btn"</span> <span style="color: #c00;">class</span>=<span style="color: #f0f;">"machete_accept_cookie_btn"</span>&gt;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #000;">[<?php esc_html_e( 'Accept button text', 'machete' ); ?>]</span>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/a&gt;
&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #000;">[<?php esc_html_e( 'Cookie warning text', 'machete' ); ?>]</span>
&nbsp;&nbsp;&lt;/div&gt;
&lt;/div&gt;</pre>
		</td></tr></tbody></table> 
	</div>
</div>

</div>


<script>
(function($){

	var cookie_bar_templates = [];
	<?php
	$machete_cookie_replaces = array(
		'{{accept_text}}'  => '<span id="machete_cookie_preview_accept"></span>',
		'{{warning_text}}' => '<span id="machete_cookie_preview_warning"></span>',
	);

	foreach ( $this->themes as $machete_theme_slug => $machete_params ) {
		$machete_params['html'] = $this->get_contents( $machete_params['template'] );

		$machete_cookie_replaces['{{extra_css}}'] = $this->themes[ $machete_theme_slug ]['extra_css'];

		$machete_params['html'] = str_replace(
			array_keys( $machete_cookie_replaces ),
			array_values( $machete_cookie_replaces ),
			$machete_params['html']
		);
		?>
		cookie_bar_templates['<?php echo esc_js( $machete_theme_slug ); ?>'] = '<?php echo wp_slash( $machete_params['html'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';

	<?php } ?>

	$(".bar-theme-radio").change( function(){
		render_bar_preview($(this).val());
	});

	var render_bar_preview = function( theme ) {
		if ( ! theme ) return;
		$( '#machete_cookie_container' ).html( cookie_bar_templates[theme] );
		update_preview_text();
	}

	var update_preview_text = function(){
		$( '#machete_cookie_preview_warning' ).html( $( '#warning_text' ).val() );
		$( '#machete_cookie_preview_accept' ).html( $( '#accept_text' ).val() );
	}

	$( "#warning_text" ).on( 'input', function() { update_preview_text(); });
	$( "#accept_text" ).on( 'input', function() { update_preview_text(); });


	var container       = document.createElement( 'div' );
	container.id        = 'machete_cookie_container';
	container.className = 'machete_cookie_container';
	container.innerHTML = cookie_bar_templates['<?php echo esc_js( $this->settings['bar_theme'] ); ?>'];

	Object.assign( container.style, {
		position: 'fixed',
		zIndex: 99999,
		bottom: 0,
		width: '100%'
	});
	var body = document.getElementsByTagName('body')[0];
	body.appendChild(container);
	update_preview_text();


	$('#mache-cookies-options').submit(function( e ) {

		if ( $( '#warning_text' ).val() == '' ) {
			window.alert('<?php echo esc_js( __( 'Cookie warning text can\'t be blank', 'machete' ) ); ?>');
			e.preventDefault();
			return;
		}
		if ( $( '#accept_text' ).val() == '' ) {
			window.alert('<?php echo esc_js( __( 'Accept button text can\'t be blank', 'machete' ) ); ?>');
			e.preventDefault();
			return;
		}
	});
	$( '#restore_cookie_text_btn' ).click( function() {
		$( '#warning_text' ).val( '<?php echo $this->default_settings['warning_text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>' );
		$( '#machete_cookie_preview_warning' ).html( '<?php echo $this->default_settings['warning_text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>' );

		$( '#accept_text' ).val( '<?php echo $this->default_settings['accept_text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>' );
		$( '#machete_cookie_preview_accept' ).html( '<?php echo $this->default_settings['accept_text']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>' );
	});


	$( 'button.handlediv' ).click( function( e ) {
		$( this ).parent().find( '.hndle' ).click();
	});

	$( '.machete-helpbox .hndle' ).click( function( e ) {
		var container  = $( this ).parent();

		if ( container.attr('data-collapsed') == 'false' ){
			close_helpbox( container );
		} else {
			open_helpbox( container );
		}
	});

	var close_helpbox  = function(elem){
		elem.attr( 'data-collapsed', 'true' );
		elem.find( 'div.inside' ).hide();
		elem.find( '.toggle-indicator span' ).attr( 'class', 'dashicons dashicons-arrow-down' );
	}
	var open_helpbox  = function(elem){
		elem.attr( 'data-collapsed', 'false' );
		elem.find( 'div.inside' ).show();
		elem.find( '.toggle-indicator span' ).attr( 'class', 'dashicons dashicons-arrow-up' );
	}

	$( '.machete-helpbox' ).each( function (index) {
		close_helpbox( $( this ) );
	} );
})(jQuery);
</script>
