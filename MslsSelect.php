<?php

/*
Plugin Name: MslsSelect
Plugin URI: https://github.com/lloc/MslsSelect
Description: Transforms the output of the Multisite Language Switcher to an HTML select
Version: 1.4
Author: Dennis Ploetner
Author URI: http://lloc.de/
*/

/*
Copyright 2014  Dennis Ploetner  (email : re@lloc.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * MslsSelect Class
 * @package mslsselect
 */
class MslsSelect {

	const VERSION = '1.4';

	public function __construct() {
		$options = get_option( 'msls' );
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
	public static function init() {
		$obj = new self;

		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $obj, 'enqueue_scripts' ) );
			add_filter( 'msls_output_get_tags', array( $obj, 'get_tags' ) );
			add_filter( 'msls_output_get', array( $obj, 'output_get' ), 10, 3 );
		}

		return $obj;
	}

	/**
	 * Enqueue scripts action
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'mslsselect', plugins_url( '/js/mslsselect.js', __FILE__ ), [], self::VERSION, true );
	}

	/**
	 * Filter for the 'msls_output_get'-hook
	 *
	 * @param string $url
	 * @param StdClass $link
	 * @param bool $current
	 *
	 * @return string
	 */
	public function output_get( $url, $link, $current ) {
		return sprintf( '<option value="%s"%s>%s</option>', $url, ( $current ? ' selected="selected"' : '' ), $link->txt );
	}

	/**
	 * Filter for the 'msls_output_get_tags'-hook
	 *
	 * @return array
	 */
	public function get_tags() {
		return array(
			'before_item'   => '',
			'after_item'    => '',
			'before_output' => '<select class="msls_languages">',
			'after_output'  => '</select>',
		);
	}

}

add_action( 'plugins_loaded', array( 'MslsSelect', 'init' ) );
