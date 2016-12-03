<?php


class MACHETE_MAINTENANCE {

    function __construct(){

		// Actions & Filters if the landing page is active or being previewed
		$status = 'maintenance';

        //echo '<h1>holaaa</h1>';

		
		if(((!empty($status) && $status === 'maintenance') || (!empty($status) && $status === 'coming_soon')) || (isset($_GET['cs_preview']) && $_GET['cs_preview'] == 'true')){
			if(function_exists('bp_is_active')){
		        add_action( 'template_redirect', array(&$this,'render_comingsoon_page'),9);
		    }else{
		        add_action( 'template_redirect', array(&$this,'render_comingsoon_page'));
		    }
            add_action( 'admin_bar_menu',array( &$this, 'admin_bar_menu' ), 1000 );
            add_action( 'wp_enqueue_scripts', array(&$this,'add_scripts') );
        }

        
    }

    function add_scripts( $hook ) {
        wp_register_style(
		'machete-maintenance-styles',
		MACHETE_BASE_URL.'css/maintenance-style.css',
		false);
        wp_enqueue_style('machete-maintenance-styles');
    }

    function admin_bar_menu( $str ){
        global $wp_admin_bar;
        /*global $seed_csp4_settings,$wp_admin_bar;
        extract($seed_csp4_settings);

        if(!isset($status)){
            return false;
        }*/

        $status = 'maintenance';

        $msg = '';
        if($status == 'coming_soon'){
        	$msg = __('Coming Soon Mode','coming-soon');
        }elseif($status == 'maintenance'){
        	$msg = __('Maintenance Mode','coming-soon');
        }
    	//Add the main siteadmin menu item
        $wp_admin_bar->add_menu( array(
            'id'     => 'seed-csp4-notice',
            'href' => admin_url().'options-general.php?page=seed_csp4',
            'parent' => 'top-secondary',
            'title'  => $msg,
            'meta'   => array( 'class' => 'csp4-mode-active' ),
        ) );
    }


    function render_comingsoon_page() {

        //extract(seed_csp4_get_settings());
        /*
        if(!isset($status)){
            $err =  new WP_Error('error', __("Please enter your settings.", 'coming-soon'));
            echo $err->get_error_message();
            exit();
        }
        */

        $status = 'coming_soon';


        if(empty($_GET['mct_preview'])){
            $_GET['mct_preview'] = false;
        }

        // Check if Preview
        $is_preview = false;
        if ((isset($_GET['mct_preview']) && $_GET['mct_preview'] == 'true')) {
            $is_preview = true;
        }

        // Exit if a custom login page
        if(empty($disable_default_excluded_urls)){
            if(preg_match("/login|admin|dashboard|account/i",$_SERVER['REQUEST_URI']) > 0 && $is_preview == false){
                return false;
            }
        }


        // Check if user is logged in.
        if($is_preview === false){
            if(is_user_logged_in()){
                return false;
            }
        }

        // check magic link
        session_start();
        if ((isset($_GET['cht_token']) && $_GET['mct_token'] == 'logout')) {
            if (isset($_SESSION['mct_token'])){
                $_SESSION['mct_token'] = '';
            }
        }

        
        if ((isset($_GET['mct_token']) && $_GET['mct_token'] == 'magic')) {
            $_SESSION['mct_token'] = $_GET['mct_token'];
            return false;
        }else if((isset($_SESSION['mct_token']) && $_SESSION['mct_token'] == 'magic')){
            return false;
        }

        if($status == 'coming_soon'){
            // coming soon default content
            $html_content = array(
                'title' => __('Coming soon','machete'),
                'body'  => __('<h1>Under construction</h1><h3>New content is coming soon...</h3>','machete'),
                'content_class' => 'default'
            ); 
        }else{
            // maintenance default content
            $html_content = array(
                'title' => __('Maintenance','machete'),
                'body'  => __('<h1>Under maintenance</h1><h3>We will be back shortly</h3>','machete'),
                'content_class' => 'default'
            );
        }
        

/*
        $page = get_post(701);
        if ($page){
            $html_content = array(
                'title' => str_replace(']]>', ']]&gt;', apply_filters('the_title', $page->post_title)),
                'body'  => str_replace(']]>', ']]&gt;', apply_filters('the_content', $page->post_content)),
                'content_class' => 'custom'
                );
        }

*/      
       
        
        




        ?><!DOCTYPE html>
<html><head>
<title><?php $html_content['title'] ?></title>

<link rel="stylesheet" href="<?php echo MACHETE_BASE_URL.'css/maintenance-style.css' ?>" >

<?php
if(@file_exists(MACHETE_DATA_PATH.'header.html')){
    readfile(MACHETE_DATA_PATH.'header.html');
} ?>    <?php //wp_head(); ?>
        </head><body id="maintenance_page">

        <?php if(@file_exists(MACHETE_DATA_PATH.'body.html')){
            readfile(MACHETE_DATA_PATH.'body.html');
        } ?>

        <div id="content" class="<?php echo $html_content['content_class']; ?>">
        <?php echo $html_content['body']; ?>
        </div>

        <?php if(@file_exists(MACHETE_DATA_PATH.'footer.html')){
            readfile(MACHETE_DATA_PATH.'footer.html');
        }; ?>

        <?php //wp_footer(); ?>
        </body></html>
        <?php
        exit();


        // Finally check if we should show the coming soon page.
        $this->comingsoon_rendered = true;

        // set headers
        if($status == 'maintenance'){
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 86400'); // retry in a day
            $csp4_maintenance_file = WP_CONTENT_DIR."/maintenance.php";
            if(!empty($enable_maintenance_php) and file_exists($csp4_maintenance_file)){
                include_once( $csp4_maintenance_file );
                exit();
            }
        }

        // render template tags
        if(empty($html)){
            $template = $this->get_default_template();
            require_once( SEED_CSP4_PLUGIN_PATH.'/themes/default/functions.php' );
            $template_tags = array(
                '{Title}' => seed_csp4_title(),
                '{MetaDescription}' => seed_csp4_metadescription(),
                '{Privacy}' => seed_csp4_privacy(),
                '{Favicon}' => seed_csp4_favicon(),
                '{CustomCSS}' => seed_csp4_customcss(),
                '{Head}' => seed_csp4_head(),
                '{Footer}' => seed_csp4_footer(),
                '{Logo}' => seed_csp4_logo(),
                '{Headline}' => seed_csp4_headline(),
                '{Description}' => seed_csp4_description(),
                '{Credit}' => seed_csp4_credit(),
                '{Append_HTML}' => seed_csp4_append_html(),
                );
            echo strtr($template, $template_tags);
        }else{
            echo $html;
        }
        exit();

    }

	

}

$machete_maintenance = new MACHETE_MAINTENANCE;
