<?php
/**
 * PropertyParserAbstractTest class.
 *
 * @package arch_tools_test
 * @subpackage CheckstyleLogs
 * @author narcis.davins
 */

/**
 * Test class for testing property parser classes.
 *
 * @author narcis.davins
 */
abstract class PropertyParserAbstractTest extends PHPUnit_Framework_TestCase
{
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
	 * Provides Error testcase data.
	 *
	 * Each data set must have the following format:
	 *
	 * array(
	 *      ERROR_TO_PARSE,
	 *      array(
	 *          'message' => EXPECTED_MESSAGE,
	 *          'line' => EXPECTED_LINE,
	 *          'source' => EXPECTED_SOURCE_FILE
	 *      )
	 * )
	 *
	 * @return array
	 */
	abstract public function errorProvider();

	/**
	 * Test message is correctly extracted.
	 *
	 * @param string $error Error used for testing.
	 * @param array $expected_error_properties Expected Error properties.
	 *
	 * @dataProvider errorProvider
	 */
	public function testExtractMessage( $error, $expected_error_properties )
	{
		$this->assertEquals( $expected_error_properties['message'], $this->obj->getMessage( $error ) );
	}

	/**
	 * Test line is correctly extracted.
	 *
	 * @param string $error Error used for testing.
	 * @param array $expected_error_properties Expected Error properties.
	 *
	 * @dataProvider errorProvider
	 */
	public function testExtractLine( $error, $expected_error_properties )
	{
		$this->assertEquals( $expected_error_properties['line'], $this->obj->getLine( $error ) );
	}

	/**
	 * Test source file is correctly extracted.
	 *
	 * @param string $error Error used for testing.
	 * @param array $expected_error_properties Expected Error properties.
	 *
	 * @dataProvider errorProvider
	 */
	public function testExtractSource( $error, $expected_error_properties )
	{
		$this->assertEquals( $expected_error_properties['source'], $this->obj->getSourceFile( $error ) );
	}
}
?>