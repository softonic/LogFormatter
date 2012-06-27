<?php
/**
 * ContainerTest.php.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

/**
 * ContainerTest.
 *
 * @author narcis.davins
 */
class ContainerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Object being tested.
	 *
	 * @var \Softonic\LogFormatter\Container
	 */
	public $object;

	/**
	 * Instantiate container.
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

		$this->object = new \Softonic\LogFormatter\Container();
		$this->object['log_reader_path'] = __FILE__;
	}

	/**
	 * Data provider for container objects test.
	 *
	 * @return array
	 */
	public function containerObjectsDataProvider()
	{
		return array(
			array(
				'collector',
				'\Softonic\LogFormatter\Collector'
			),
			array(
				'property_parser',
				'\Softonic\LogFormatter\Error\PhpPropertyParser'
			),
			array(
				'log_reader',
				'\Softonic\LogFormatter\Log\Reader'
			),
			array(
				'error_processor',
				'\Softonic\LogFormatter\Error\Processor'
			)
		);
	}

	/**
	 * Test container instantiates the expected object types.
	 *
	 * @param string $object Object to retreive from container.
	 * @param string $expected_type Expected class.
	 *
	 * @dataProvider containerObjectsDataProvider
	 */
	public function testContainerObjects( $object, $expected_type )
	{
		$this->assertInstanceOf( $expected_type, $this->object[$object] );
	}

	/**
	 * Test adding separators.
	 */
	public function testAddSeparatorsToReader()
	{
		$separator = new \Softonic\LogFormatter\Log\Separator\Constant( 'TEST' );
		$mock_reader = $this->getMock(
			'\Softonic\LogFormatter\Log\Reader',
			array( 'addStartSeparator', 'addEndSeparator' ),
			array( __FILE__ )
		);
		$mock_reader->expects( $this->exactly( 2 ) )
			->method( 'addStartSeparator' )
			->with( $this->equalTo( $separator ) )
			->will( $this->returnValue( true ) );

		$mock_reader->expects( $this->once() )
			->method( 'addEndSeparator' )
			->with( $this->equalTo( $separator ) )
			->will( $this->returnValue( true ) );

		$this->object['log_reader'] = function() use ( $mock_reader ){
			return $mock_reader;
		};

		$this->object->addErrorEndSeparators( array( $separator ) );
		$this->object->addErrorStartSeparators( array( $separator, $separator ) );

		// Instantiate log reader.
		$this->object['log_reader'];
	}

	/**
	 * Test adding separators.
	 */
	public function testDifferentSeparatorCallsAreAppended()
	{
		$separator = new \Softonic\LogFormatter\Log\Separator\Constant( 'TEST' );
		$mock_reader = $this->getMock(
			'\Softonic\LogFormatter\Log\Reader',
			array( 'addEndSeparator' ),
			array( __FILE__ )
		);

		$mock_reader->expects( $this->exactly( 3 ) )
			->method( 'addEndSeparator' )
			->with( $this->equalTo( $separator ) )
			->will( $this->returnValue( true ) );

		$this->object['log_reader'] = function() use ( $mock_reader ){
			return $mock_reader;
		};

		$this->object->addErrorEndSeparators( array( $separator ) );
		$this->object->addErrorEndSeparators( array( $separator ) );
		$this->object->addErrorEndSeparators( array( $separator ) );

		$this->object['log_reader'];
	}
}
?>