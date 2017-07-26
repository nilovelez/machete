<?php
if ( ! defined( 'ABSPATH' ) ) exit;


$machete_modules['cleanup'] = array(
	'title' => __('Optimization','machete'),
	'full_title' => __('WordPress Optimization','machete'),
	'description' => __('Reduces much of the legacy code bloat in WordPress page headers. It also has some tweaks to make you site faster and safer.','machete'),
	'is_active' => true,
	'has_config' => true,
	'can_be_disabled' => false,
	'role' => 'admin'
);
$machete_modules['cookies'] = array(
	'title' => __('Cookie Law','machete'),
	'full_title' => __('Cookie Law Warning','machete'),
	'description' => __('Light and responsive cookie law warning bar that won\'t affect your PageSpeed score and plays well with static cache plugins.','machete'),
	'is_active' => true,
	'has_config' => true,
	'can_be_disabled' => true,
	'role' => 'author'
);
$machete_modules['utils'] = array(
	'title' => __('Analytics & Code','machete'),
	'full_title' => __('Analytics and Custom Code','machete'),
	'description' => __('Google Analytics tracking code manager and a simple editor to insert HTML, CSS and JS snippets or site verification tags.'),
	'is_active' => true,
	'has_config' => true,
	'can_be_disabled' => true,
	'role' => 'admin'
);
$machete_modules['maintenance'] = array(
	'title' => __('Maintenance Mode','machete'),
	'full_title' => __('Maintenance Mode','machete'),
	'description' => __('Customizable maintenance page to close your site during updates or development. It has a "magic link" to grant temporary access.','machete'),
	'is_active' => true,
	'has_config' => true,
	'can_be_disabled' => true,
	'role' => 'author'
);
$machete_modules['clone'] = array(
	'title' => __('Post & Page Cloner','machete'),
	'full_title' => __('Post & Page Cloner','machete'),
	'description' => __('Adds a "duplicate" link to post, page and most post types lists. Also adds "copy to new draft" function to the post editor.','machete'),
	'is_active' => true,
	'has_config' => false,
	'can_be_disabled' => true,
	'role' => 'author'
);
$machete_modules['powertools'] = array(
	'title' => __('PowerTools','machete'),
	'full_title' => __('Machete PowerTools','machete'),
	'description' => __('Machete PowerTools is an free upgrade module targeted at WordPress developers and power users.','machete'),
	'is_active' => false,
	'has_config' => true,
	'can_be_disabled' => false,
	'role' => 'admin'
);

if($machete_disabled_modules = get_option('machete_disabled_modules')){
	foreach ($machete_disabled_modules as $module) {
		if (isset($machete_modules[$module]) && $machete_modules[$module]['can_be_disabled']){
			$machete_modules[$module]['is_active'] = false;
		}
	}
}

if (defined('MACHETE_POWERTOOLS_INIT')) {
	$machete_modules['powertools']['is_active'] = true;
	$machete_modules['powertools']['description'] = __('Machete PowerTools are now active! Enjoy your new toy!','machete');
}


