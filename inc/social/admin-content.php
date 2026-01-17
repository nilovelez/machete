<?php
/**
 * Content of the "Cookie Law" page.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap machete-wrap machete-section-wrap">
	<div class="wp-header-end"></div><!-- admin notices go after .wp-header-end or .wrap>h2:first-child -->
	<h1><?php $this->icon(); ?> <?php esc_html_e( 'Social Sharing Buttons', 'machete' ); ?></h1>
	<p class="tab-description"><?php esc_html_e( 'Social sharing done the Machete way. The icons are made as a custom webfont embedded in a minified CSS file that only weighs 5.8KB. The sharing actions use each platform\'s native share URL.', 'machete' ); ?></p>
	<?php $machete->admin_tabs( 'machete-social' ); ?>


	<p class="tab-performance"><span><strong><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Performance impact:', 'machete' ); ?></strong> <?php esc_html_e( 'This tool adds 6.1KB and a single database request to each page load.', 'machete' ); ?></span></p>

<form id="mache-social-options" action="" method="POST">

<?php wp_nonce_field( 'machete_save_social' ); ?>
<input type="hidden" name="machete-social-saved" value="true">

<table class="form-table">
<tbody><tr>

	<th scope="row"><?php esc_html_e( 'Sharing buttons status', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Sharing buttons status', 'machete' ); ?></span></legend>
		<label><input name="social_status" value="enabled" type="radio" <?php checked( 'enabled', $this->settings['status'], true ); ?>> <?php echo esc_html( _x( 'Enabled', 'plural', 'machete' ) ); ?></label><br>
		<label><input name="social_status" value="disabled" type="radio" <?php checked( 'disabled', $this->settings['status'], true ); ?>> <?php echo esc_html( _x( 'Disabled', 'plural', 'machete' ) ); ?></label><br>
	</fieldset></td>
</tr>




<tr>
	<th scope="row"><label for="social_title"><?php esc_html_e( 'Bottom buttons title', 'machete' ); ?></label></th>
	<td><input name="social_title" id="social_title" value="<?php echo esc_html( $this->settings['title'] ); ?>" class="regular-text ltr" type="text">
	<p class="description"><?php esc_html_e( 'Leave blank to remove. The %%post_type%% placeholder is replaced by the post type name. ie: post, page', 'machete' ); ?></p>
	</td>
</tr>
<tr>
	<th scope="row"><?php esc_html_e( 'Active share buttons', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Active share buttons', 'machete' ); ?></span></legend>
	<?php foreach ( $this->networks as $machete_network_slug => $machete_network ) { ?>
		<label><input type="checkbox" name="networkEnabled[]" value="<?php echo esc_attr( $machete_network_slug ); ?>" id="network_<?php echo esc_attr( $machete_network_slug . '_fld' ); ?>" <?php checked( true, in_array( $machete_network_slug, $this->settings['networks'], true ), true ); ?>> <?php echo esc_html( $machete_network['title'] ); ?></label><br>
	<?php } ?>
	</fieldset></td>
</tr>

<tr>
	<th scope="row"><?php esc_html_e( 'Sharing buttons position', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Sharing buttons position', 'machete' ); ?></span></legend>
	<?php foreach ( $this->positions as $machete_position_slug => $machete_position ) { ?>
		<label><input type="checkbox" name="positionEnabled[]" value="<?php echo esc_attr( $machete_position_slug ); ?>" id="position_<?php echo esc_attr( $machete_network_slug . '_fld' ); ?>" <?php checked( true, in_array( $machete_position_slug, $this->settings['positions'], true ), true ); ?>> <?php echo esc_html( $machete_position ); ?></label><br>
	<?php } ?>
	</fieldset></td>
</tr>

<tr>
	<th scope="row"><?php esc_html_e( 'Show in these post types', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Show in these post types', 'machete' ); ?></span></legend>
	<?php foreach ( $this->valid_post_types as $machete_post_type_slug => $machete_post_type ) { ?>
		<label><input type="checkbox" name="postTypeEnabled[]" value="<?php echo esc_attr( $machete_post_type_slug ); ?>" id="post_type_<?php echo esc_attr( $machete_post_type_slug . '_fld' ); ?>" <?php checked( true, in_array( $machete_post_type_slug, $this->settings['post_types'], true ), true ); ?>> <?php echo esc_html( $machete_post_type ); ?></label><br>
	<?php } ?>
	</fieldset></td>
</tr>
<tr>
	
	<th scope="row"><?php esc_html_e( 'Force styles in templates', 'machete' ); ?></th>
	<td><fieldset><legend class="screen-reader-text"><span><?php esc_html_e( 'Force styles in templates', 'machete' ); ?></span></legend>
		<label><input name="force_styles" value="enabled" type="checkbox" <?php checked( 'enabled', $this->settings['force_styles'], true ); ?>> <?php echo esc_html( _x( 'Make button styles load in active post types single post templates. Useful if you want to use the shortcode outside of the loop in the Site Editor.', 'plural', 'machete' ) ); ?></label><br>
		</fieldset></td>
</tr>

<tr>
	<th scope="row"><?php esc_html_e( 'Use with shortcode', 'machete' ); ?></th>
	<td><code>[mct-social-share]</code>
	<p class="description"><?php esc_html_e( 'You can use this shortcode in the content of any post, page or custom post type. It won\'t work on archive pages, sidebars...', 'machete' ); ?></p>
	</td>
</tr>

</tbody></table>

<?php submit_button(); ?>
</form>
</div>
