<?php
/**
 * SqlPropertyParserTest class.
 *
 * @package arch_tools_test
 * @subpackage CheckstyleLogs
 * @author narcis.davins
 */

require_once __DIR__ . '/PropertyParserAbstractTest.php';

/**
 * Test SqlPropertyParser class.
 *
 * @author narcis.davins
 */
class SqlPropertyParserTest extends PropertyParserAbstractTest
{
	/**
	 * Object tested.
	 *
	 * @var \Softonic\LogFormatter\Error\SqlPropertyParser
	 */
	public $obj;

	/**
	 * Set up test.
	 */
	public function setUp()
	{
		$this->obj = new \Softonic\LogFormatter\Error\SqlPropertyParser();
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
USER AGENT: 'test/1.34.1'
ERROR:ERROR in 'testdb': Lock wait timeout exceeded; try restarting transaction
QUERY:
/* Save */
INSERT INTO
	test
SET
	test_field = NOW()

TRACE:
#0 @PATH_CODE@/core/db.class.php(225): Db->query('INSERT INTO...', 'Save')
#1 @PATH_CODE@/test.php(66): TestModel->save(0)
#2 {main}
ERROR;
		$data[] = array(
			$error,
			array(
				'message' => 'ERROR in \'testdb\': Lock wait timeout exceeded; try restarting transaction in @PATH_CODE@/test.php on line 66',
				'line' => 66,
				'source' => '@PATH_CODE@/test.php'
			)
		);

		$error = <<<'ERROR'
USER AGENT: 'test/1.34.1'
ERROR:ERROR in 'testdb2': Lock wait timeout exceeded; try restarting transaction
TRACE:
#0 @PATH_CODE@/core/db.class.php(225): Db->query('INSERT INTO...', 'Save')
#1 @PATH_CODE@/test2.php(137): TestModel->saveTest(0)
#2 {main}
ERROR;
		$data[] = array(
			$error,
			array(
				'message' => 'ERROR in \'testdb2\': Lock wait timeout exceeded; try restarting transaction in @PATH_CODE@/test2.php on line 137',
				'line' => 137,
				'source' => '@PATH_CODE@/test2.php'
			)
		);

		return $data;
	}

	/**
	 * Test that getType return SQL.
	 */
	public function testGetType()
	{
		$this->assertEquals( 'SQL', $this->obj->getType( '' ) );
	}
}
?>