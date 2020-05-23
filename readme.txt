=== Machete ===
Contributors: nilovelez
Donate link: https://machetewp.com/product/donation/
Tags: cookies, analytics, code, css, javascript, admin
Requires at least: 4.6
Tested up to: 5.3.2
Requires PHP: 5.6
Stable tag: trunk
License: WTFPL
License URI: http://www.wtfpl.net/txt/copying/

Machete is a lean and simple suite of tools that solve common WordPress annoyances: cookie bar, tracking codes, header cleanup

== Description ==

Machete is a simple suite of tools that solves common WordPress annoyances using as few resources as possible. Machete doesn't cover every single use case, but there is a huge amount of sites that would require less plugins if they used Machete.

All Machete tools have two things in common: they solve problems faced by many web developers and they do it using as few server resources as possible.

So far, Machete includes the following tools:

= Header cleanup =
WordPress places a lot of code inside the `<head>` tag just to keep backward compatibility or to enable optional features. You can disable most of it and save some time from each page request while making your installation safer.

= Cookie law warning =
We know you hate cookie warning bars. Well, this is the least hateable cookie bar you'll find. It is really light, doesn't affect your PageSpeed score and plays well with static cache plugins.

= Analytics and custom code =
You don't need a zillion plugins to perform easy tasks like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom stylesheet (Google Fonts, Print Styles, accessibility tweaks...).

The Google Analytics tracking code is PageSpeed optimized, GPDR friendly and has the option to track Contact Form 7 events.

= Maintenance mode =
The maintenance mode that ships with WordPress is just a basic lock-down that is activated whenever you do a major update. With machete Maintenance Mode you can hide your unfinished page from visitors and search engines, give your clients a secure temporary access and lock your site without affecting your SEO.

= Post & Page cloner =
Adds a "duplicate" link to post, page and most post types lists. Also adds "copy to new draft" function to the post editor.

= Social Sharing Buttons =
Social sharing done the Machete way. The icons are made as a custom webfont embedded in a minified CSS file that only weighs 5.8KB. The sharing actions use each platform's native share URL.

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
2. Cookie bar configuration. Why can't it all be so simple?
3. This is not your typical tracking script...

== Changelog ==

= 3.4.2 =
* Fixed: social share links didn't work on pages (kudos @davizoco for the heads up)
* Fixed: content of the individual post was returned empty if the social share module was active but no position was selected
* Fixed: Bundled translations weren't being loaded

= 3.4.1 =
* Version push to include a file left out in the 3.4 commit (sorry)

= 3.4 =
* Complete rework of the social share module
* Added shortcode for the social share buttons
* Updated WhatsApp share URL
* Updated the option to remove the medium_large thumbnail size
* New share JS without jQuery
* New custom code editor interface (with tabs!)
* Refactored the import/export module
* Minor WordPress Coding standards fixes

= 3.3.3 =
* Fixed social share title that disappears if no placeholder

= 3.3.2 =
* Minor CSS fix

= 3.3.1 =
* New: remove generator tag from RSS feeds
* Fixed social share WhatsApp URL
* Updated description wp_generator also removes WooCommerce generator tag

= 3.3 =
* New Social Sharing Buttons module
* Lots of tiny changes to adhere to WordPress Coding standards
* Minor Gutenberg compatibility fixes
* Fixed the position of the admin notices on Machete admin pages
* Fixed WP-CLI Warnings (finally). Props to @angelfplaza and @oterox

= 3.2.3 =
* Improved Clone Module to make it work better with Gutenberg and complex page builders
* Fix: In some cases the clone module didn't copy the featured image.
* Fix: In some cases the clone module could break the post metas.

= 3.2.2 =
* Maintenance URL whitelist made more restrictive by request. Only 'wp-login.php' and 'wp-admin' accepted now
* Changed the admin stylesheet handle to prevent HUGE SVG icons on update

= 3.2.1 =
* Urgent fix: Fixes typo that prevented the maintenance page from working properly.

= 3.2 =
* Improved the compliance of the code with the WordPress Coding Standards
* Made some interface changes. The redundant tab menu is gone, navigation is now made using the admin sidebar menu.
* Dropped the use of PHP sessions in the maintenance page. It uses session cookies now.
* New: New 'Clone item' button on the back-end admin bar to make the admin bar compatible with the Gutenberg interface.
* New: Added option to PowerTools to enable upload of SVG images
* Fix: Fixed ampersand escaping error in the code editor
* Fix: Typo in the Disabled REST API notice (props @carloslongarela)
* Fix: PHP 5.4 empty() syntax error. Do you realize Machete only supports PHP 5.6+? (props @luisrull and @Selupress)

= 3.1.2 =
* Added option to reduce Thumbnail JPEG quality
* Fix to allow HTML in the cookie warning text
* Added option to PowerTools to disable RSS feeds
* PowerTools fix

= 3.1.1 =
* Fixed a stupid PHP 5.4 bug (props @cheteronda)

= 3.1 =
Machete 3.1 is a completely new beast. Almost all the code has been rewritten, taking great care not to break existing sites. That is the reason version 3.0 wasn't pushed to the WordPress directory, everything had to be tested thoroughly first.

= 3.0.5 =
* Added Contact Form 7 tracking to the Analytics & Code module
* Updated machete PowerTools
* Added option to Machete PowerTools to disable RSS feeds
* Refactor of Optimization module. Code is now lighter and faster.

= 3.0.4 =
* Added native WordPress code editor to the custom code module
* Added new designs for the cookie bar warning
* Deleted old icons
* Added option to remove Contact Form 7 refill scripts
* Fixed option to remove jquery-migrate to reduce compatibility issues

= 3.0 =
* New module structure, modules are lighter and more isolated now.
* New import-export module to backup and restore machete settings
* Huge code refactor
* Huge module cleanup
* Fix module management issues in about tab
* Fixed false ‘error saving to database’ notice

== Upgrade Notice ==

= 3.4 =
Some fixes and lots or refinements. Enjoy!

= 1.5.2 =
Fixes maintenance module content issue.

= 1.5 =
¡New module! Maintenance and coming soon mode.

= 1.4.6 =
Cookie bar has been (slightly) redesigned. Re-save your cookie bar settings to regenerate the static files.

= 1.1 =
Some image paths were broken
