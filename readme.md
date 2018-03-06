#Machete
Machete is a simple suite of tools that solve common WordPress annoyances using as few resources as posible. Machete doesn't cover every single user case, but there is a huge amount of sites that would require less plugins if they used Machete.

So far, Machete includes the following tools:

### Header cleanup:
WordPress places a lot of code inside the <head> tag just to keep backward compatiblity or enable optional features. You can disable most of it and save some time from each page request while making your installation safer.

### Cookie law warning:
We know you hate cookie warning bars. Well, this is the less hateable cookie bar you'll find. It is really light, it won't affect your PageSpeed score and plays well with static cache plugins.

### Analytics and custom code:
You don't need a zillion plugins to perform easy task like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom styleseet (Google Fonts, Print Styles, accesibility tweaks...).

### Maintenance mode:
The maintenance mode that ships with WordPress is just a basic lockdown that is activated whenever you do a major update. With machete Maintenance Mode you can hide your uncomplete page from visitors and search engines, give your clients a secure temporary access and lock you site without affecting your SEO.

### Post & Page cloner 
Adds a "duplicate" link to post, page and most post types lists. Also adds "copy to new draft" function to the post editor.

## Installation
1. Upload the plugin files to the `/wp-content/plugins/machete` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure each tool using the corresponding link on the **Machete** side menu

## PHPCS PHP CodeSniffer
WordPress Code Standards:
phpcs -q --standard=WordPress --report=summary --parallel=4 .

## commits summary
git diff --shortstat "@{1 month}"
git diff --shortstat "@{4 days ago}"

