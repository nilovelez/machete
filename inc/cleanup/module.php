<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_cleanup_module extends machete_module {

	function __construct(){
		$this->init( array(
			'slug' => 'cleanup',
			'title' => __('Optimization','machete'),
			'full_title' => __('WordPress Optimization','machete'),
			'description' => __('Reduces much of the legacy code bloat in WordPress page headers. It also has some tweaks to make you site faster and safer.','machete'),
			//'is_active' => true,
			//'has_config' => true,
			//'can_be_disabled' => true,
			//'role' => 'manage_options'
			)
		);

		$this->cleanup_array = array(
		  'rsd_link' => array(
		    'title' => __('RSD','machete'),
		    'description' => __('Remove Really Simple Discovery(RSD) links. They are used for automatic pingbacks.','machete')
		  ),
		  'wlwmanifest' => array(
		    'title' => __('WLW','machete'),
		    'description' => __('Remove the link to wlwmanifest.xml. It is needed to support Windows Live Writer. Yes, seriously.','machete')
		  ),
		  'feed_links' => array(
		    'title' => __('feed_links','machete'),
		    'description' => __('Remove Automatics RSS links. RSS will still work, but you will need to provide your own links.','machete')
		  ),
		  'next_prev' => array(
		    'title' => __('adjacent_posts','machete'),
		    'description' => __('Remove the next and previous post links from the header','machete')
		  ),
		  'shortlink' => array(
		    'title' => __('shortlink','machete'),
		    'description' => __('Remove the shortlink url from header','machete')
		  ),
		  'wp_generator' => array(
		    'title' => __('wp_generator','machete'),
		    'description' => __('Remove WordPress meta generator tag. Used by atackers to detect the WordPress version.','machete')
		  ),
		  'ver' => array(
		    'title' => __('version','machete'),
		    'description' => __('Remove WordPress version var (?ver=) after styles and scripts. Used by atackers to detect the WordPress version.','machete')
		  ),
		  'recentcomments' => array(
		    'title' => __('recent_comments_style','machete'),
		    'description' => __('Removes a block of inline CSS used by old themes from the header','machete')
		  ),
		  'wp_resource_hints' => array(
		    'title' => __('dns-prefetch','machete'),
		    'description' => __('Removes dns-prefetch links from the header','machete')
		  ),
		);

		$this->optimize_array = array(
		  'emojicons' => array(
		    'title' => __('Emojicons','machete'),
		    'description' => __('Remove lots of emoji styles and scripts from the header, RSS, mail function, tinyMCE editor...','machete')
		  ),
		  'pdf_thumbnails' => array(
		    'title' => __('PDF Thumbnails','machete'),
		    'description' => __('Starting with 4.7, WordPress tries to make thumbnails from each PDF you upload, potentially crashing your server if GhostScript and Imagemagick aren\'t properly configured. This option disables PDF thumbnails.','machete')
		  ),
		  'limit_revisions' => array(
		    'title' => __('Limit Post Revisions','machete'),
		    'description' => __('Limits the number of stored revisions to 5 only WP_POST_REVISIONS constant has been defined.','machete')
		  ),
		  
		  
		  'slow_heartbeat' => array( // @fpuente addons
		    'title' => __('Slow Heartbeat','machete'),
		    'description' => __('By default, heartbeat makes a post call every 15 seconds on post edit pages. Change to 60 seconds (less CPU usage).','machete')
		  ),  
		  'comments_reply_feature' => array( // @fpuente addons
		    'title' => __('JS Comment reply','machete'),
		    'description' => __('Load the comment-reply JS file only when needed.','machete')
		  ),
		  
		  'empty_trash_soon' => array( // @fpuente addons
		    'title' => __('Empty trash every week','machete'),
		    'description' => __('You can shorten the time posts are kept in the trash, which is 30 days by default, to 1 week.','machete')
		  ),

		  'capital_P_dangit' => array(
		    'title' => __('capital_P_dangit'),
		    'description' => __('Removes the filter that converts Wordpress to WordPress in every dang title, content or comment text.','machete')
		  ),
		  'disable_editor' => array(
		    'title' => __('Plugin and theme editor','machete'),
		    'description' => __('Disables the plugins and theme editor. A mostly useless tool that can be very dangerous in the wrong hands.','machete')
		  ),
		  'medium_large_size' => array(
		    'title' => __('medium_large thumbnail','machete'),
		    'description' => __('Prevents WordPress from generating the medium_large 768px thumbnail size of image uploads.','machete')
		  ),
		  'comment_autolinks' => array(
		    'title' => __('No comment autolinks','machete'),
		    'description' => __('URLs in comments are converted to links by default. This feature is often exploited by spammers.','machete')
		  ),

		  
		);

		$this->tweaks_array = array(
		  'json_api' => array(
		    'title' => __('JSON API','machete'),
		    'description' => __('Disable Json API and remove link from header. Use with care.','machete') . ' <br><span style="color: red">'.__('The video widget added in WordPress 4.8 needs the JSON API to work','machete').'</span>'
		  ),
		  'jquery-migrate' => array(
		    'title' => __('remove jQuery-migrate','machete'),
		    'description' => __('jQuery-migrate provides diagnostics that can simplify upgrading to new versions of jQuery, you can safely disable it.','machete'). ' <br><span style="color: red">'.__('Breaks some themes that depend on visual builders, like Avada or The7','machete').'</span>'
		  ),
		  'oembed_scripts' => array( // @fpuente addons
		    'title' => __('Remove oEmbed Scripts','machete'),
		    'description' => __('Since WordPress 4.4, oEmbed is installed and available by default. If you don’t need oEmbed, you can remove it.','machete')
		  ),
		  
		);
	}

	public function frontend(){
		$this->read_settings();
		if( count( $this->settings ) > 0 ) { 
			require($this->path . 'frontend_functions.php' );
		}
	}
	
	public function admin(){
		$this->read_settings();

		if ( isset( $_POST['machete-cleanup-saved'] ) ){
		    check_admin_referer( 'machete_save_cleanup' );
		    if ( isset( $_POST['optionEnabled'] ) ){
		  		$this->save_settings($_POST['optionEnabled']);
		    }else{
		  		$this->save_settings();
		    }
		}

		if( count( $this->settings ) > 0 ) { 
			require($this->path . 'admin_functions.php' );
		}

		$this->all_cleanup_checked = (count(array_intersect(array_keys($this->cleanup_array), $this->settings)) == count($this->cleanup_array)) ? true : false;

		$this->all_optimize_checked = (count(array_intersect(array_keys($this->optimize_array), $this->settings)) == count($this->optimize_array)) ? true : false;

		$this->all_tweaks_checked = (count(array_intersect(array_keys($this->tweaks_array), $this->settings)) == count($this->tweaks_array)) ? true : false;

		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}

	protected function save_settings( $options = array(), $silent = false ) {
		
		$valid_options = array_merge(
			array_keys($this->cleanup_array), 
			array_merge(
				array_keys($this->optimize_array),
				array_keys($this->tweaks_array)
				)
			);
		$options = array_intersect($options, $valid_options);

		if ( count($options) > 0 ){

			for($i = 0; $i < count($options); $i++){
				$options[$i] = sanitize_text_field($options[$i]);
			}
			
			if ($old_options = $this->settings){
				if(
					(0 == count(array_diff($options, $old_options))) &&
					(0 == count(array_diff($old_options, $options)))
					){
					// no removes && no adds
					if (!$silent) $this->notice(__( 'No changes were needed.', 'machete' ), 'info');
					return true;
				}
			}
			if (update_option('machete_cleanup_settings',$options)){
				$this->settings = $options;
				if (!$silent) $this->save_success_notice();
				return true;
			}else{
				if (!$silent) $this->save_error_notice();
				return false;
			}

		}elseif (count($this->settings) > 0 ){
			if ( delete_option('machete_cleanup_settings')){
				$this->settings = array();
				if (!$silent) $this->save_success_notice();
				return true;
			}else{
				if (!$silent) $this->save_error_notice();
				return false;
			}
		}
		
		if (!$silent) $this->notice(__( 'No changes were needed.', 'machete' ), 'info');		
		return true;
	}

	
}
$machete->modules['cleanup'] = new machete_cleanup_module();