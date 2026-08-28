<?php
/**
 * PHPStan bootstrap.
 *
 * Loads the real Multisite Language Switcher so that the `lloc\Msls\*` symbols MslsSelect
 * documents are analysed against the actual API. MslsSelect talks to MSLS through filters
 * only, so nothing here is needed at runtime - but the `msls_output_get` callback receives
 * a `lloc\Msls\Link\LinkInterface` and reads `$link->txt` from it. Analysing that against a
 * hand-written stub would hide a rename in MSLS instead of catching it, which is how
 * `MslsAdmin::init()` losing its return value slipped past MslsMenu.
 *
 * @package mslsselect
 */

declare( strict_types=1 );

// phpstan-wordpress may have defined it already; aliases.php bails out without it.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

require_once dirname( __DIR__ ) . '/vendor/lloc/multisite-language-switcher/includes/aliases.php';
