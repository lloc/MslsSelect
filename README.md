MslsSelect
========

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lloc/MslsSelect/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lloc/MslsSelect/?branch=master)
[![codecov](https://codecov.io/gh/lloc/MslsSelect/graph/badge.svg?token=W1CM7ZXXWM)](https://codecov.io/gh/lloc/MslsSelect)

Transforms the output of the Multisite Language Switcher to an HTML select

## Requirements
- WordPress 5.6 or higher running in multisite mode.
- [Multisite Language Switcher](https://wordpress.org/plugins/multisite-language-switcher/) plugin (MslsSelect replaces its default list output).
- PHP 7.4+ to match the plugin minimum.

## Installation
1. Install via the WordPress admin: go to `Plugins → Add New`, search for **MslsSelect**, and click *Install Now*.
2. Or install manually by uploading the latest `mslsselect.zip` to `wp-content/plugins`.
3. Activate **MslsSelect** network-wide (or on selected sites); the frontend hooks load automatically.

## Usage
- Wherever Multisite Language Switcher renders its language list (widget, block, or `the_msls()` template tag), MslsSelect swaps the markup for a `<select>` element and auto-redirects after selection.
- The generated `<select>` uses the `msls_languages` class; target it in your theme for styling, e.g.:

  ```css
  .msls_languages { max-width: 220px; }
  ```
- No additional configuration is required. The plugin ensures the current site stays in the list by updating the `msls` option when needed.

## Troubleshooting
- If you still see a list instead of a dropdown, confirm the Multisite Language Switcher plugin is active and outputting links on that template.
- Clear page caches or CDN layers after activation so the new markup and JavaScript load correctly.
- Check the browser console for JavaScript errors; the minified file is enqueued as `mslsselect` if you need to debug or dequeue it temporarily.
