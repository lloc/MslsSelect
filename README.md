MslsSelect
========

[![codecov](https://codecov.io/gh/lloc/MslsSelect/graph/badge.svg?token=W1CM7ZXXWM)](https://codecov.io/gh/lloc/MslsSelect)

Transforms the output of the Multisite Language Switcher to an HTML select

## Requirements
- WordPress 6.1 or higher running in multisite mode.
- [Multisite Language Switcher](https://wordpress.org/plugins/multisite-language-switcher/) plugin — MslsSelect is an add-on and declares that through its `Requires Plugins` header.
- PHP 7.4+ to match the plugin minimum.

MslsSelect is tested against **MSLS 3.0** and follows its major version. It keeps working with
MSLS 2.x as well: MslsSelect touches no `lloc\Msls\*` class, only the `msls_output_get_tags` and
`msls_output_get` filters, and those are unchanged in 3.0. There is therefore no version gate —
an older MSLS is not disabled.

## Installation
1. Install via the WordPress admin: go to `Plugins → Add New`, search for **MslsSelect**, and click *Install Now*.
2. Or install manually by uploading the latest `mslsselect.zip` to `wp-content/plugins`.
3. Activate **MslsSelect** network-wide (or on selected sites); the frontend hooks load automatically.

## Usage
- Wherever Multisite Language Switcher renders its language list (widget, block, or `msls_the_switcher()` template tag), MslsSelect swaps the markup for a `<select>` element and auto-redirects after selection.
- The generated `<select>` uses the `msls_languages` class; target it in your theme for styling, e.g.:

  ```css
  .msls_languages { max-width: 220px; }
  ```
- No additional configuration is required. MslsSelect needs MSLS's "Display link to the current
  language" setting to be on — otherwise the dropdown could never show the language you are
  currently reading. It forces that value while the option is read rather than writing it, so
  the setting you saved in MSLS stays untouched and the frontend performs no database write.

## Development
- `composer install` installs the tooling. `composer qa` runs the full gate: PHPCS, PHPStan and PHPUnit.
- PHPStan analyses `MslsSelect.php` against the **real** Multisite Language Switcher, which is
  pulled in as a dev dependency and bootstrapped in `tests/phpstan-bootstrap.php`. Never point it
  at test doubles — a stub reports whatever it was written to report and hides renames in the
  MSLS API instead of catching them.
- The dev tree needs PHP 8.0+ because MSLS 3.0 depends on `php-di ^7`. The plugin itself still
  runs on PHP 7.4; `PHPCompatibilityWP` with `testVersion 7.4-` verifies that statically.

## Troubleshooting
- If you still see a list instead of a dropdown, confirm the Multisite Language Switcher plugin is active and outputting links on that template.
- Clear page caches or CDN layers after activation so the new markup and JavaScript load correctly.
- Check the browser console for JavaScript errors; the minified file is enqueued as `mslsselect` if you need to debug or dequeue it temporarily.
