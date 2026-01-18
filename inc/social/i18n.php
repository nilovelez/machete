<?php
/**
 * Localization file for the social module.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->params['title']       = __( 'Social Sharing', 'machete' );
$this->params['full_title']  = __( 'Social Sharing Buttons', 'machete' );
$this->params['description'] = __( 'Social sharing buttons as simple as they can be. No bloat, no extra JS libraries, no API calls.', 'machete' );

/* translators: %%post_type%% is a placeholder, keep it as is. */
$this->default_settings['title'] = __( 'Share this %%post_type%%', 'machete' );


$this->positions ['before'] = __( 'At the beginning of the content', 'machete' );
$this->positions ['after']  = __( 'At the end of the content (hidden on mobile)', 'machete' );
$this->positions ['footer'] = __( 'Floating footer (mobile only)', 'machete' );


$this->networks['twitter']['title'] = _x( 'X', 'network name', 'machete' );
$this->networks['twitter']['label'] = _x( 'Post this', 'Twitter button label', 'machete' );

$this->networks['facebook']['title'] = _x( 'Facebook', 'network name', 'machete' );
$this->networks['facebook']['label'] = _x( 'Share this', 'Facebook button label', 'machete' );

$this->networks['linkedin']['title'] = _x( 'LinkedIn', 'network name', 'machete' );
$this->networks['linkedin']['label'] = _x( 'Share this', 'LinkedIn button label', 'machete' );

$this->networks['whatsapp']['title'] = _x( 'WhatsApp (only on mobile devices)', 'network name', 'machete' );
$this->networks['whatsapp']['label'] = _x( 'Share this', 'WhatsApp button label', 'machete' );

$this->networks['pinterest']['title'] = _x( 'Pinterest', 'network name', 'machete' );
$this->networks['pinterest']['label'] = _x( 'Pin this', 'Pinterest button label', 'machete' );
