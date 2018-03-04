<?php
/**
 * Machete module card template for the "About Machete" page.

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args['tab_url'] = add_query_arg( 'page', 'machete-' . $slug, admin_url( 'admin.php' ) );
$args['img_url'] = MACHETE_BASE_URL . 'inc/' . $slug . '/banner.svg';
?>
<div class="machete-module-wrap"><div class="machete-module <?php echo esc_attr( $slug . '-module' ); ?> module-is-<?php echo $args['is_active'] ? 'active' : 'inactive'; ?>">

<?php if ( $args['is_active'] && $args['has_config'] ) { ?>
	<a class="machete-module-image"
		href="<?php echo esc_url( $args['tab_url'] ); ?>"
		title="<?php echo esc_attr( __( 'Configure', 'machete' ) . ' ' . $args['full_title'] ); ?>">
		<img src="<?php echo esc_url( $args['img_url'] ); ?>">
	</a>
<?php } else { ?>
	<span class="machete-module-image" title="<?php echo esc_attr( $args['full_title'] ); ?>">
		<img src="<?php echo esc_url( $args['img_url'] ); ?>">
	</span>
<?php } ?>

	<div class="machete-module-text">
		<?php if ( $args['has_config'] ) { ?>
			<h3><a href="<?php echo esc_url( $args['tab_url'] ); ?>"
		title="<?php echo esc_attr( __( 'Configure', 'machete' ) . ' ' . $args['full_title'] ); ?>"><?php echo esc_html( $args['full_title'] ); ?></a></h3>
		<?php } else { ?>
			<h3><?php echo esc_html( $args['full_title'] ); ?></h3>
		<?php } ?>

		<div class="machete-module-description"><?php echo esc_html( $args['description'] ); ?></div>

		<?php if ( $args['is_active'] ) { ?>
		<div class="machete-module-active-indicator"><?php esc_html_e( 'Active', 'machete' ); ?></div>
		<?php } ?>
	</div>

	<div class="machete-module-bottom">
		<div class="machete-module-toggle-active">
		<?php

		$action_url = wp_nonce_url( add_query_arg( array(
			'page'   => 'machete',
			'module' => $slug,
		), admin_url( 'admin.php' ) ), 'machete_action_' . $slug );

		if ( 'powertools' === $slug ) {

			$machete_powertools_path = 'machete-powertools/machete-powertools.php';

			if ( $args['is_active'] ) {

				$machete_powertools_deactivation_url = wp_nonce_url( add_query_arg( array(
					'action' => 'deactivate',
					'plugin' => $machete_powertools_path,
				), admin_url( 'plugins.php' ) ), 'deactivate-plugin_' . $machete_powertools_path );
				?>

				<a href="<?php echo esc_url( $machete_powertools_deactivation_url ); ?>" class="button-secondary" data-status="1"><?php esc_html_e( 'Deactivate Plugin', 'machete' ); ?></a>

				<a href="<?php echo esc_url( add_query_arg( 'page', 'machete-' . $slug, admin_url( 'admin.php' ) ) ); ?>"
				title="<?php echo esc_attr( __( 'Configure', 'machete' ) . ' ' . $args['full_title'] ); ?>" class="button-secondary" data-status="0"><?php esc_html_e( 'Settings', 'machete' ); ?></a>

			<?php
			} elseif ( file_exists( WP_PLUGIN_DIR . '/' . $machete_powertools_path ) ) {

				$machete_powertools_activation_url = wp_nonce_url( add_query_arg( array(
					'action' => 'activate',
					'plugin' => $machete_powertools_path,
				), admin_url( 'plugins.php' ) ), 'activate-plugin_' . $machete_powertools_path );
				?>

				<a href="<?php echo esc_url( $machete_powertools_activation_url ); ?>" class="button-primary" data-status="1"><?php esc_html_e( 'Activate Plugin', 'machete' ); ?></a>

			<?php } else { ?>

				<a href="https://machetewp.com/powertools/" class="button-primary" data-status="1"><?php esc_html_e( 'Download Machete PowerTools', 'machete' ); ?></a>

			<?php } ?>


		<?php } elseif ( $args['is_active'] ) { ?>

			<?php if ( $args['can_be_disabled'] ) { ?>
			<a href="<?php echo esc_url( add_query_arg( 'machete-action', 'deactivate', $action_url ) ); ?>" class="button-secondary" data-status="0"><?php esc_html_e( 'Deactivate', 'machete' ); ?></a>
			<?php } ?>

			<?php if ( $args['has_config'] ) { ?>
			<a href="<?php echo esc_url( add_query_arg( 'page', 'machete-' . $slug, admin_url( 'admin.php' ) ) ); ?>"
			title="<?php echo esc_attr( __( 'Configure', 'machete' ) . ' ' . $args['full_title'] ); ?>" class="button-secondary" data-status="0"><?php esc_html_e( 'Settings', 'machete' ); ?></a>
			<?php } ?>

		<?php } else { ?>

			<a href="<?php echo esc_url( add_query_arg( 'machete-action', 'activate', $action_url ) ); ?>" class="button-secondary" data-status="1"><?php esc_html_e( 'Activate', 'machete' ); ?></a>

		<?php } ?>
		</div>
	</div>

</div></div>
