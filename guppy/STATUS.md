# Status

Version 5.4 is published on the WordPress.org plugin directory (SVN r3811543, `tags/5.4`; git tag `v5.4`). It requires PHP 8.2 and WordPress 5.9 and is tested up to WordPress 7.1. The plugin website is a one-page static site in `docs/`, deployed by `.github/workflows/pages.yml` to GitHub Pages at https://machete.nilovelez.com/ whenever `docs/` changes on master (machetewp.com is being retired). Next: enable Enforce HTTPS in the Pages settings (needs a repo admin), and the items in `BACKLOG.md` (update the Live Preview blueprint).

Notes:

- Translations come only from the WordPress.org language packs; the plugin bundles no `languages/` folder.
- `changelog.md` keeps the full history and is left out of the release zip; `readme.txt` keeps only the 5.x entries and links it.

## Log

Newest entries first. One per work session, brief.

- 2026-11-19: Website review: Modules dropdown in the menu, rewritten module descriptions, full option lists with safety badges for Optimization, WooCommerce and PowerTools, new 1280x720 screenshots on WordPress 7.1, WTFPL footer.
- 2026-11-19: Built the one-page plugin website in `docs/` (sticky header, hero with the install form, philosophy, the eight modules with screenshots, testimonials) and published it on GitHub Pages at machete.nilovelez.com. Copied the WordPress.org `assets/` folder into git and added `BACKLOG.md`.
- 2026-11-19: Released 5.4 to WordPress.org (SVN r3811543) and tagged `v5.4` in git. Checked the Spanish translations on a test site: they come from the WordPress.org language pack and still work.
- 2026-11-19: Prepared 5.4: PHP 8.2 and WordPress 5.9 minimums, WPCS cleanup of `inc/powertools/`, cookie bar script generation reworked (settings now need `unfiltered_html`), gtag guard, `/random/` option, plugin website moved to machete.nilovelez.com and the Analytics article link to nilovelez.com, version bump, tested up to 7.1, removed the unused `languages/` folder. Plugin Check: no errors, 20 direct-query warnings in the PowerTools purges. Checked out the WordPress.org SVN repo.
- 2026-11-18: guppy/STATUS.md created. Added the development setup (Playground blueprint, Composer with WPCS 3, `.gitattributes` and `.gitignore`) and registered the project in Guppy. Pending: 22 PHPCS errors and 11 warnings in `inc/powertools/`, ruleset minimum versions, and whether `changelog.md`, `credits.txt` and `license.txt` go in the release zip.
