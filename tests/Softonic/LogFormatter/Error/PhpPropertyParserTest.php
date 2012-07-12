<?php
/**
 * PhpPropertyParserTest class.
 *
 * @package arch_tools_test
 * @subpackage CheckstyleLogs
 * @author narcis.davins
 */

require_once __DIR__ . '/PropertyParserAbstractTest.php';

/**
 * Test PhpPropertyClass class.
 *
 * @author narcis.davins
 */
class PhpPropertyParserTest extends PropertyParserAbstractTest
{
	/**
	 * Object tested.
	 *
	 * @var \Softonic\LogFormatter\Error\PhpPropertyParser
	 */
	public $obj;

	/**
	 * Set up test.
	 */
	public function setUp()
	{
		$this->obj = new \Softonic\LogFormatter\Error\PhpPropertyParser();
	}

	/**
	 * Provides Error testcase data.
	 *
	 * @return array
	 */
	public function errorProvider()
	{
		$data = array();

		$error = <<<'ERROR'
[2010-12-4 08:04:21]PHP User Error brabrabra in @PATH_CODE@/test.php on line 765

Stack Trace:
#0 @PATH_CODE@/test.php(765) asdsad
ERROR;
		$data[] = array(
			$error,
			array(
				'message' => 'PHP User Error brabrabra in @PATH_CODE@/test.php on line 765',
				'line' => 765,
				'source' => '@PATH_CODE@/test.php'
			)
		);

		$error = <<<'ERROR'
[2010-12-4 08:04:21]TestException(1024): 'a' in the array in @PATH_VENDOR@/adapter.model.php on line 694
[12.47s654] Unexpected exception in @PATH_CODE@/2.php on line 7864

Stack Trace:
#0 @PATH_CODE@/test.php(765) asdsad
ERROR;
		$data[] = array(
			$error,
			array(
				'message' => 'TestException(1024): \'a\' in the array in @PATH_VENDOR@/adapter.model.php on line 694',
				'line' => 694,
				'source' => '@PATH_VENDOR@/adapter.model.php'
			)
		);

		$error = <<<'ERROR'
[2010-12-4 08:04:21] TestException(1024): 'a'
in the array in @PATH_VENDOR@/adapter.model.php on line 694
[12.47s654] Unexpected exception in @PATH_CODE@/2.php on line 7864

Stack Trace:
#0 @PATH_CODE@/test.php(765) asdsad
ERROR;
		$data[] = array(
			$error,
			array(
				'message' => 'TestException(1024): \'a\'
in the array in @PATH_VENDOR@/adapter.model.php on line 694',
				'line' => 694,
				'source' => '@PATH_VENDOR@/adapter.model.php'
			)
		);

		return $data;
	}

	/**
	 * Test that getType return PHP.
	 */
	public function testGetType()
	{
		$this->assertEquals( 'PHP', $this->obj->getType( '' ) );
	}
}
?>