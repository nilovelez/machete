== Changelog ==

= 5.0.1 = 
* Update: Updated translation files
* Update: Social share module: Updated x.com url for sharing
* Improvement: Manteintenance mode: Added compatibility with custom password recovery URLs

= 5.0 = 
* Checked for compatibility with WordPress 6.5.x
* Checked for compatibility with WooCommerce 9.0.x
* New: Cookie bar module: accent color option
* Improvement: Cookie bar module: modified the stylesheet to make them easier to override
* New: Maintenance module: added button to copy the magic link
* Update: Analytics and code module: Removed Universal Analytics tracking
* New: Clone module: added clone button to block editor
* Fix: Clone module: fixed loop check for block themes
* Fix: Clone module: added function to fix cloned post title
* Update: Social share module: replaced Twitter with X
* Fix: Social share module: force remove of underline in share buttons
* Fix: Fixed 'body_class undefined' warning
* Fix: Fixed some escape functions
* Fix: Added class property declaration to prevent deprecation notice in PHP >8.2
* Fix: Minor coding standards fixes

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

= 3.5.1 =
* Checked for compatibility with WordPress 5.5

= 3.5 =
* Changes to improve render on RTL languages
* Typo and grammar fixes, general proofreading of the English language (kudos @noplanman)
* Updated language files
* Fixed: minor javascript bug
* Tweak: prevented LastPass from loading on code fields (kudos @noplanman)
* Fixed: Fix HTML layout errors (kudos @noplanman)
* Style changes to adhere to WP Coding Style 

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

= 4.0 =
Machete 4.0 is a major upgrade. Re-save your cookie bar, and tracking code settings to regenerate the static files.

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