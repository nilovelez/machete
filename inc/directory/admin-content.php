<?php
/**
 * Content of the "Resource directory" page.

 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) {
	exit;
} ?>

<div class="wrap machete-wrap machete-section-wrap">

<h1><?php esc_html_e( 'Resource Directory', 'machete' ); ?></h1>
<p class="tab-description"><?php esc_html_e( 'WordPress has a los of code just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer', 'machete' ); ?></p>
<?php machete_admin_tabs( 'machete-directory' ); ?>

<?php

$machete_resource_array = array(
	array(
		'title'       => 'BlackBox Debug Bar',
		'href'        => __( 'https://www.wordpress.org/plugins/', 'machete' ) . 'blackbox-debug-bar/',
		'href_title'  => __( 'WordPress.org Plugin Directory', 'machete' ),
		'description' => __( 'BlackBox is a plugin for plugin and theme developers. It collects and displays useful debug information (errors, executed queries, globals, profiler).', 'machete' ),
	),
	array(
		'title'       => 'Open Graph',
		'href'        => __( 'https://www.wordpress.org/plugins/', 'machete' ) . 'opengraph/',
		'href_title'  => __( 'WordPress.org Plugin Directory', 'machete' ),
		'description' => __( 'Adds Open Graph metadata to your posts and pages so that they look great when shared on sites like Facebook and Google+.', 'machete' ),
	),
);

?>
	<!--<p class="card ">Your Purchase Must Be Registered To Receive Theme Support & Auto Updates</p>-->

	<div class="feature-section">	
		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-cleanup-table">
		<thead>
			<tr>
				<th class="column-title"><?php esc_html_e( 'Resource', 'machete' ); ?></th>
				<th class="column-title"><?php esc_html_e( 'Location', 'machete' ); ?></th>
				<th><?php esc_html_e( 'Description', 'machete' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $machete_resource_array as $resource ) { ?>
			<tr>
				<td class="column-title column-primary"><strong><?php echo esc_html( $resource['title'] ); ?></strong></td>
				<td><a href="<?php echo esc_html( $resource['href'] ); ?>"><?php echo esc_html( $resource['href_title'] ); ?></a></td>
				<td><?php echo esc_html( $resource['description'] ); ?></td>
			</tr>

		<?php } ?>

		</tbody>
		</table>
	</div>
</div>
