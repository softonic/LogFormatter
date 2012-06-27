<?php
/**
 * ConstantTest.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

/**
 * ConstantTest.
 *
 * @author narcis.davins
 */
class ConstantTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Object being tested.
	 *
	 * @var \Softonic\LogFormatter\Log\Separator
	 */
	public $object;

	/**
	 * Set up autoloader.
	 *
	 * @static
	 */
	public static function setUpBeforeClass()
	{
		require_once(__DIR__ . '/../../../../../src/Softonic/Autoload.php');
		Softonic\Autoload::register();
	}

	/**
	 * Data provider for testSeparatorMatch.
	 *
	 * @return array
	 */
	public function separatorDataProvider()
	{
		return array(
			'Test case sensitivity' => array(
				'AAA',
				'aaa',
				false
			),
			'test does not match empty subject' => array(
				'AAA',
				'',
				false
			),
			'test empty constant does not match non empty subject' => array(
				'',
				'aaa',
				false
			),
			'test different string don\'t match' => array(
				'AAA',
				'IOUAGHDLSJ',
				false
			),
			'Test same string' => array(
				'AAA',
				'AAA',
				true
			),
			'test same string with special characters' => array(
				"testing\n",
				"testing\n",
				true
			),
			'test empty constant matches empty subject' => array(
				'',
				'',
				true
			),
		);
	}

	/**
	 * Test Constant separator matching.
	 *
	 * @param string $separator Separator to look for.
	 * @param string $subject String to compare the separator against.
	 * @param boolean $expected Expected result.
	 * @dataProvider separatorDataProvider
	 */
	public function testSeparatorMatch( $separator, $subject, $expected )
	{
		$this->object = new \Softonic\LogFormatter\Log\Separator\Constant( $separator );
		$this->assertEquals( $expected, $this->object->matches( $subject ) );
	}
}
?>