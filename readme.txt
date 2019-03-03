=== Machete ===
Contributors: nilovelez
Tags: cookies, analytics, code, css, javascript, admin
Requires at least: 4.6
Tested up to: 5.1
Requires PHP: 5.6
Stable tag: trunk
License: WTFPL
License URI: http://www.wtfpl.net/txt/copying/

Machete is a lean and simple suite of tools that solve common WordPress annoyances: cookie bar, tracking codes, header cleanup

== Description ==

Machete is a simple suite of tools that solve common WordPress annoyances using as few resources as posible. Machete doesn't cover every single user case, but there is a huge amount of sites that would require less plugins if they used Machete.

So far, Machete includes the following tools:

= Header cleanup: =
WordPress places a lot of code inside the <head> tag just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer.

= Cookie law warning: =
We know you hate cookie warning bars. Well, this is the less hateable cookie bar you'll find. It is really light, it won't affect your PageSpeed score and plays well with static cache plugins.

= Analytics and custom code: =
You don't need a zillion plugins to perform easy task like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom styleseet (Google Fonts, Print Styles, accesibility tweaks...).
The Google Analytics tracking code if PageSpeed optimized, GPDR friendly and has the option to track Contact Form 7 events.

= Maintenance mode: =
The maintenance mode that ships with WordPress is just a basic lockdown that is activated whenever you do a major update. With machete Maintenance Mode you can hide your uncomplete page from visitors and search engines, give your clients a secure temporary access and lock you site without affecting your SEO.

