<?php
/**
 * Content of the "About Machete" page.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wrap about-wrap machete-wrap full-width-layout">
		<div class="wp-header-end"></div><!-- admin notices go after .wp-header-end or .wrap>h2:first-child -->
		<div class="machete-logo"><img src="<?php echo esc_url( MACHETE_BASE_URL . 'img/logo_machete.png' ); ?>"></div>
		<h1><?php esc_html_e( 'Welcome to Machete!', 'machete' ); ?></h1>

		<div class="about-text"><?php esc_html_e( 'Machete is now installed and ready to use. Machete solves common WordPress problems using as little resources as possible. Machete lets you use less plugins. Machete optimizes.', 'machete' ); ?></div>

		<?php $machete->admin_tabs(); ?>

		<div class="feature-section">
		<div class="machete-important-notice  machete-warning">
			<p class="about-description"><?php esc_html_e( 'Machete is not meant to be safe. Machete is a suite of tools that can break things if not handled with care. As a rule of thumb, don\'t use any option you don\'t understand.', 'machete' ); ?></p>
		</div>

		<div class="machete-module-list">
		<?php
		foreach ( $machete->modules as $machete_module ) {
			$machete_args = $machete_module->params;
			$machete_slug = $machete_args['slug'];

			if ( 'about' === $machete_slug ) {
				continue;
			}
			if (
				in_array( $machete_slug, array( 'about', 'powertools' ), true ) ||
				$machete_args['is_external']
			) {
				continue;
			}
			include plugin_dir_path( __FILE__ ) . 'templates/machete-module.php';
		}
		?>
		</div>

		<h2><?php esc_html_e( 'External Modules', 'machete' ); ?></h2>

		<div class="machete-module-list">
		<?php
		foreach ( $machete->modules as $machete_module ) {
			$machete_args = $machete_module->params;
			$machete_slug = $machete_args['slug'];

			if ( 'about' === $machete_slug ) {
				continue;
			}
			if ( ( 'powertools' !== $machete_slug ) && ( ! $machete_args['is_external'] ) ) {
				continue;
			}
			include plugin_dir_path( __FILE__ ) . 'templates/machete-module.php';
		}
		?>
		</div>



	</div>
	<!--<p class="description"></p>-->
</div>
