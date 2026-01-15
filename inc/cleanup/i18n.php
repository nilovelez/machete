<?php
/**
 * Localization file for the Cleanup module.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->params['title'] = __( 'Optimization', 'machete' );
$this->params['full_title'] = __( 'WordPress Optimization', 'machete' );
$this->params['description'] = __( 'Reduces much of the legacy code bloat in WordPress page headers. It also has some tweaks to make your site faster and safer.', 'machete' );

/* Cleanup array */
$this->cleanup_array['rsd_link'] = array(
    'title'       => __( 'RSD', 'machete' ),
    'description' => __( 'Remove Really Simple Discovery (RSD) links. They are used for automatic pingbacks.', 'machete' ),
);
$this->cleanup_array['wlwmanifest'] = array(
    'title'       => __( 'WLW', 'machete' ),
    'description' => __( 'Remove the link to wlwmanifest.xml. It is needed to support Windows Live Writer. Yes, seriously.', 'machete' ),
);
$this->cleanup_array['feed_links'] = array(
    'title'       => __( 'feed_links', 'machete' ),
    'description' => __( 'Remove Automatics RSS links. RSS will still work, but you will need to provide your own links.', 'machete' ),
);
$this->cleanup_array['feed_generator'] = array(
    'title'       => __( 'feed_generator', 'machete' ),
    'description' => __( 'Remove generator tag from RSS feeds.', 'machete' ),
);
$this->cleanup_array['next_prev'] = array(
    'title'       => __( 'adjacent_posts', 'machete' ),
    'description' => __( 'Remove the next and previous post links from the header', 'machete' ),
);
$this->cleanup_array['shortlink'] = array(
    'title'       => __( 'shortlink', 'machete' ),
    'description' => __( 'Remove the shortlink url from header', 'machete' ),
);
$this->cleanup_array['wp_generator'] = array(
    'title'       => __( 'wp_generator', 'machete' ),
    'description' => __( 'Remove WordPress and WooCommerce meta generator tags. Used by attackers to detect the WordPress version.', 'machete' ),
);
$this->cleanup_array['ver'] = array(
    'title'       => __( 'version', 'machete' ),
    'description' => __( 'Remove WordPress version var (?ver=) after styles and scripts. Used by attackers to detect the WordPress version.', 'machete' ),
);
$this->cleanup_array['wp_resource_hints'] = array(
    'title'       => __( 'dns-prefetch', 'machete' ),
    'description' => __( 'Removes dns-prefetch links from the header', 'machete' ),
);

/* Optimize array */
$this->optimize_array['emojicons']	 = array(
    'title'       => __( 'Emojicons', 'machete' ),
    'description' => __( 'Remove lots of emoji styles and scripts from the header, RSS, mail function, tinyMCE editor...', 'machete' ),
);
$this->optimize_array['pdf_thumbnails'] = array(
    'title'       => __( 'PDF Thumbnails', 'machete' ),
    'description' => __( 'Starting with 4.7, WordPress tries to make thumbnails from each PDF you upload, potentially crashing your server if GhostScript and ImageMagick aren\'t properly configured. This option disables PDF thumbnails.', 'machete' ),
);
$this->optimize_array['limit_revisions'] = array(
    'title'       => __( 'Limit Post Revisions', 'machete' ),
    'description' => __( 'Limits the number of stored revisions to 5 only if WP_POST_REVISIONS constant has not been defined.', 'machete' ),
);
$this->optimize_array['slow_heartbeat'] = array( // @fpuente addons
    'title'       => __( 'Slow Heartbeat', 'machete' ),
    'description' => __( 'By default, heartbeat makes a post call every 15 seconds on post edit pages. Change to 60 seconds (less CPU usage).', 'machete' ),
);
$this->optimize_array['comments_reply_feature'] = array( // @fpuente addons
    'title'       => __( 'JS Comment reply', 'machete' ),
    'description' => __( 'Load the comment-reply JS file only when needed.', 'machete' ),
);
$this->optimize_array['empty_trash_soon'] = array( // @fpuente addons
    'title'       => __( 'Empty trash every week', 'machete' ),
    'description' => __( 'You can shorten the time posts are kept in the trash, which is 30 days by default, to 1 week.', 'machete' ),
);
$this->optimize_array['capital_P_dangit'] = array(
    'title'       => __( 'capital_P_dangit', 'machete' ),
    'description' => __( 'Removes the filter that converts Wordpress to WordPress in every dang title, content or comment text.', 'machete' ),
);
$this->optimize_array['medium_large_size'] = array(
    'title'       => __( 'medium_large thumbnail', 'machete' ),
    'description' => __( 'Prevents WordPress from generating the medium_large 768px thumbnail size of image uploads.', 'machete' ),
);
$this->optimize_array['1536x1536_size'] = array(
    'title'       => __( '1536x1536 thumbnail', 'machete' ),
    'description' => __( 'Prevents WordPress from generating the 1536x1536 thumbnail size of image uploads.', 'machete' ),
);
$this->optimize_array['2048x2048_size'] = array(
    'title'       => __( '2048x2048 thumbnail', 'machete' ),
    'description' => __( 'Prevents WordPress from generating the 2048x2048 thumbnail size of image uploads.', 'machete' ),
);
$this->optimize_array['comment_autolinks'] = array(
    'title'       => __( 'No comment autolinks', 'machete' ),
    'description' => __( 'URLs in comments are converted to links by default. This feature is often exploited by spammers.', 'machete' ),
);
$this->optimize_array['disable_login_langs'] = array(
    'title'       => __( 'Disable login languages', 'machete' ),
    'description' => __( 'WordPress shows a language switcher at the bottom of the login screen. It\'s not need if you only use one language.', 'machete' ),
);
$this->optimize_array['disable_editor'] = array(
    'title'       => __( 'Plugin and theme editor', 'machete' ),
    'description' => __( 'Disables the plugins and theme editor. A mostly useless tool that can be very dangerous in the wrong hands.', 'machete' ),
);

