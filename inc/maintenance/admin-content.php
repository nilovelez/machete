<?php
/**
 * Content of the "Maintenance Mode" page.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
} ?>

<div class="wrap machete-wrap machete-section-wrap">
	<div class="wp-header-end"></div><!-- admin notices go after .wp-header-end or .wrap>h2:first-child -->
	<h1><?php $this->icon(); ?> <?php esc_html_e( 'Coming Soon & Maintenance Mode', 'machete' ); ?></h1>

	<p class="tab-description"><?php esc_html_e( 'If you have to close your website temporarily to the public, the native WordPress maintenance mode falls short and most coming soon plugins are bulky, incomplete or expensive. Machete maintenance mode is light, simple and versatile.', 'machete' ); ?></p>
	<?php $machete->admin_tabs( 'machete-maintenance' ); ?>
	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php esc_html_e( 'This section stores all its settings in a single autoloaded configuration variable.', 'machete' ); ?></span></p>


<form id="mache-maintenance-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_maintenance' ); ?>
<input type="hidden" name="machete-maintenance-saved" value="true">

	<table class="form-table">
		<tr>
		<th scope="row"><?php esc_html_e( 'Set site status', 'machete' ); ?></th>
		<td><fieldset>
			<label>
				<input name="site_status" value="online" type="radio"
				<?php checked( $this->settings['site_status'], 'online', true ); ?>>
				<?php echo wp_kses( __( '<strong>Online</strong> — WordPress works as usual', 'machete' ), array( 'strong' => array() ) ); ?>
			</label><br>

			<label>
				<input name="site_status" value="coming_soon" type="radio"
				<?php checked( $this->settings['site_status'], 'coming_soon' ); ?>>
				<?php echo wp_kses( __( '<strong>Coming soon</strong> — Site closed. All pages have a meta robots noindex, nofollow', 'machete' ), array( 'strong' => array() ) ); ?>
			</label><br>
			<label>
				<input name="site_status" value="maintenance" type="radio"
				<?php checked( $this->settings['site_status'], 'maintenance' ); ?>>
				<?php echo wp_kses( __( '<strong>Maintenance</strong> — Site closed. All pages return 503 Service unavailable', 'machete' ), array( 'strong' => array() ) ); ?>
			</label><br>
		</fieldset></td>
		</tr>

		<tr valign="top"><th scope="row"><?php esc_html_e( 'Magic Link', 'machete' ); ?></th>
			<td>
				<input type="hidden" name="token" id="token_fld" value="<?php echo esc_attr( $this->settings['token'] ); ?>">
				<a href="<?php echo esc_url( $this->magic_url ); ?>" id="machete_magic_link"><?php echo esc_url( $this->magic_url ); ?></a>
				<button name="change_token" id="change_token_btn" class="button action"><?php esc_html_e( 'change secret token', 'machete' ); ?></button>
		<p class="description"><?php esc_html_e( 'You can use this link to grant anyone access to the website when it is in maintenance mode.', 'machete' ); ?></p>

			</td>
		</tr>

		<tr valign="top"><th scope="row"><?php esc_html_e( 'Choose a page for the content', 'machete' ); ?></th>
			<td>
				<select name="page_id" id="page_id_fld">
					<option value=""><?php esc_html_e( 'Use default content', 'machete' ); ?></option>
					<?php
					$machete_pages = get_pages();
					foreach ( $machete_pages as $machete_page ) {
						echo '<option value="' . esc_attr( $machete_page->ID ) . '" ' .
						selected( $machete_page->ID, $this->settings['page_id'] ) . '>' .
						esc_html( $machete_page->post_title ) .
						'</option>';
					}
					?>
				</select>

				<a href="<?php echo esc_url( $this->preview_url ); ?>" target="machete_preview" id="preview_maintenance_btn" class="button action"><?php esc_html_e( 'Preview', 'machete' ); ?></a>
			</td>
		</tr>

	</table>

<?php submit_button(); ?>

</form>


<div class="postbox machete-helpbox" data-collapsed="false">
<button type="button" class="handlediv button-link"><span class="toggle-indicator" aria-hidden="true"><span class="dashicons dashicons-arrow-up"></span></span></button>
	<h3 class="hndle"><span><span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'How do I customize the maintenance page?', 'machete' ); ?></span></h2>
	<div class="inside">
		<table class="form-table"><tbody><tr valign="top"><th scope="row"></th><td>
			<?php // translators: %s: Analytics & code tab link. ?>
			<p class="description"><?php echo sprintf( esc_html( __( 'You can customize the maintenance page CSS using the %sAnalytics & Code tab', 'machete' ) ), '<a href="' . esc_url( admin_url( 'admin.php?page=machete-utils' ) ) . '">' ); ?></a></p>
			<p class="description"><?php esc_html_e( 'For your reference, this is the HTML used to render the Maintenance page:', 'machete' ); ?></p>
			<pre style="color: #00f; font-weight: bold;">&lt;html&gt;
&nbsp;&nbsp;&lt;head&gt;
&nbsp;&nbsp;&nbsp;&nbsp;&lt;title&gt;<span style="color: #000;">[<?php esc_html_e( 'title of the selected page', 'machete' ); ?>]</span>&lt;/title&gt;
&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #000;">[&hellip;]</span>
&nbsp;&nbsp;&lt;/head&gt;
&nbsp;&nbsp;&lt;body <span style="color: #c00;">id</span>=<span style="color: #f0f;">"maintenance_page"</span>&gt;
&nbsp;&nbsp;&nbsp;&nbsp;&lt;div <span style="color: #c00;">id</span>=<span style="color: #f0f;">"content"</span>&gt;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #000;">[<?php esc_html_e( 'content of the selected page', 'machete' ); ?>]</span>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;
&nbsp;&nbsp;&lt;/body&gt;
&lt;/html&gt;</pre>
		</td></tr></tbody></table>
	</div>
</div>


</div>


<script>

(function($){

	var machete_preview_base_url = '<?php echo esc_url( $this->preview_base_url ); ?>';
	var machete_magic_base_url   = '<?php echo esc_url( $this->magic_base_url ); ?>';

	var random_token = function(){
		var chrs = '0123456789ABCDEF';
		var token = '';
		for (var i = 0, n = 12; i < n; i++){
			token += chrs.substr(Math.round(Math.random()*15),1);
		}
		return token;
	}

	$('#change_token_btn').click(function(e){

		if (confirm('<?php esc_html_e( 'Are you sure you want to change the secret token?\nThis will invalidate previously-shared links.', 'machete' ); ?>')){
			var new_token = random_token();
			var new_magic_url = machete_magic_base_url + new_token;

			$('#machete_magic_link').attr('href',new_magic_url).html(new_magic_url);
			$('#token_fld').val(new_token);
		}
		e.preventDefault();
		return;
	});

	$('#page_id_fld').change(function(e){
		var content_page_id = '';
		if (content_page_id = $('#page_id_fld option:selected').val()){

			$('#preview_maintenance_btn').attr('href',machete_preview_base_url+'&mct_page_id='+content_page_id);
			console.log(content_page_id);
		}else{
			$('#preview_maintenance_btn').attr('href', machete_preview_base_url);
			console.log('vacío');
		}

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


	$('#mache-maintenance-options').submit(function( e ) {

	});
})(jQuery);
</script>
