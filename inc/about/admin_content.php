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
	    float: right;
	    margin: 0 0 10px 10px;
	    background: #00a0d2;
	    padding: 4px 10px;
	    color: #fff;
	    border-radius: 3px;
	    -webkit-transition: all 0.35s ease;
	    -moz-transition: all 0.35s ease;
	    -o-transition: all 0.35s ease;
	    transition: all 0.35s ease;
	}

	#machete-module-list .machete-module .machete-module-toggle-active {
	    margin-top: 15px;
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
			'description' => __('WordPress has a los of code just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer.','machete'),
			'is_active' => true,
			'role' => 'admin'
		),
		'cookies' => array(
			'full_title' => __('Cookie Law Warning','machete'),
			'description' => __('We know you hate cookie warning bars. Well, this is the less hateable cookie bar you\'ll find. It is really light, it won\'t affect your PageSpeed score and plays well with static cache plugins.','machete'),
			'is_active' => true,
			'role' => 'author'
		),
		'utils' => array(
			'full_title' => __('Analytics and Custom Code','machete'),
			'description' => __('You don\'t need a zillion plugins to perform easy task like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom styleseet (Google Fonts, Print Styles, accesibility tweaks...).','machete'),
			'is_active' => true,
			'role' => 'admin'
		),
		'maintenance' => array(
			'full_title' => __('Maintenance Mode','machete'),
			'description' => __('If you have to close yout website temporarly to the public, the navive WordPress maintenance mode falls short and most coming soon plugins are bulky, incomplete or expensive. Machete maintenance mode is light, simple and versatile.','machete'),
			'is_active' => true,
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