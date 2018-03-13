<?php
/**
 * Content of the "About Machete" page.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
} ?>

<div class="wrap about-wrap machete-wrap full-width-layout">
		<h1><?php esc_html_e( 'Welcome to Machete!', 'machete' ); ?></h1>

		<div class="about-text"><?php esc_html_e( 'Machete is now installed and ready to use. Machete solves common WordPress problems using as little resources as posible. Machete lets you use less plugins. Machete optimizes.', 'machete' ); ?></div>
		<div class="machete-logo"><img src="<?php echo esc_url( MACHETE_BASE_URL . 'img/logo_machete.png' ); ?>"></div>

		<?php $machete->admin_tabs(); ?>

		<div class="feature-section">
		<div class="machete-important-notice  machete-warning">
			<p class="about-description"><?php esc_html_e( 'Machete is not meant to be safe. Machete is a suite of tools that can break things if not handled with care. As a rule of thumb, don\'t use any option you don\'t understand.', 'machete' ); ?></p>
		</div>

		<div class="machete-module-list">
		<?php
		foreach ( $machete->modules as $module ) {
			$args = $module->params;
			$slug = $args['slug'];

			if ( 'about' === $slug ) {
				continue;
			}
			if ( in_array( $slug, array( 'about', 'powertools' ), true ) || $args['is_external'] ) {
				continue;
			}
			include plugin_dir_path( __FILE__ ) . 'templates/machete-module.php';
		}
		?>
		</div>

		<h2><?php esc_html_e( 'External Modules', 'machete' ); ?></h2>

		<div class="machete-module-list">
		<?php
		foreach ( $machete->modules as $module ) {
			$args = $module->params;
			$slug = $args['slug'];

			if ( 'about' === $slug ) {
				continue;
			}
			if ( ( 'powertools' !== $slug ) && ( ! $args['is_external'] ) ) {
				continue;
			}
			include plugin_dir_path( __FILE__ ) . 'templates/machete-module.php';
		}
		?>
		</div>



	</div>	
	<!--<p class="description"></p>-->
</div>