/* Tweaks array */
$this->tweaks_array['json_api'] = array(
    'title'       => __( 'JSON API', 'machete' ),
    'description' => __( 'Remove the JSON-API links from page headers. Also require that API consumers be authenticated.', 'machete' ) . ' <br><span style="color: #d94f4f">' . __( 'Be careful. Might break some plugins that use the REST API.', 'machete' ) . '</span>',
);
$this->tweaks_array['xmlrpc'] = array(
    'title'       => __( 'XML-RPC', 'machete' ),
    'description' => __( 'Disable the XML-RPC interface. ', 'machete' ),
);
$this->tweaks_array['jquery-migrate'] = array(
    'title'       => __( 'remove jQuery-migrate', 'machete' ),
    'description' => __( 'jQuery-migrate provides diagnostics that can simplify upgrading to new versions of jQuery, you can safely disable it.', 'machete' ) . ' <br><span style="color: #d94f4f">' . __( 'May break some themes and plugins that depend on legacy code.', 'machete' ) . '</span>',
);
$this->tweaks_array['oembed_scripts'] = array(
    'title'       => __( 'Remove oEmbed Scripts', 'machete' ),
    'description' => __( 'Since WordPress 4.4, oEmbed is installed and available by default. If you don’t need oEmbed, you can remove it.', 'machete' ),
);
$this->tweaks_array['jpeg_quality'] = array(
    'title'       => __( 'Reduce JPEG quality', 'machete' ),
    'description' => __( 'When WordPress generates a JPEG thumbnail it compresses the image to 82% quality. Check this to reduce the default quality to 72%. (It doesn\'t affect original image quality).', 'machete' ),
);
$this->tweaks_array['big_image_scaling'] = array(
    'title'       => __( 'Disable big image scaling', 'machete' ),
    'description' => __( 'When you upload a huge image, WordPress generates a 2560px version of it. Check this to disable this feature.', 'machete' ),
);
$this->tweaks_array['gutenberg_css'] = array(
    'title'       => __( 'Remove block editor CSS', 'machete' ),
    'description' => __( 'Dequeues Gutenberg CSS stylesheets from the from the frontend.', 'machete' ) . '<br><span style="color: #d94f4f">' . __( 'Check this only if you are not using the block editor.', 'machete' ) . '</span>',
);
$this->tweaks_array['disable_global_css'] = array(
    'title'       => __( 'Disable Global CSS', 'machete' ),
    'description' => __( 'Removes the huge inline CSS elements that the block editor adds by default.', 'machete' ) . '<br><span style="color: #d94f4f">' . __( 'Be careful, you shouldn\'t check this if your current theme uses global styles.', 'machete' ) . '</span>',
);