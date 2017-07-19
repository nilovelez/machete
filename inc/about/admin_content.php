<?php if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit; ?>

	<div class="wrap about-wrap machete-wrap">
			<h1><?php _e('Welcome to Machete!','machete') ?></h1>

				<div class="about-text"><?php _e('Machete is now installed and ready to use. Machete solves common WordPress problems using as little resources as posible. Machete lets you use less plugins. Machete optimizes.','machete') ?></div>
		<div class="machete-logo"><img src="<?php echo MACHETE_BASE_URL ?>img/logo_machete.png"></div>
		<?php machete_admin_tabs('machete'); ?>
		
		<div class="feature-section">
		<div class="machete-important-notice  machete-warning">
			<p class="about-description"><?php _e('Machete is not meant to be safe. Machete is a suite of tools that can break things if not handled with care. As a rule of thumb, don\'t use any option you don\'t understand.','machete') ?></p>
		</div>


<style>

   .about-wrap .feature-section {overflow: visible;}

	#machete-module-list {
		zoom: 1;
		margin: 0 -12px;
	}

	#machete-module-list:before {
	    content: '';
	    display: block;
	}
	#machete-module-list:after {
	    content: '';
	    display: table;
	    clear: both;
	}

	#machete-module-list .machete-module-wrap {
	    float: left;
	    -ms-box-sizing: border-box;
	    -moz-box-sizing: border-box;
	    -webkit-box-sizing: border-box;
	    box-sizing: border-box;
	    padding: 0 12px 24px 12px;
	    width: 50%;
	}

	#machete-module-list .machete-module-wrap:nth-child(odd) {
		clear: left;
	}


	@media (max-width: 1023px){
		#machete-module-list .machete-module-wrap {
			width: 100%;
		}
	}

	#machete-module-list .machete-module {
	    border: 1px solid #D9D9D9;
	    float: left;
	    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
	    -moz-box-shadow: 0 1px 2px rgba(0,0,0,0.05);
	    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
	    padding: 0;
	    width: 100%;
	    background: #fefefe;
	    position: relative;
	    overflow: hidden;
	}
	#machete-module-list .machete-module .machete-module-image {
	    width: 128px;
	    height: 128px;
	    float: left;
	    margin: 20px;
	    
	}
	#machete-module-list .machete-module .machete-module-image img,
	#machete-module-list .machete-module .machete-module-image svg {
		width: 128px;
    	height: auto;
	}

	#machete-module-list .machete-module.module-is-inactive .machete-module-image svg,
	#machete-module-list .machete-module.module-is-inactive .machete-module-image img {
	    filter: url(filters.svg#grayscale);
	    filter: gray;
	    -webkit-filter: grayscale(1);
	    opacity: 0.7;
	}

	#machete-module-list .machete-module .machete-module-text {
	    padding: 20px 20px 20px 0;
    	margin-left: 168px;
	}
	#machete-module-list .machete-module h3 {
	    color: #0073aa;
	    font-size: 1.4em;
	    font-weight: 500;
	    margin-top: 0;
	    margin-right: 10px;
	}
	#machete-module-list .machete-module.module-is-inactive h3 {
	    color: #666;
	}

	#machete-module-list .machete-module .machete-module-description {
	    margin: 0;
	    line-height: 1.35em;
	    color: #777777;
	}

	#machete-module-list .machete-module .machete-module-active-indicator {
	    background: #ff9900;
	    padding: 4px 10px;
	    color: #fff;
	    display: inline-block;
	    width: 200px;
	    text-align: center;
	    position: absolute;
	    top: 20px;
	    right: -80px;
	    transform: rotate(45deg);
	}

	#machete-module-list .machete-module .machete-module-bottom {
		background-color: #fafafa;
		border-top: 1px solid #D9D9D9;
	    padding: 20px;
	    clear: left;	
	}


	#machete-module-list .machete-module .machete-module-toggle-active {
	    display: inline-block;
	}

	#machete-module-list .machete-module.module-is-active .machete-module-toggle-active .machete-module-activate {
	    display: none;
	}

	#machete-module-list .machete-module.module-is-inactive .machete-module-toggle-active .machete-module-deactivate {
	    display: none;
	}

</style>

	<?php 


	$module_info = array(
		'cleanup' => array(
			'full_title' => __('WordPress Optimization','machete'),
			'description' => __('Reduces much of the legacy code bloat in WordPress page headers. It also has some tweaks to make you site faster and safer.','machete'),
			'is_active' => true,
			'role' => 'admin'
		),
		'cookies' => array(
			'full_title' => __('Cookie Law Warning','machete'),
			'description' => __('Light and responsive cookie law warning bar that won\'t affect your PageSpeed score and plays well with static cache plugins.','machete'),
			'is_active' => true,
			'role' => 'author'
		),
		'utils' => array(
			'full_title' => __('Analytics and Custom Code','machete'),
			'description' => __('Google Analytics tracking code manager and a simple editor to insert HTML, CSS and JS snippets or site verification tags.'),
			'is_active' => true,
			'role' => 'admin'
		),
		'maintenance' => array(
			'full_title' => __('Maintenance Mode','machete'),
			'description' => __('Customizable maintenance page to close your site during updates or development. It has a "magic link" to grant temporary access.','machete'),
			'is_active' => true,
			'role' => 'admin'
		),
		'clone' => array(
			'full_title' => __('Post & Page Cloner','machete'),
			'description' => __('Customizable maintenance page to close your site during updates or development. It has a "magic link" to grant temporary access.','machete'),
			'is_active' => false,
			'role' => 'admin'
		),
		'powertools' => array(
			'full_title' => __('Machete PowerTools','machete'),
			'description' => __('','machete'),
			'is_active' => false,
			'role' => 'admin'
		)
	);

	if (defined ('MACHETE_POWERTOOLS_INIT') ) {
		$module_info['powertools']['is_active'] = true;

	}



	?>

		<div id="machete-module-list">
		<?php foreach ($module_info as $slug => $args){ ?>
			<div class="machete-module-wrap"><div class="machete-module module-is-<?php echo $args['is_active'] ? 'active' : 'inactive' ?>">
				
				<a class="machete-module-image"
					href="<?php echo admin_url('admin.php?page=machete-'.$slug) ?>"
					title="<?php echo __('Configure','machete').' '.$args['full_title'] ?>">
					<img src="<?php echo MACHETE_BASE_URL ?>inc/<?php echo $slug ?>/banner.svg">
				</a>
				<div class="machete-module-text">
					<!--<div class="machete-module-active-indicator">Activo</div>-->
					<h3><?php echo $args['full_title'] ?></h3>
					<div class="machete-module-description">
						<?php echo $args['description'] ?>
					</div>
					<?php if ($args['is_active']){ ?><div class="machete-module-active-indicator"><?php _e('Active','machete') ?></div><?php } ?>

				</div>
				<div class="machete-module-bottom">
					<div class="machete-module-toggle-active">
						<button class="button-secondary machete-module-activate" data-status="1">Activar</button>
						<button class="button-secondary machete-module-deactivate" data-status="0">Desactivar</button>
					</div>

					

				</div>


			</div></div>

		<?php } ?>

		</div>
	</div>	
			


	<!--<p class="description"></p>-->
	
</div>