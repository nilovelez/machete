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


		<div class="theme-browser rendered">
			

			<div class="theme">
				<div class="theme-wrapper">
					<div class="theme-screenshot">
						<div class="module-info"><?php _e('WordPress places a lot of code inside the &lt;head&gt; tag just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer.','machete') ?></div>
						<img src="<?php echo MACHETE_BASE_URL ?>img/cleanup.png" alt="<?php _e('Header Cleanup','machete') ?>">
					</div>
					<h3 class="theme-name"><span><?php _e('Header Cleanup','machete') ?></span></h3>
					<div class="theme-actions"><a href="<?php echo admin_url('admin.php?page=machete-cleanup') ?>" class="button button-primary" title="<?php __('Configure','machete').' '.__('Header Cleanup','machete') ?>"><?php _e('Configure','machete') ?></a></div>
				</div>
			</div>
			<div class="theme">
				<div class="theme-wrapper">
					<div class="theme-screenshot">
						<div class="module-info"><?php _e('We know you hate cookie warning bars. Well, this is the less hateable cookie bar you\'ll find. It is really light, it won\'t affect your PageSpeed score and plays well with static cache plugins.','machete') ?></div>
						<img src="<?php echo MACHETE_BASE_URL ?>img/cookies.png" alt="<?php _e('Cookie Law Warning','machete') ?>">
					</div>
					<h3 class="theme-name"><span><?php _e('Cookie Law Warning','machete') ?></span></h3>
					<div class="theme-actions"><a href="<?php echo admin_url('admin.php?page=machete-cookies') ?>" class="button button-primary" title="<?php __('Configure','machete').' '.__('Cookie Law Warning','machete') ?>"><?php _e('Configure','machete') ?></a></div>
				</div>
			</div>
			<div class="theme">
				<div class="theme-wrapper">
					<div class="theme-screenshot">
						<div class="module-info"><?php _e('You don\'t need a zillion plugins to perform easy task like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom styleseet (Google Fonts, Print Styles, accesibility tweaks...).','machete') ?></div>
						<img src="<?php echo MACHETE_BASE_URL ?>img/code.png" alt="<?php _e('Analytics and Custom Code','machete') ?>">
					</div>
					<h3 class="theme-name"><span><?php _e('Analytics and Custom Code','machete') ?></span></h3>
					<div class="theme-actions"><a href="<?php echo admin_url('admin.php?page=machete-utils') ?>" class="button button-primary" title="<?php __('Configure','machete').' '.__('Analytics and Custom Code','machete') ?>"><?php _e('Configure','machete') ?></a></div>
				</div>
			</div>							
		</div>
	</div>	
			


	<!--<p class="description"></p>-->
	
</div>