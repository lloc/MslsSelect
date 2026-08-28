<?php
/**
 * MslsSelect
 *
 * @copyright Copyright (C) 2011-2026, Dennis Ploetner, re@lloc.de
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 or later
 * @wordpress-plugin
 * @package mslsselect
 *
 * Plugin Name: MslsSelect
 * Requires Plugins: multisite-language-switcher
 * Version: 3.0.0
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
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MslsSelect Class
 *
 * @package mslsselect
 */
class MslsSelect {

	const VERSION = '3.0.0';

	/**
	 * Init
	 *
	 * @return MslsSelect
	 */
	public static function init(): self {
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
			add_filter( 'msls_output_get_tags', array( __CLASS__, 'get_tags' ) );
			add_filter( 'msls_output_get', array( __CLASS__, 'output_get' ), 10, 3 );

			/**
			 * Both hooks are needed: get_option() applies "option_msls" only when the option
			 * row exists and falls back to "default_option_msls" when it does not - which is
			 * exactly the state of a fresh Multisite Language Switcher install.
			 */
			add_filter( 'option_msls', array( __CLASS__, 'output_current_blog' ) );
			add_filter( 'default_option_msls', array( __CLASS__, 'output_current_blog' ) );
		}

		return new self();
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
	 * Filter for the 'option_msls'- and 'default_option_msls'-hooks
	 *
	 * The current blog has to be a part of the list, otherwise the select would never
	 * show the language the visitor is currently reading. Forcing the value while the
	 * option is read leaves the setting the user has saved untouched and saves a write
	 * to the database on every request in the frontend.
	 *
	 * @param mixed $options
	 *
	 * @return array<string, mixed>
	 */
	public static function output_current_blog( $options ): array {
		$options = is_array( $options ) ? $options : array();

		$options['output_current_blog'] = 1;

		return $options;
	}

	/**
	 * Filter for the 'msls_output_get'-hook
	 *
	 * @param string                        $url
	 * @param \lloc\Msls\Link\LinkInterface $link
	 * @param bool                          $current
	 *
	 * @return string
	 */
	public static function output_get( string $url, $link, bool $current ): string {
		return sprintf(
			'<option value="%s"%s>%s</option>',
			esc_url( $url ),
			( $current ? ' selected="selected"' : '' ),
			esc_html( $link->txt )
		);
	}

	/**
	 * Filter for the 'msls_output_get_tags'-hook
	 *
	 * @return array<string, string>
	 */
	public static function get_tags(): array {
		return array(
			'before_item'   => '',
			'after_item'    => '',
			'before_output' => '<select class="msls_languages">',
			'after_output'  => '</select>',
		);
	}
}

// @codeCoverageIgnoreStart
if ( function_exists( 'add_action' ) ) {
	add_action(
		'plugins_loaded',
		function () {
			MslsSelect::init();
		}
	);
}
// @codeCoverageIgnoreEnd
