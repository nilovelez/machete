# Status

Version 5.4 is merged into `master` and ready to be published on the WordPress.org plugin directory (SVN `trunk` and `tags/5.4`). It requires PHP 8.2 and WordPress 5.9, is tested up to WordPress 7.1, and adds options to disable the WordPress core AI features and the WordPress 7.2 `/random/` redirect, plus cookie bar fixes. Next: publish 5.4 to SVN, reply to the security report about the cookie bar once it is out, and turn the plugin website into a static site on GitHub Pages at machete.nilovelez.com (machetewp.com is being retired).

Notes:

- The bundled `languages/machete-es_ES.po` has 51 untranslated and 12 fuzzy strings; the WordPress.org language packs take precedence over it.
- `changelog.md` keeps the full history and is left out of the release zip; `readme.txt` keeps only the 5.x entries and links it.

## Log

Newest entries first. One per work session, brief.

- 2026-11-19: Prepared 5.4: PHP 8.2 and WordPress 5.9 minimums, WPCS cleanup of `inc/powertools/`, cookie bar script generation reworked (settings now need `unfiltered_html`), gtag guard, `/random/` option, plugin website moved to machete.nilovelez.com and the Analytics article link to nilovelez.com, version bump, tested up to 7.1, regenerated POT. Plugin Check: no errors, 20 direct-query warnings in the PowerTools purges. Checked out the WordPress.org SVN repo.
- 2026-11-18: guppy/STATUS.md created. Added the development setup (Playground blueprint, Composer with WPCS 3, `.gitattributes` and `.gitignore`) and registered the project in Guppy. Pending: 22 PHPCS errors and 11 warnings in `inc/powertools/`, ruleset minimum versions, and whether `changelog.md`, `credits.txt` and `license.txt` go in the release zip.
