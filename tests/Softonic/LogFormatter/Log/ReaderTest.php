<?php
/**
 * LogReaderTest class.
 *
 * @package arch_tools_test
 * @subpackage CheckstyleLogs
 * @author narcis.davins
 */

/**
 * Test logReader class.
 *
 * @author narcis.davins
 */
class LogReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * All files in this array will be unlinked in tearDown.
	 *
	 * @var array
	 */
	protected $created_files = array();

	/**
	 * Register autoload.
	 *
	 * @static
	 */
	public static function setUpBeforeClass()
	{
		require_once(__DIR__ . '/../../../../src/Softonic/Autoload.php');
		Softonic\Autoload::register();
	}

	/**
	 * Delete created files.
	 */
	public function tearDown()
	{
		foreach ( $this->created_files as $file )
		{
			unlink( $file );
		}
	}

	/**
	 * Test exception thrown if trying to read a non existing log.
	 */
	public function testExceptionOnNonExistingFile()
	{
		$this->setExpectedException( 'RuntimeException' );
		new Softonic\LogFormatter\Log\Reader( sys_get_temp_dir() . '/aaaaaaaaaaaaaaaaaaaaaa.txt' );
	}

	/**
	 * Test that an empty file gives no errors.
	 */
	public function testEmptyLog()
	{
		$files = tempnam( sys_get_temp_dir(), 'test_' );
		$this->created_files[] = $files;

		$obj = new Softonic\LogFormatter\Log\Reader( $files );
		$this->assertEmpty( $obj->getNextError() );
	}

	/**
	 * Test get php errors.
	 */
	public function testGetPhpErrors()
	{
		$obj = new Softonic\LogFormatter\Log\Reader( __DIR__ . '/../resources/php_errors.log' );
		$obj->addStartSeparator( new \Softonic\LogFormatter\Log\Separator\Pattern( '/^\[[^\]]+\]/' ) );

		$errors = array();
		while ( false != ( $error = $obj->getNextError() ) )
		{
			$this->assertStringStartsWith( '[', $error );
			$errors[] = $error;
		}

		$this->assertEquals( 5, count( $errors ) );
	}

	/**
	 * Test get sql errors.
	 */
	public function testGetSqlErrors()
	{
		$obj = new Softonic\LogFormatter\Log\Reader( __DIR__ . '/../resources/sql_error.log' );
		$obj->addEndSeparator( new Softonic\LogFormatter\Log\Separator\Constant( "---------------\n" ) );
		$errors = explode( "---------------\n", file_get_contents( __DIR__ . '/../resources/sql_error.log' ) );
		foreach ( $errors as $error )
		{
			if ( $error !== end( $errors ) )
			{
				$error .= "---------------\n";
			}
			$this->assertEquals( $error, $obj->getNextError() );
		}
		$this->assertEmpty( $obj->getNextError() );
	}
}
?>