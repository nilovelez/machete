=== Machete ===
Contributors: nilovelez
Tags: cookies, analytics, code, css, javascript, admin
Requires at least: 4.3
Tested up to: 4.8
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

= Maintenance mode: =
The maintenance mode that ships with WordPress is just a basic lockdown that is activated whenever you do a major update. With machete Maintenance Mode you can hide your uncomplete page from visitors and search engines, give your clients a secure temporary access and lock you site without affecting your SEO.

= Post & Page cloner =
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

= 2.0 = 
* Huge internal refactor. Machete modules can now be disabled individually.

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