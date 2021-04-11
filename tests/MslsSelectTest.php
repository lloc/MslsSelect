<?php

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

class MslsSelectTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	public function test_enqueue_scripts() {
		Functions\expect( 'wp_enqueue_script' )->once();
		Functions\expect( 'plugins_url' )->once()->andReturn( 'an_url' );

		MslsSelect::enqueue_scripts();

		$this->expectOutputString( '' );
	}

	public function test_get_tags() {
		$expected = [
			'before_item'   => '',
			'after_item'    => '',
			'before_output' => '<select class="msls_languages">',
			'after_output'  => '</select>',
		];

		$this->assertEquals( $expected, MslsSelect::get_tags() );
	}

	public function test_init_admin_true() {
		Functions\expect( 'get_option' )->once()->andReturn( [ 'output_current_blog' => 1 ] );
		Functions\expect( 'is_admin' )->once()->andReturn( true );

		$this->assertInstanceOf( MslsSelect::class, MslsSelect::init() );

		$this->assertFalse( has_action( 'wp_enqueue_scripts', [ MslsSelect::class, 'enqueue_scripts' ] ) );
		$this->assertFalse( has_filter( 'msls_output_get_tags', [ MslsSelect::class, 'get_tags' ] ) );
		$this->assertFalse( has_filter( 'msls_output_get', [ MslsSelect::class, 'output_get' ] ) );
	}

	public function test_init_admin_false() {
		Functions\expect( 'get_option' )->once()->andReturn( [ 'output_current_blog' => 1 ] );
		Functions\expect( 'is_admin' )->once()->andReturn( false );

		$this->assertInstanceOf( MslsSelect::class, MslsSelect::init() );

		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', [ MslsSelect::class, 'enqueue_scripts' ] ) );
		$this->assertSame( 10, has_filter( 'msls_output_get_tags', [ MslsSelect::class, 'get_tags' ] ) );
		$this->assertSame( 10, has_filter( 'msls_output_get', [ MslsSelect::class, 'output_get' ] ) );
	}

	public function test_output_get_true() {
		$url  = '/test/';
		$link = (object) [ 'txt' => 'Test' ];

		$expected = '<option value="/test/" selected="selected">Test</option>';

		$this->assertEquals( $expected, MslsSelect::output_get( $url, $link, true ) );
	}

	public function test_output_get_false() {
		$url  = '/test/';
		$link = (object) [ 'txt' => 'Test' ];

		$expected = '<option value="/test/">Test</option>';

		$this->assertEquals( $expected, MslsSelect::output_get( $url, $link, false ) );
	}

	public function test_update_option_in_constructor() {
		Functions\expect( 'get_option' )->once()->andReturn( [] );
		Functions\expect( 'update_option' )->once()->andReturn( true );

		$this->assertIsObject( ( new MslsSelect() ) );
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}
}
