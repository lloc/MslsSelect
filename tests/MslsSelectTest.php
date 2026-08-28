<?php

declare( strict_types=1 );

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

class MslsSelectTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Guards against the header and the constant drifting apart, which WordPress.org
	 * rejects a release for.
	 */
	public function test_version_matches_the_plugin_header(): void {
		$header = file_get_contents( dirname( __DIR__ ) . '/MslsSelect.php' );

		$this->assertIsString( $header );
		$this->assertSame( 1, preg_match( '/^\s*\*\s*Version:\s*(\S+)$/m', $header, $matches ) );
		$this->assertSame( MslsSelect::VERSION, $matches[1] );
	}

	public function test_enqueue_scripts(): void {
		Functions\expect( 'wp_enqueue_script' )->once();
		Functions\expect( 'plugins_url' )->once()->andReturn( 'an_url' );

		MslsSelect::enqueue_scripts();

		$this->expectOutputString( '' );
	}

	public function test_get_tags(): void {
		$expected = array(
			'before_item'   => '',
			'after_item'    => '',
			'before_output' => '<select class="msls_languages">',
			'after_output'  => '</select>',
		);

		$this->assertEquals( $expected, MslsSelect::get_tags() );
	}

	public function test_init_admin_true(): void {
		Functions\expect( 'is_admin' )->once()->andReturn( true );

		$this->assertInstanceOf( MslsSelect::class, MslsSelect::init() );

		$this->assertFalse( has_action( 'wp_enqueue_scripts', array( MslsSelect::class, 'enqueue_scripts' ) ) );
		$this->assertFalse( has_filter( 'msls_output_get_tags', array( MslsSelect::class, 'get_tags' ) ) );
		$this->assertFalse( has_filter( 'msls_output_get', array( MslsSelect::class, 'output_get' ) ) );
		$this->assertFalse( has_filter( 'option_msls', array( MslsSelect::class, 'output_current_blog' ) ) );
		$this->assertFalse( has_filter( 'default_option_msls', array( MslsSelect::class, 'output_current_blog' ) ) );
	}

	public function test_init_admin_false(): void {
		Functions\expect( 'is_admin' )->once()->andReturn( false );

		$this->assertInstanceOf( MslsSelect::class, MslsSelect::init() );

		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', array( MslsSelect::class, 'enqueue_scripts' ) ) );
		$this->assertSame( 10, has_filter( 'msls_output_get_tags', array( MslsSelect::class, 'get_tags' ) ) );
		$this->assertSame( 10, has_filter( 'msls_output_get', array( MslsSelect::class, 'output_get' ) ) );

		/**
		 * Both are required: get_option() applies "option_msls" only when the row exists and
		 * "default_option_msls" when it does not.
		 */
		$this->assertSame( 10, has_filter( 'option_msls', array( MslsSelect::class, 'output_current_blog' ) ) );
		$this->assertSame( 10, has_filter( 'default_option_msls', array( MslsSelect::class, 'output_current_blog' ) ) );
	}

	public function test_output_get_true(): void {
		Functions\expect( 'esc_url' )->once()->andReturnFirstArg();
		Functions\expect( 'esc_html' )->once()->andReturnFirstArg();

		$link = (object) array( 'txt' => 'Test' );

		$expected = '<option value="/test/" selected="selected">Test</option>';

		$this->assertEquals( $expected, MslsSelect::output_get( '/test/', $link, true ) );
	}

	public function test_output_get_false(): void {
		Functions\expect( 'esc_url' )->once()->andReturnFirstArg();
		Functions\expect( 'esc_html' )->once()->andReturnFirstArg();

		$link = (object) array( 'txt' => 'Test' );

		$expected = '<option value="/test/">Test</option>';

		$this->assertEquals( $expected, MslsSelect::output_get( '/test/', $link, false ) );
	}

	/**
	 * The option value ends up in window.location, so an unescaped javascript: URI would
	 * be a redirect the visitor never asked for.
	 */
	public function test_output_get_escapes_url_and_text(): void {
		Functions\expect( 'esc_url' )->once()->with( 'javascript:alert(1)' )->andReturn( '' );
		Functions\expect( 'esc_html' )->once()->with( '<b>x</b>' )->andReturn( '&lt;b&gt;x&lt;/b&gt;' );

		$link = (object) array( 'txt' => '<b>x</b>' );

		$expected = '<option value="">&lt;b&gt;x&lt;/b&gt;</option>';

		$this->assertEquals( $expected, MslsSelect::output_get( 'javascript:alert(1)', $link, false ) );
	}

	public function test_output_current_blog_adds_the_key(): void {
		$this->assertSame(
			array(
				'display'             => 2,
				'output_current_blog' => 1,
			),
			MslsSelect::output_current_blog( array( 'display' => 2 ) )
		);
	}

	public function test_output_current_blog_overrides_a_disabled_value(): void {
		$this->assertSame(
			array( 'output_current_blog' => 1 ),
			MslsSelect::output_current_blog( array( 'output_current_blog' => 0 ) )
		);
	}

	/**
	 * WordPress hands false to the default_option_* filter when the option does not exist
	 * yet - writing to that false is a deprecation as of PHP 8.1.
	 *
	 * @dataProvider provide_non_array_options
	 *
	 * @param mixed $option
	 */
	public function test_output_current_blog_handles_non_arrays( $option ): void {
		$this->assertSame( array( 'output_current_blog' => 1 ), MslsSelect::output_current_blog( $option ) );
	}

	/**
	 * @return array<string, array<int, mixed>>
	 */
	public function provide_non_array_options(): array {
		return array(
			'false'  => array( false ),
			'null'   => array( null ),
			'string' => array( '' ),
		);
	}
}
