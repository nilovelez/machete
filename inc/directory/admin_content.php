<?php if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit; ?>

<div class="wrap machete-wrap machete-section-wrap">

		<h1><?php _e('Resource Directory','machete') ?></h1>
		<p class="tab-description"><?php _e('WordPress has a los of code just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer','machete') ?></p>
		<?php machete_admin_tabs('machete-directory'); ?>
			
<?php

$machete_resource_array = array(
	array(
		'title' => 'BlackBox Debug Bar',
		'href' => __('https://www.wordpress.org/plugins/','machete').'blackbox-debug-bar/',
		'href_title' => __('WordPress.org Plugin Directory','machete'),
		'description' => __('BlackBox is a plugin for plugin and theme developers. It collects and displays useful debug information (errors, executed queries, globals, profiler).','machete')
	),
	array(
		'title' => 'Open Graph',
		'href' => __('https://www.wordpress.org/plugins/','machete').'opengraph/',
		'href_title' => __('WordPress.org Plugin Directory','machete'),
		'description' => __('Adds Open Graph metadata to your posts and pages so that they look great when shared on sites like Facebook and Google+.','machete')
	),
	
	
);

?>
	<!--<p class="card ">Your Purchase Must Be Registered To Receive Theme Support & Auto Updates</p>-->

	<div class="feature-section">

		
		<table class="wp-list-table widefat fixed striped posts machete-options-table machete-cleanup-table">
		<thead>
			<tr>
				<th class="column-title"><?php _e('Resource','machete') ?></th>
				<th class="column-title"><?php _e('Location','machete') ?></th>
				<th><?php _e('Description','machete') ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($machete_resource_array as $resource){ ?>
			<tr>
				<td class="column-title column-primary"><strong><?php echo $resource['title'] ?></strong></td>
				<td><a href="<?php echo $resource['href'] ?>"><?php echo $resource['href_title'] ?></a></td>
				<td><?php echo $resource['description'] ?></td>
			</tr>

		<?php } ?>

		</tbody>
		</table>

		

	</div>
			
  </div>