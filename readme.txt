=== Machete ===
Contributors: nilovelez
Donate link: https://ko-fi.com/nilovelez
Tags: cookies, analytics, code, css, javascript, admin
Requires PHP: 7.0
Requires at least: 4.6
Tested up to: 6.1
Stable tag: 4.0.2
License: WTFPL
License URI: http://www.wtfpl.net/txt/copying/

Machete is a lean and simple suite of tools that solve common WordPress annoyances: cookie bar, tracking codes, header cleanup

== Description ==

Machete is a simple suite of tools that solves common WordPress annoyances using as few resources as possible. Machete doesn't cover every single use case, but there is a huge amount of sites that would require less plugins if they used Machete.

All Machete tools have two things in common: they solve problems faced by many web developers and they do it using as few server resources as possible.

So far, Machete includes the following tools:

= WordPress Optimization =
WordPress places a lot of code inside the `<head>` tag just to keep backward compatibility or to enable optional features. You can disable most of it and save some time from each page request while making your installation safer.

= Cookies & GDPR Warning =
We know you hate cookie warning bars. Well, this is the least hateable cookie bar you'll find. It is really light, doesn't affect your PageSpeed score and plays well with static cache plugins.

= Analytics and custom code =
You don't need a zillion plugins to perform easy tasks like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom stylesheet (Google Fonts, Print Styles, accessibility tweaks...).

The Google Analytics and Google Tag Manager tracking codes are PageSpeed optimized, GPDR friendly.

= Maintenance mode =
The maintenance mode that ships with WordPress is just a basic lock-down that is activated whenever you do a major update. With machete Maintenance Mode you can hide your unfinished page from visitors and search engines, give your clients a secure temporary access and lock your site without affecting your SEO.

= Post & Page cloner =
Adds a "duplicate" link to post, page and most post types lists. Also adds "copy to new draft" function to the post editor.

= Social Sharing Buttons =
Social sharing done the Machete way. The icons are made as a custom webfont embedded in a minified CSS file that only weighs 5.8KB. The sharing actions use each platform's native share URL.

= WooCommerce Utils =
WooCommerce was designed to work for every possible use case, but that often leads to unexpected behavior. These simple fixes can improve the WooCommerce user experience by making it behave as clients expect.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/machete` directory, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure each tool using the corresponding link on the **Machete** side menu

== Frequently Asked Questions ==

= Doesn't plugin X do that better? =

Yes, but Machete does it well enough and is probably much lighter.

= Is there any set-it-and-forget-it alternative to Machete? =

Machete is meant to be used as a development suite. If you are looking for a simpler solution to cut out WordPress bloat, you should have a look at WordPress [WPO Tweaks & Optimizations by Fernando Tellado](https://wordpress.org/plugins/wpo-tweaks/ "WPO Tweaks & Optimizations").

= Why does WordFence show a warning when I save my options? =

Machete caches some of its options to files located in `wp-content\uploads\machete\` to speed up loading. This is completely safe, but it's not a normal WordPress behaviour and it might make plugins like WordFence raise a warning. Just whitelist the action, save again and you'll be fine.

= Why doesn't Machete have an option to disable Gutenberg? =

Like it or not, the WordPress Block Editor (codenamed Gutenberg) is here to stay. Instead of disabling Gutenberg, you should be focusing on updating you workflow to use it. If you need to disable Gutenberg during the transition, you should use the official [Classic Editor plugin](https://wordpress.org/plugins/classic-editor/).

== Screenshots ==

1. Machete Welcome screen
2. WordPress Optimization. Cleanup time!
3. Cookie bar configuration. Simple and sweet
4. Analytics 4, Universal Analytics and Google tag Manager
5. Coming soon module with magic link to show your work
6. Lightest ever social sharing buttons

== Changelog ==

= 4.0.3 = 
* Added SVG icon to the maintenance page, just for the looks
* Changed the Pinterest share URL to prevent redirects
* Cleanup: Removed the option to disable the Contact Form 7 refill (no longer used)
* Cleanup: removed te option to disable the '.recentcomments a' output
* Fix: declared $machete as global to prevent WP-CLI errors (props Armando Lüscher)

= 4.0.2 =
* Checked for compatibility with WordPress 6.1
* Added option to disable WooCommerce SKUs
* Added option to disable WooCommerce duplicate SKU check
* Fix: Corrected WooCommerce Settings bug

= 4.0.1 =
Fix: Corrected the bug that prevented the obsolete tracking code warning to be dismissed.

= 4.0 =
* Checked for compatibility with WordPress 6.0
* Cleanup module: Added option to disable WordPress search functions
* Cookies module: Complete rework to comply with GDPR. New styles and scripts.
* Tracking & code: Added Analytics 4 and Google Tag Manager support
* New WooCommerce module!
* Import/export module removed
* Powertools: Removed option to save with keyboard
* Tracking & code: Removed option to track CF7 forms

[View the complete changelog](https://plugins.svn.wordpress.org/machete/trunk/changelog.md)
