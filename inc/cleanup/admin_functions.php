<?php
if ( ! defined( 'MACHETE_ADMIN_INIT' ) ) exit;



if ( ! function_exists( 'machete_cleanup_save_options' ) ) :

function machete_cleanup_error_save_options() { ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'Error saving configuration to database.', 'machete' ); ?></p>
    </div>
<?php }

function machete_cleanup_notice_no_changes() { ?>
    <div class="notice notice-info is-dismissible">
        <p><?php _e( 'No changes were needed.', 'machete' ); ?></p>
    </div>
<?php }



function machete_cleanup_save_options() {

	if (isset($_POST['optionEnabled'])){

		$settings = $_POST['optionEnabled'];

		for($i = 0; $i < count($settings); $i++){
			$settings[$i] = sanitize_text_field($settings[$i]);
		}
		
		if ($old_options = get_option('machete_cleanup_settings')){
			if(
				(0 == count(array_diff($settings, $old_options))) &&
				(0 == count(array_diff($old_options, $settings)))
				){
				// no removes && no adds
				add_action( 'admin_notices', 'machete_cleanup_notice_no_changes' );
				return false;
			}
		}
		

		if (update_option('machete_cleanup_settings',$settings)){
			return true;
		}else{
			add_action( 'admin_notices', 'machete_cleanup_error_save_options' );
			return false;
		}

	}else{
		if (delete_option('machete_cleanup_settings')){
			return true;
		}else{
			add_action( 'admin_notices', 'machete_cleanup_error_save_options' );
			return false;
		}
	}
	return false;
}
endif; // machete_cleanup_save_options()



//update_option( $option, $new_value, $autoload );
if (isset($_POST['machete-cleanup-saved'])){
  	check_admin_referer( 'machete_save_cleanup' );
	if(machete_cleanup_save_options()){
		new Machete_Notice(__( 'Options saved!', 'machete' ), 'success');
	}
}