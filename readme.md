# Machete
Machete is a simple suite of tools that solves common WordPress annoyances using as few resources as possible. Machete doesn't cover every single use case, but there is a huge amount of sites that would require less plugins if they used Machete.

All Machete tools have two things in common: they solve problems faced by many web developers and they do it using as few server resources as possible.

So far, Machete includes the following tools:

### WordPress Optimization
WordPress places a lot of code inside the `<head>` tag just to keep backward compatibility or to enable optional features. You can disable most of it and save some time from each page request while making your installation safer.

### Cookies & GDPR Warning
We know you hate cookie warning bars. Well, this is the least hateable cookie bar you'll find. It is really light, doesn't affect your PageSpeed score and plays well with static cache plugins.

### Analytics and custom code
You don't need a zillion plugins to perform easy tasks like inserting a verification meta tag (Google Search Console, Bing, Pinterest), a json-ld snippet or a custom stylesheet (Google Fonts, Print Styles, accessibility tweaks...).

The Google Analytics and Google Tag Manager tracking codes are PageSpeed optimized, GPDR friendly.

### Maintenance mode
The maintenance mode that ships with WordPress is just a basic lock-down that is activated whenever you do a major update. With machete Maintenance Mode you can hide your unfinished page from visitors and search engines, give your clients a secure temporary access and lock your site without affecting your SEO.

### Post & Page cloner
Adds a "duplicate" link to post, page and most post types lists. Also adds "copy to new draft" function to the post editor.

### Social Sharing Buttons
Social sharing done the Machete way. The icons are made as a custom webfont embedded in a minified CSS file that only weighs 5.8KB. The sharing actions use each platform's native share URL.

### WooCommerce Utils
WooCommerce was designed to work for every possible use case, but that often leads to unexpected behavior. These simple fixes can improve the WooCommerce user experience by making it behave as clients expect.

## Installation
1. Upload the plugin files to the `/wp-content/plugins/machete` directory, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure each tool using the corresponding link on the **Machete** side menu

## PHPCS PHP CodeSniffer
WordPress Coding Standards:
```sh
$ phpcs -q --standard=WordPress --report=summary --parallel=4 .
$ phpcs -q --standard=WordPress --report=summary --parallel=4 --extensions=php --colors .
$ phpcs -q --parallel=4 --extensions=php --colors .
```

## commits summary
```sh
$ git diff --shortstat "@{1 month}"
$ git diff --shortstat "@{4 days ago}"
```

## uglify and compress
https://jscompress.com/
