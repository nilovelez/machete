=== Machete ===
Contributors: nilovelez
Tags: cookies, analytics, code, css, javascript, admin
Requires at least: 4.3
Tested up to: 4.7
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

= 1.4.6 =
Cookie bar has been (slightly) redesigned. Resave your cookie bar settings to regenerate the static files.

= 1.1 =
Some image paths were broken