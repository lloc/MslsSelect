<?php

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

class MslsSelectTest extends TestCase {

	public function get_test() {
		Functions\expect( 'get_option' )->once()->andReturn( [] );
		Functions\expect( 'update_option' )->once();

		return new MslsSelect();
	}

	public function test_get_tags() {
		$test = $this->get_test();

		$this->assertNotEmpty( $test->get_tags() );
	}

	public function test_init_admin_true() {
		Functions\expect( 'get_option' )->once()->andReturn( [ 'output_current_blog' => 1 ] );
		Functions\expect( 'is_admin' )->once()->andReturn( true );

		$this->assertInstanceOf( MslsSelect::class, MslsSelect::init() );

		$this->assertFalse( has_action('wp_enqueue_scripts', [ MslsSelect::class, 'enqueue_scripts' ] ) );
		$this->assertFalse( has_filter('msls_output_get_tags', [ MslsSelect::class, 'get_tags' ] ) );
		$this->assertFalse( has_filter('msls_output_get', [ MslsSelect::class, 'output_get' ] ) );
	}

	public function test_init_admin_false() {
		Functions\expect( 'get_option' )->once()->andReturn( [ 'output_current_blog' => 1 ] );
		Functions\expect( 'is_admin' )->once()->andReturn( false );

		$this->assertInstanceOf( MslsSelect::class, MslsSelect::init() );

		$this->assertTrue( has_action('wp_enqueue_scripts', [ MslsSelect::class, 'enqueue_scripts' ] ) );
		$this->assertTrue( has_filter('msls_output_get_tags', [ MslsSelect::class, 'get_tags' ] ) );
		$this->assertTrue( has_filter('msls_output_get', [ MslsSelect::class, 'output_get' ] ) );
	}

	public function test_output_get_true() {
		$test = $this->get_test();

		$url  = '/test/';
		$link = (object) [ 'txt' => 'Test' ];

		$expected = '<option value="/test/" selected="selected">Test</option>';

		$this->assertEquals( $expected, $test->output_get( $url, $link, true ) );
	}

	public function test_output_get_false() {
		$test = $this->get_test();

		$url  = '/test/';
		$link = (object) [ 'txt' => 'Test' ];

		$expected = '<option value="/test/">Test</option>';

		$this->assertEquals( $expected, $test->output_get( $url, $link, false ) );
	}

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}
}
