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
	<h1><?php $this->icon(); ?> <?php esc_html_e( 'Social Sharing Buttons', 'machete' ); ?></h1>
	<p class="tab-description"><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet placerat ultrices. Sed et euismod leo, nec vestibulum ex. Quisque aliquet fermentum volutpat. Ut felis lorem, rhoncus a erat id, luctus lobortis ipsum.', 'machete' ); ?></p>
	<?php $machete->admin_tabs( 'machete-social' ); ?>


	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php echo wp_kses( _e( 'This tool adds 4Kb and a single database request request to each page load.', 'machete' ), wp_kses_allowed_html() ); ?></span></p>

<form id="mache-social-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_social' ); ?>
<input type="hidden" name="machete-social-saved" value="true">

<table class="form-table">
<tbody><tr>

	<th scope="row"><?php esc_html_e( 'Sharing buttons status', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Sharing buttons status', 'machete' ); ?></span></legend>
		<label><input name="status" value="enabled" type="radio" <?php checked( 'enabled', $this->settings['status'], true ); ?>> <?php echo esc_html( _x( 'Enabled', 'plural', 'machete' ) ); ?></label><br>
		<label><input name="status" value="disabled" type="radio" <?php checked( 'disabled', $this->settings['status'], true ); ?>> <?php echo esc_html( _x( 'Disabled', 'plural', 'machete' ) ); ?></label><br>
	</fieldset></td>
</tr>




<tr>
	<th scope="row"><label for="sharing_title"><?php esc_html_e( 'Bottom buttons title', 'machete' ); ?></label></th>
	<td><input name="sharing_title" id="sharing_title" value="<?php echo esc_html( $this->settings['title'] ); ?>" class="regular-text ltr" type="text">
	<p class="description"><?php esc_html_e( 'The %%post_type%% placeholder is replaced by the post type name. ie: post, page', 'machete' ); ?></p>
	</td>
</tr>
<tr>
	<th scope="row"><?php esc_html_e( 'Active share buttons', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Active share buttons', 'machete' ); ?></span></legend>
	<?php foreach ( $this->networks as $network_slug => $network ) { ?>
		<label><input type="checkbox" name="networkEnabled[]" value="<?php echo esc_attr( $network_slug ); ?>" id="network_<?php echo esc_attr( $network_slug . '_fld' ); ?>" <?php checked( true, in_array( $network_slug, $this->settings['networks'], true ), true ); ?>> <?php echo esc_html( $network['title'] ); ?></label><br>
	<?php } ?>
	</fieldset></td>
</tr>


<tr>
	<th scope="row"><?php esc_html_e( 'Sharing buttons position', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Sharing buttons position', 'machete' ); ?></span></legend>
	<?php foreach ( $this->positions as $position_slug => $position ) { ?>
		<label><input type="checkbox" name="positionEnabled[]" value="<?php echo esc_attr( $network_slug ); ?>" id="position_<?php echo esc_attr( $network_slug . '_fld' ); ?>" <?php checked( true, in_array( $position_slug, $this->settings['positions'], true ), true ); ?>> <?php echo esc_html( $position ); ?></label><br>
	<?php } ?>
	</fieldset></td>
</tr>

<tr>
	<th scope="row"><?php esc_html_e( 'Show in these post types', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Show in these post types', 'machete' ); ?></span></legend>
	<?php foreach ( $this->valid_post_types as $post_type_slug => $post_type ) { ?>
		<label><input type="checkbox" name="postTypeEnabled[]" value="<?php echo esc_attr( $post_type_slug ); ?>" id="post_type_<?php echo esc_attr( $post_type_slug . '_fld' ); ?>" <?php checked( true, in_array( $network_slug, $this->settings['post_types'], true ), true ); ?>> <?php echo esc_html( $post_type ); ?></label><br>
	<?php } ?>
	</fieldset></td>
</tr>


</tbody></table>

<?php submit_button(); ?>
</form>


<div class="postbox machete-helpbox" data-collapsed="false" style="display: none;">
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

	foreach ( $this->themes as $slug => $params ) {
		$params['html'] = $this->get_contents( $params['template'] );

		$machete_cookie_replaces['{{extra_css}}'] = $this->themes[ $slug ]['extra_css'];

		$params['html'] = str_replace(
			array_keys( $machete_cookie_replaces ),
			array_values( $machete_cookie_replaces ),
			$params['html']
		);
		?>
		cookie_bar_templates['<?php echo esc_js( $slug ); ?>'] = '<?php echo wp_slash( $params['html'] ); // WPCS: XSS ok. ?>';

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


	$('#mache-social-options').submit(function( e ) {

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
		$( '#warning_text' ).val( '<?php echo $this->default_settings['warning_text']; // WPCS: XSS ok. ?>' );
		$( '#machete_cookie_preview_warning' ).html( '<?php echo $this->default_settings['warning_text']; // WPCS: XSS ok. ?>' );

		$( '#accept_text' ).val( '<?php echo $this->default_settings['accept_text']; // WPCS: XSS ok. ?>' );
		$( '#machete_cookie_preview_accept' ).html( '<?php echo $this->default_settings['accept_text']; // WPCS: XSS ok. ?>' );
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
