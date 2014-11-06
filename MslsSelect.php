<?php

/*
Plugin Name: MslsSelect
Plugin URI: https://github.com/lloc/MslsSelect
Description: Transforms the output of the Multisite Language Switcher to an HTML select
Version: 0.1
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

	const VERSION = '0.1';

	public function __construct() {
		add_action( 'wp_enqueue_scripts',   array( $this, 'enqueue_scripts' ) );
		add_filter( 'msls_output_get_tags', array( $this, 'get_tags' ) );
		add_filter( 'msls_output_get',      array( $this, 'output_get' ), 10, 3 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'mslsselect',
			plugins_url( '/js/mslsselect.js' , __FILE__ ),
			array( 'jquery' ),
			self::VERSION,
			true
		);
	}

	public function output_get( $url, $link, $current ) {
		return sprintf(
			'<option value="%s"%s>%s</option>',
			$url,
			( $current ? ' selected="selected"' : '' ),
			$link->txt
		);
	}

	public function get_tags( $tags ) {
		return array(
			'before_item'   => '',
			'after_item'    => '',
			'before_output' => '<select class="msls_languages">',
			'after_output'  => '</select>',
		);
	}

}
$mslsselect = new MslsSelect(); 