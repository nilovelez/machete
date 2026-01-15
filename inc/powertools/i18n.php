<?php
/**
 * Localization file for the powertools module.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->params['title']           = '<span style="color: #ff9900">' . __( 'PowerTools', 'machete' ) . '</span>';
$this->params['full_title']      = __( 'Machete PowerTools', 'machete' );
$this->params['description']     = __( 'Machete PowerTools is a free upgrade module targeted at WordPress developers and power users.', 'machete' );
				

$this->powertools_array['widget_shortcodes']  = array(
        'title'       => __( 'Shortcodes in Widgets', 'machete' ),
        'description' => __( 'Enables the use of shortcodes in text/html widgets. It may slightly impact performance', 'machete' ),
);

$this->powertools_array['rss_thumbnails'] = array(
        'title'       => __( 'Thumbnails in RSS', 'machete' ),
        'description' => __( 'Add the featured image or the first attached image as the thumbnail of each post in the RSS feed', 'machete' ),
);

$this->powertools_array['page_excerpts'] = array(
        'title'       => __( 'Excerpts in Pages', 'machete' ),
        'description' => __( 'Enables excerpts in pages. Useless for most people but awesome when combined with a page builder like Visual Composer', 'machete' ),
);

$this->powertools_array['move_scripts_footer'] = array(
        'title'       => __( 'Move scripts to footer', 'machete' ),
        'description' => __( 'Move all enqueued JS scripts from the header to the footer. Machete will de-register the call for the JavaScript to load in the HEAD section of the site and re-register it to the FOOTER.', 'machete' ),
);

$this->powertools_array['defer_all_scripts'] = array(
        'title'       => __( 'Defer your JavaScript', 'machete' ),
        'description' => __( 'The defer attribute also downloads the JS file during HTML parsing, but it only executes it after the parsing has completed. Executed in order of appearance on the page', 'machete' ),
);

$this->powertools_array['disable_feeds'] = array(
        'title'       => __( 'Disable all feeds', 'machete' ),
        'description' => __( 'RSS, RDF, Atom... disables all of them and makes life a little less easy for leechers.', 'machete' ),
);

$this->powertools_array['enable_svg'] = array(
    'title'       => __( 'Enable SVG images', 'machete' ),
     // translators: Link the post "SVG uploads in WordPress (the Inconvenient Truth)".
    'description' => sprintf( __( 'Enables the upload of SVG images to the media library. This <a href="%s" target="_blank" rel="noopener noreferrer">has been proven to be dangerous</a>, so be careful.', 'machete' ), 'https://bjornjohansen.no/svg-in-wordpress' ),
);

$this->powertools_array['disable_search'] = array(
    'title'       => __( 'Disable search', 'machete' ),
    'description' => __( 'Disables the public search from WordPress', 'machete' ),
);
