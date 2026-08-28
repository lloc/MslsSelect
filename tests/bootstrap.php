<?php
/**
 * PHPUnit bootstrap.
 *
 * MslsSelect.php exits when ABSPATH is missing, so the constant has to be defined before
 * the file is required. Loading it from here instead of through composer's autoload-dev
 * also keeps the plugin file out of the phpcs and phpstan processes, which analyse the
 * file rather than execute it.
 *
 * @package mslsselect
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

require_once dirname( __DIR__ ) . '/vendor/autoload.php';
require_once dirname( __DIR__ ) . '/MslsSelect.php';
