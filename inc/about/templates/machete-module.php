<?php
/**
 * Machete module card template for the "About Machete" page.

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$machete_args['tab_url'] = add_query_arg( 'page', 'machete-' . $machete_slug, admin_url( 'admin.php' ) );
if ( ! array_key_exists( 'banner', $machete_args ) ) {
	$machete_args['banner'] = MACHETE_BASE_URL . 'inc/' . $machete_slug . '/banner.svg';
}
?>
<div class="machete-module-wrap"><div class="machete-module <?php echo esc_attr( $machete_slug . '-module' ); ?><?php echo ! empty( $machete_args['has_warning'] ) ? ' has-warning-module' : ''; ?> module-is-<?php echo $machete_args['is_active'] ? 'active' : 'inactive'; ?>">

<?php if ( $machete_args['is_active'] && $machete_args['has_config'] ) { ?>
	<a class="machete-module-image"
		href="<?php echo esc_url( $machete_args['tab_url'] ); ?>"
		title="<?php echo esc_attr( __( 'Configure', 'machete' ) . ' ' . $machete_args['full_title'] ); ?>">
		<img src="<?php echo esc_url( $machete_args['banner'] ); ?>">
	</a>
<?php } else { ?>
	<span class="machete-module-image" title="<?php echo esc_attr( $machete_args['full_title'] ); ?>">
		<img src="<?php echo esc_url( $machete_args['banner'] ); ?>">
	</span>
<?php } ?>

	<div class="machete-module-text">
		<?php if ( $machete_args['is_active'] && $machete_args['has_config'] ) { ?>
			<h3><a href="<?php echo esc_url( $machete_args['tab_url'] ); ?>"
		title="<?php echo esc_attr( __( 'Configure', 'machete' ) . ' ' . $machete_args['full_title'] ); ?>"><?php echo esc_html( $machete_args['full_title'] ); ?></a></h3>
		<?php } else { ?>
			<h3><?php echo esc_html( $machete_args['full_title'] ); ?></h3>
		<?php } ?>

		<div class="machete-module-description"><?php echo esc_html( $machete_args['description'] ); ?></div>

		<?php if ( $machete_args['is_active'] ) { ?>
		<div class="machete-module-active-indicator"><?php esc_html_e( 'Active', 'machete' ); ?></div>
		<?php } ?>
	</div>

	<div class="machete-module-bottom">
		<div class="machete-module-toggle-active">
		<?php

		$machete_action_url = wp_nonce_url(
			add_query_arg(
				array(
					'page'   => 'machete',
					'module' => $machete_slug,
				),
				admin_url( 'admin.php' )
			),
			'machete_action_' . $machete_slug
		);

		if ( $machete_args['is_active'] ) {
			?>

			<?php if ( $machete_args['can_be_disabled'] ) { ?>
			<a href="<?php echo esc_url( add_query_arg( 'machete-action', 'deactivate', $machete_action_url ) ); ?>" class="button-secondary" data-status="0"><?php esc_html_e( 'Deactivate', 'machete' ); ?></a>
			<?php } ?>

			<?php if ( $machete_args['has_config'] ) { ?>
			<a href="<?php echo esc_url( add_query_arg( 'page', 'machete-' . $machete_slug, admin_url( 'admin.php' ) ) ); ?>"
			title="<?php echo esc_attr( __( 'Configure', 'machete' ) . ' ' . $machete_args['full_title'] ); ?>" class="button-secondary" data-status="0"><?php esc_html_e( 'Settings', 'machete' ); ?></a>
			<?php } ?>

		<?php } else { ?>
			<?php if ( $machete_args['can_be_enabled'] ) { ?>
				<?php
				$machete_activate_url   = add_query_arg( 'machete-action', 'activate', $machete_action_url );
				$machete_activate_class = 'button-secondary';
				if ( ! empty( $machete_args['has_warning'] ) ) {
					$machete_activate_class .= ' machete-module-activate-warning';
				}
				?>
			<a href="<?php echo esc_url( $machete_activate_url ); ?>"
				class="<?php echo esc_attr( $machete_activate_class ); ?>"
				data-status="1"
				<?php if ( ! empty( $machete_args['has_warning'] ) ) { ?>
				data-warning-title="<?php echo esc_attr( wp_strip_all_tags( $machete_args['full_title'] ) ); ?>"
				data-warning-message="<?php echo esc_attr( $machete_args['activation_warning'] ); ?>"
				data-warning-icon-url="<?php echo esc_url( MACHETE_BASE_URL . 'inc/' . $machete_slug . '/icon.svg' ); ?>"
				<?php } ?>><?php esc_html_e( 'Activate', 'machete' ); ?></a>
			<?php } else { ?>
				<span class="button-secondary button-disabled"><?php esc_html_e( 'Activate', 'machete' ); ?></span>
			<?php } ?>

		<?php } ?>
		</div>
	</div>

</div></div>
