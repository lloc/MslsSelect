=== MslsSelect ===

Contributors: realloc
Donate link: http://www.greenpeace.org/international/
Tags: multilingual, multisite, language, switcher, select
Requires at least: 5.6
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 2.3.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Transforms the output of the Multisite Language Switcher to an HTML select

== Description ==

Most people are likely to use some lines of PHP or the widget provided by the [Multisite Language Switcher](http://wordpress.org/plugins/multisite-language-switcher/) to integrate the links to the translations in their blogs.

But if you look for an **easy way** to change the output of the _Multisite Language Switcher_ as an HTML select with some JavaScript that forwards the visitor automatically after selecting an option then give **MslsSelect** a try.

== Installation ==

* Download the plugin and uncompress it with your preferred unzip programme
* Copy the entire directory in your plugin directory of your WordPress blog (/wp-content/plugins)
* Activate the plugin and see the magic ;)

== Changelog ==

= 2.3.4 =
* Cleaner builds

= 2.3.0 =
* Plugin check action added
* Resulting errors addressed
* "Requires Plugins" added
* Set PHP 7.4 as a minimum requirement
* Set WordPress 5.6 as a minimum requirement

= 2.2.7 =
* WordPress 6.3 tested
* Small enhancements

= 2.2.6 =
* WordPress 6.2.2 tested

= 2.2.5 =
* phpstan config excluded
* WordPress 5.8 tested

= 2.2.4 =
* Unit test reorganized
* WordPress 5.7 tested

= 2.2.3 =
* strict types and PHPStan tested

= 2.2.2 =
* WordPress 5.6 tested

= 2.2.1 =
* WordPress 5.5 tested

= 2.2 =
* full test coverage
* WordPress 5.4 tested
* PHP 7.1 as minimum declared

= 2.1 =
* jQuery dependency removed and JS reviewed

= 2.0 =
* compatibility with MSLS 2.0

= 1.3 =
* plugin inits when plugins_loaded runs and adds callback to hooks in its factory now

= 1.2 =
* include output_current_blog right after activation

= 1.1 =
* fix backwards compatibility

= 1.0 =
* tagged as stable
* WordPress Coding Standards
* PHPDoc

= 0.1 =
* first version