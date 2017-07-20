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

	.machete-wrap .notice, .machete-wrap div.error, .machete-wrap div.updated {
	    display: block !important;
	    margin-right: 170px;
	}

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
	    color: #666;
	    font-size: 1.4em;
	    font-weight: 500;
	    margin-top: 0;
	    margin-right: 10px;
	}
	#machete-module-list .machete-module h3 a {
	    color: #0073aa;
	    text-decoration: none;
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

	

</style>



		<div id="machete-module-list">
		<?php foreach ($machete_modules as $slug => $args){ ?>
			<div class="machete-module-wrap"><div class="machete-module module-is-<?php echo $args['is_active'] ? 'active' : 'inactive' ?>">
				
				<?php if ($args['is_active'] && $args['has_config']) { ?>

					<a class="machete-module-image"
						href="<?php echo admin_url('admin.php?page=machete-'.$slug) ?>"
						title="<?php echo __('Configure','machete').' '.$args['full_title'] ?>">
						<img src="<?php echo MACHETE_BASE_URL ?>inc/<?php echo $slug ?>/banner.svg">
					</a>

				<?php } else { ?>

					<span class="machete-module-image" title="<?php echo $args['full_title'] ?>">
						<img src="<?php echo MACHETE_BASE_URL ?>inc/<?php echo $slug ?>/banner.svg">
					</span>

				<?php } ?>

				<div class="machete-module-text">
					<?php if ($args['has_config']) { ?>
						<h3><a href="<?php echo admin_url('admin.php?page=machete-'.$slug) ?>"
						title="<?php echo __('Configure','machete').' '.$args['full_title'] ?>"><?php echo $args['full_title'] ?></a></h3>

					<?php } else { ?>
						<h3><?php echo $args['full_title'] ?></h3>

					<?php } ?>

					<div class="machete-module-description">
						<?php echo $args['description'] ?>
					</div>
					<?php if ($args['is_active']){ ?><div class="machete-module-active-indicator"><?php _e('Active','machete') ?></div><?php } ?>

				</div>
				<div class="machete-module-bottom">
					<div class="machete-module-toggle-active">
					<?php 

					$action_url = wp_nonce_url( admin_url( "admin.php?page=machete&amp;module=" . $slug), 'machete_action_' . $slug );

					if ($args['is_active']) { ?>

						<?php if ($args['can_be_disabled']) { ?>
						<a href="<?php echo $action_url.'&amp;machete-action=deactivate' ?>" class="button-secondary" data-status="0"><?php _e('Deactivate','machete') ?></a>
						<?php } ?>

						<?php if ($args['has_config']) { ?>
						<a href="<?php echo admin_url('admin.php?page=machete-'.$slug) ?>"
						title="<?php echo __('Configure','machete').' '.$args['full_title'] ?>" class="button-secondary" data-status="0"><?php _e('Settings','machete') ?></a>
						<?php } ?>

					<?php } else { ?>

						<a href="<?php echo $action_url.'&amp;machete-action=activate' ?>" class="button-secondary" data-status="1"><?php _e('Activate','machete') ?></a>

					<?php } ?>
					</div>

				</div>


			</div></div>

		<?php } ?>

		</div>
	</div>	
			


	<!--<p class="description"></p>-->
	
</div>