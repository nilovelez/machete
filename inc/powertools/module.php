<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class machete_powertools_module extends machete_module {

	function __construct(){
		$this->init( array(
			'slug' => 'powertools',
			//'title' => __('PowerTools','machete'),
			'title' => '<span style="color: #ff9900">'.__('PowerTools','machete').'</span>',
			'full_title' => __('Machete PowerTools','machete'),
			'description' => __('Machete PowerTools is an free upgrade module targeted at WordPress developers and power users.','machete'),
			'is_active' => false,
			//'has_config' => true,
			'can_be_disabled' => false,
			// 'role' => 'manage_options'
			)
		);

		$this->powertools_array = array(
			'widget_shortcodes' => array(
				'title' => __('Shortcodes in Widgets','machete'),
				'description' => __('Enables the use of shortcodes in text/html widgets. It may slightly impact performance','machete')
			),
			'widget_oembed' => array(
				'title' => __('OEmbed in Widgets','machete'),
				'description' => __('Enables OEMbed in text/html widgets.','machete')
			),
			'rss_thumbnails' => array(
				'title' => __('Thumbnails in RSS','machete'),
				'description' => __('Add the featured image or the first attached image as the thumbnail of each post in the RSS feed','machete')
			),
			'page_excerpts' => array(
				'title' => __('Excerpts in Pages','machete'),
				'description' => __('Enables excerpts in pages. Useless for most people but awesome qhen combined with a page builder like Visual Composer','machete')
			),
			'save_with_keyboard' => array(
				'title' => __('Save with keyboard','machete'),
				'description' => __('Lets you save your posts, pages, theme and plugin files in the most natural way: pressing Ctrl+S (or Cmd+S on Mac). It saves as draft unpublished posts/pages and updates the ones that are already public','machete')
			),
			'move_scripts_footer' => array(
				'title' => __('Move scripts to footer','machete'),
				'description' => __('Move all JS queued scripts from header to footer. Machete will de-register the call for the JavaScript to load in the HEAD section of the site and re-register it to the FOOTER.','machete')
			),	
			'defer_all_scripts' => array(
				'title' => __('Defer your JavaScript','machete'),
				'description' => __('The defer attribute also downloads the JS file during HTML parsing, but it only executes it after the parsing has completed. Executed in order of appearance on the page','machete')
			),	
		);
	}

	public function admin(){
		$this->read_settings();

		if ( isset( $_POST['machete-powertools-saved'] ) ){
		    check_admin_referer( 'machete_save_powertools' );
		    if ( isset( $_POST['optionEnabled'] ) ){
		  		$this->save_settings($_POST['optionEnabled']);
		    }else{
		  		$this->save_settings();
		    }
		}

		
		if (isset($_POST['machete-powertools-action'])){
			check_admin_referer( 'machete_powertools_action' );
			
			switch ($_POST['action']){
				case __('Purge Transients','machete') :
					$this->purge_transients();
					break;
				case __('Purge Post Revisions','machete') :
					$this->purge_post_revisions();
					break;
				case __('Flush Rewrite Rules','machete') :
					$this->flush_rewrite_rules();
					break;
				case __('Flush Opcache','machete') :
					$this->flush_opcache();
					break;
				case __('Flush Object Cache','machete') :
					$this->flush_wpcache();
					break;
				default:
					$this->notice(__( 'Unknown action requested', 'machete' ), 'error');
					return false;

			}
		}
		

		if( count( $this->settings ) > 0 ) { 
			require($this->path . 'admin_functions.php' );
		}

		$this->all_powertools_checked = (count(array_intersect(array_keys($this->powertools_array), $this->settings)) == count($this->powertools_array)) ? true : false;

		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}

	public function frontend(){
		$this->read_settings();
		if( count( $this->settings ) > 0 ) { 
			require($this->path . 'frontend_functions.php' );
		}
	}

	protected function save_settings( $options = array(), $silent = false ) {

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
					return false;
				}
			}
			if (update_option('machete_powertools_settings',$options)){
				$this->settings = $options;
				$this->save_success_notice();
				return true;
			}else{
				$this->save_error_notice();
				return false;
			}

		}else{
			if ( delete_option('machete_powertools_settings')){
				$this->settings = array();
				if (!$silent) $this->save_success_notice();
				return true;
			}else{
				if (!$silent) $this->save_error_notice();
				return false;
			}
		}
		if (!$silent) $this->notice(__( 'No changes were needed.', 'machete' ), 'info');		
		return false;
	}

	protected function import( $options = array() ){
		if (!is_array($options) || (count($options) == 0)) return false;
		
		$valid_active_options = array_intersect($options, $this->powertools_array);
		return ( $this->save_settings($valid_active_options) );
	}
	
	private function purge_transients(){
		//echo '<h1 style="text-align: right">'.__('Purge Transients','machete').'</h1>';
		
		global $wpdb;

		/*
		 * Deletes all expired transients. The multi-table delete syntax is used.
		 * to delete the transient record from table a, and the corresponding.
		 * transient_timeout record from table b.
		 *
		 * Based on code inside core's upgrade_network() function.
		 */
		$sql = "DELETE a, b FROM $wpdb->options a, $wpdb->options b
			WHERE a.option_name LIKE %s
			AND a.option_name NOT LIKE %s
			AND b.option_name = CONCAT( '_transient_timeout_', SUBSTRING( a.option_name, 12 ) )
			AND b.option_value < %d";
		$rows = $wpdb->query( $wpdb->prepare( $sql, $wpdb->esc_like( '_transient_' ) . '%', $wpdb->esc_like( '_transient_timeout_' ) . '%', time() ) );

		$sql = "DELETE a, b FROM $wpdb->options a, $wpdb->options b
			WHERE a.option_name LIKE %s
			AND a.option_name NOT LIKE %s
			AND b.option_name = CONCAT( '_site_transient_timeout_', SUBSTRING( a.option_name, 17 ) )
			AND b.option_value < %d";
		$rows2 = $wpdb->query( $wpdb->prepare( $sql, $wpdb->esc_like( '_site_transient_' ) . '%', $wpdb->esc_like( '_site_transient_timeout_' ) . '%', time() ) );

		$this->notice( sprintf( __( '%d Transients Rows Cleared', 'machete' ), $rows + $rows2 ), 'success' );
		return true;
	}

	private function purge_post_revisions(){
		//echo '<h1 style="text-align: right">'.__('Purge Post Revisions','machete').'</h1>';
		
		global $wpdb;

		// DELETE ALL UNUSED POST REVISIONS
		$sql = "
		DELETE a,b,c
			FROM wp_posts a
			WHERE a.post_type = 'revision'
			LEFT JOIN wp_term_relationships b
			ON (a.ID = b.object_id)
			LEFT JOIN wp_postmeta c ON (a.ID = c.post_id);";
		$rows = $wpdb->query($sql);
		//echo '<div class="updated inline"><p>' . sprintf( __( '%d Post revisions deleted', 'woocommerce' ), $rows) . '</p></div>';
		
		$this->notice( sprintf( _n( 'Success! %s Post revision deleted.', 'Success! %s Post revisions deleted.', $rows, 'machete' ), $rows ), 'success' );
		return true;
	}

	private function flush_rewrite_rules(){
		flush_rewrite_rules();
		$this->notice(__('Rewrite rules flushed', 'machete' ), 'success');
		return true;
	}

	private function flush_wpcache(){
		wp_cache_flush();
		$this->notice(__('Object cache flushed', 'machete' ), 'success');
		return true;
	}

	private function flush_opcache(){
		opcache_reset();
		$this->notice(__('Opcache flushed', 'machete' ), 'success');
		return true;
	}


}
$machete->modules['powertools'] = new machete_powertools_module;