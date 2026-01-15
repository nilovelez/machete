<?php
/**
 * Localization file for the Cookies module.
 *
 * @package WordPress
 * @subpackage Machete
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->params['title']       = __( 'Cookies & GDPR', 'machete' );
$this->params['full_title']  = __( 'Cookies & GDPR Warning', 'machete' );
$this->params['description'] = __( 'Light and responsive cookie law warning bar that won\'t affect your PageSpeed score and plays well with static cache plugins.', 'machete' );

/* Default texts */
$this->default_settings['warning_text'] = __( 'This website uses both technical cookies, essential for you to browse the website and use its features, and third-party cookies we use for marketing and data analytics porposes, as explained in our <a href="/cookies/">cookie policy</a>.', 'machete' );
$this->default_settings['accept_text'] = __( 'Accept cookies', 'machete' );
$this->default_settings['partial_accept_text'] = __( 'Accept only essential', 'machete' );
$this->default_settings['config_button_text'] = __( 'Cookies', 'machete' );

/* Themes */
$this->themes['new_light']['name'] = __( 'Modern Light', 'machete' );
$this->themes['new_dark']['name']  = __( 'Modern Dark', 'machete' );
$this->themes['cookie']['name']    = __( 'Cookie!', 'machete' );


/* Cookies bar inner HTML */
$this->cookies_bar_innerhtml = 'var machete_cookies_bar_html = \'<span id="machete_cookie_warning_text" class="machete_cookie_warning_text">{{warning_text}}</span> <button id="machete_accept_cookie_btn_partial" class="machete_accept_cookie_btn partial">{{partial_accept_text}}</button> <button id="machete_accept_cookie_btn" class="machete_accept_cookie_btn">{{accept_text}}</button>\';' . "\n";

// translators: button to config cookie settings again.
$this->cookies_bar_innerhtml .= 'var machete_cookies_configbar_html = \'<div id="machete_cookie_config_btn\" class=\"machete_cookie_config_btn\">' . __( 'Cookies', 'machete' ) . '</div>\';' . "\n";

$this->cookies_bar_innerhtml .= 'var machete_cookies_bar_stylesheet = \'{{theme_stylesheet}}\';' . "\n";
