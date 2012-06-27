<?php
/**
 * LogReaderTest class.
 *
 * @package arch_tools_test
 * @subpackage CheckstyleLogs
 * @author narcis.davins
 */

/**
 * CollectorTest.
 *
 * @author narcis.davins
 */
class CollectorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Register autoload.
	 *
	 * @static
	 */
	public function setUp()
	{
		if ( !class_exists( 'Pimple' ) && !is_readable( __DIR__ . '/../../../external/Pimple.php' ) )
		{
			$this->markTestSkipped();
		}
		require_once( __DIR__ . '/../../../external/Pimple.php' );
		require_once(__DIR__ . '/../../../src/Softonic/Autoload.php');
		\Softonic\Autoload::register();
	}

	/**
	 * Test collect from log.
	 */
	public function testCollectFromLog()
	{
		$container = new \Softonic\LogFormatter\Container();
		$reader = $this->getMock( 'Reader', array( 'getNextError' ) );
		$reader->expects( $this->exactly( 3 ) )
			->method( 'getNextError' )
			->will( $this->onConsecutiveCalls( 'error 1', 'error 2', '' ) );

		$processor = $this->getMock( 'Processor', array( 'addErrorToFile' ) );
		$processor->expects( $this->exactly( 2 ) )
			->method( 'addErrorToFile' )
			->will( $this->returnValue( null ) );

		$processor->expects( $this->at( 0 ) )
			->method( 'addErrorToFile' )
			->with(
				$this->equalTo( 'error 1' ),
				$this->anything(),
				$this->anything()
			)
			->will( $this->returnValue( null ) );

		$processor->expects( $this->at( 1 ) )
			->method( 'addErrorToFile' )
			->with(
				$this->equalTo( 'error 2' ),
				$this->anything(),
				$this->anything()
			)
			->will( $this->returnValue( null ) );

		$container['log_reader'] = $reader;
		$container['error_processor'] = $processor;

		$container['collector']->collectFromLog( 'foo.txt', 'bar', 'foobar' );
	}

	/**
	 * Test output.
	 */
	public function testOutputCollector()
	{
		$test_string = tempnam( sys_get_temp_dir(), 'test' );
		$container = new \Softonic\LogFormatter\Container();

		$foo_file = $this->getMock( 'stdClass', array( 'write' ) );
		$foo_file->expects( $this->once() )
			->method( 'write' );

		$processor = $this->getMock( '\Softonic\LogFormatter\Error\Processor', array( 'getFiles' ), array( $container ) );
		$processor->expects( $this->once() )
			->method( 'getFiles' )
			->will( $this->returnValue( array( $foo_file ) ) );

		$container['error_processor'] = $processor;
		$container['collector']->write( $test_string );

		$file_content = file_get_contents( $test_string );
		unlink( $test_string );
		$this->assertEquals(
			\Softonic\LogFormatter\Collector::FILE_HEADER . \Softonic\LogFormatter\Collector::FILE_FOOTER,
			$file_content
		);
	}
}
?>