= Post & Page cloner: =
Adds a "duplicate" link to post, page and most post types lists. Also adds "copy to new draft" function to the post editor.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/machete` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure each tool using the corresponding link on the **Machete** side menu


== Frequently Asked Questions ==

= Doesn't plugin X does that better? =

Yes, but Machete does it well enough and probably is much lighter.

== Screenshots ==

1. Machete Welcome screen
2. Cookie bar configuration. Why can't it be all so simple?
3. This is not your typical tracking script...

== Changelog ==

= 3.3 =
* New Social Sharing Buttons module
* Lots of tiny changes to adhere to WordPress Coding standards
* Minor Gutenberg compatibility fixes
* Fixed the position of the admin notices on Machete admin pages
* Fixed WP-Cli Warnings (finally). Props to @angelfplaza and @oterox


= 3.2.3 =
* Improved Clone Module to make it work better with Gutemberg and complex page builders
* Fix: In some cases the clone module didn't copy the featured image.
* Fix: In some cases the clone module could break the post metas.

= 3.2.2 =
* Maintenance URL whitelist made more restrictive by request. Only 'wp-login.php' and 'wp-admin' accepted now
* Changed the admin stylesheet handle to prevent HUGE SVG icons on update

= 3.2.1 =
* Urgent fix: Fixes typo that prevented the maintenance page to work properly.

= 3.2 = 
* Improved the compliance of the code with the WordPress Coding Standards
* Made some interface changes. The redundant tab menu is gone, navigation is now made using the admin sidebar menu.
* Dropped the use of PHP sessions in the maintenance page. It uses session cookies now.
* New: New 'Clone item' button on the back-end admin bar to make the admin bar compatible with the Gutenberg interface.
* New: Added option to PowerTools to enable upload of SVG images
* Fix: Fixed ampersand escaping error in the code editor
* Fix: Typo in the Disabled REST API notice (props @carloslongarela)
* Fix: PHP 5.4 empty() syntax error. Dou you realize Machete only supports PHP 5.6+? (props @luisrull and @Selupress)

= 3.1.2 = 
* Added option to reduce Thumbnail JPEG quality 
* Fix to allow HTML in the cookie warning text
* Added option to PowerTools to disable RSS feeds
* Powertools fix

= 3.1.1 =
* Fixed a stupid PHP 5.4 bug (props @cheteronda)

= 3.1 =
Machete 3.1 is a completely new beast. Almost all the code has been rewritten, taking great care no to break existing sites. That is the reason version 3.0 wasn't pushed to the WordPress directory, everything had to be tested thoroughly first.

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
* New module estructure, modules are lighter and more isolated now.
* New import-export module to backup and restore machete settings
* Huge code refactor
* Huge module cleanup
* Fix module management issues in about tab
* Fixed false ‘error saving to database’ notice

= 2.1 = 
* Optimization functions are now divided in header cleanup, feature cleanup and optimization tweaks.
* Added options to remove medium_large thumbnail size
* Added option to disable plugin and theme editor
* Added option to remove comment autolinks

= 2.0.2 =
* Added an option to remove the capital_P_dangit filter
* Fixed the mobile styles of the admin tabs
* Switched the welcome check from a transient to an option saving 3 queries from each admin pageload

= 2.0.1 =
* renamed the admin.css file to admin_v2.css to prevent display problems to users who have the old stylesheet cached

= 2.0 = 
* Huge internal refactor. Machete modules can now be disabled individually.
* New Post & Page Cloner module
* redefined user role system. Authors can now access some function and backend actions are executed with all user levels
* Shiny new Icons!

= 1.7.2 =
* Rollback to re-enable the option to remove oEmbed Scripts. WordPress 4.8 doesn't need the oembed script but the JSON API

= 1.7.1 =
* Disabled Remove oEmbed Scripts as it interferes with WordPress 4.8 video widget

= 1.7 =
Added new actios to the optimization module:

* Remove oEmbed Scripts
* Slow Heartbeat
* JS Comment reply
* Empty trash every week

Thank you, @fpuente

* Added 'Anonymize user IPs' option to analytics code 

= 1.6.2 = 
* Fixed magic link session reported in some servers
* Fixed the jQuery-migrate remover. It shout play better with complex themes now

= 1.6.1 =
* Fixed some language strings

= 1.6 = 
* Some mayor internal changes
* "Header cleanup" is now "WordPress Optimization"
* Added option to disable PDF thumbnails
* Added option to limit post revisions
* Added option toremove jQuery-migrate
* Updated language strings

= 1.5.3 =
* Fixed an error that caused an error 500 in some environments (thanks cheteronda)
* Some minor usability fixed on the maintenance settings page

= 1.5.2 =
* Fixed a typo that prevented the use of custom content in the maintenance page
* Updated language strings

= 1.5.1 =
* Fixed a weird unexpected bug when calling get_post()

= 1.5 =
* New maintenance and coming soon module!
* Huge code cleanup
* Added cursor:pointer to 'accept cookies' button (thanks frantorres)
* Added 'Restore default warning text' link to cookie page'
* Finished WordPress 4.7 testing
* Updated language strings

= 1.4.6 = 
* (hopefully) the last modification to the cookie bar styles

= 1.4.5 =
* Redesign of cookie bar. Now it looks better on small-screen devices.
* By popular request, default cookiebar warning text is now shorter
* minor javascript fixes

= 1.4.4 =
* fixed TinyMCE error when activating emoji cleanup (thanks soyrafaramos)

= 1.4.3 =
* fixed cookie bar save error on some systems (thanks carlos.herrera)

= 1.4.2 =
* some minor debugging
* saved some database writes

= 1.4.1 = 
* Updated language strings
* some minor error fixes

= 1.4 =
* huge internal cleanup
* security improvements
* Updated language strings
* fixed slashes bug in cookie toolbar

= 1.3 =
* improved error control
* Updated language strings
* added .pot file
* some minor debugging

= 1.2 =
* Some tweaks and assets added for the WordPress repository

= 1.1 =
* Some minor cosmetical changes
* Updated language strings

= 1.0 =
* First publicly available version

== Upgrade Notice ==

= 1.5.3 =
Fixes an error that causes an error 500 in some environments

= 1.5.2 =
Fixes maintenance module content issue.

= 1.5 = 
¡New module! Maintenance and coming soon mode.

= 1.4.6 =
Cookie bar has been (slightly) redesigned. Resave your cookie bar settings to regenerate the static files.

= 1.1 =
Some image paths were broken