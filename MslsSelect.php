<?php
/**
 * MslsSelect
 *
 * @copyright Copyright (C) 2011-2024, Dennis Ploetner, re@lloc.de
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 or later
 * @wordpress-plugin
 *
 * Plugin Name: MslsSelect
 * Requires Plugins: multisite-language-switcher
 * Version: 2.3.4
 * Plugin URI: https://wordpress.org/plugins/mslsselect/
 * Description: Transforms the output of the Multisite Language Switcher to an HTML select
 * Author: Dennis Ploetner
 * Author URI: http://lloc.de/
 * License: GPLv2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

declare( strict_types=1 );

/**
 * MslsSelect Class
 *
 * @package mslsselect
 */
class MslsSelect {

	const VERSION = '2.3.4';

	public function __construct() {
		$options = get_option( 'msls' );

		/**
		 * Check and set - if needed - the option to true because we want to have also the current blog in the list.
		 */
		if ( empty( $options['output_current_blog'] ) ) {
			$options['output_current_blog'] = 1;

			update_option( 'msls', $options );
		}
	}

	/**
	 * Init
	 *
	 * @return MslsSelect
	 */
	public static function init(): self {
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
			add_filter( 'msls_output_get_tags', [ __CLASS__, 'get_tags' ] );
			add_filter( 'msls_output_get', [ __CLASS__, 'output_get' ], 10, 3 );
		}

		return new self;
	}

	/**
	 * Enqueue scripts action
	 *
	 * @return void
	 */
	public static function enqueue_scripts(): void {
		wp_enqueue_script(
			'mslsselect',
			plugins_url( '/js/mslsselect.min.js', __FILE__ ),
			array(),
			self::VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}

	/**
	 * Filter for the 'msls_output_get'-hook
	 *
	 * @param string $url
	 * @param object $link
	 * @param bool $current
	 *
	 * @return string
	 */
	public static function output_get( string $url, $link, bool $current ): string {
		return sprintf( '<option value="%s"%s>%s</option>', $url, ( $current ? ' selected="selected"' : '' ), $link->txt );
	}

	/**
	 * Filter for the 'msls_output_get_tags'-hook
	 *
	 * @return array<string, string>
	 */
	public static function get_tags(): array {
		return [
			'before_item'   => '',
			'after_item'    => '',
			'before_output' => '<select class="msls_languages">',
			'after_output'  => '</select>',
		];
	}

}

// @codeCoverageIgnoreStart
if ( function_exists( 'add_action' ) ) {
	add_action( 'plugins_loaded', function () {
		MslsSelect::init();
	} );
}
// @codeCoverageIgnoreEnd
