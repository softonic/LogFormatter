<?php
/**
 * PatternTest.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

/**
 * PatternTest.
 *
 * @author narcis.davins
 */
class PatternTest extends PHPUnit_Framework_TestCase
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
				'/AAA/',
				'aaa',
				false
			),
			'test does not match empty subject' => array(
				'/AAA/',
				'',
				false
			),
			'test different string don\'t match' => array(
				'/AAA/',
				'IOUAGHDLSJ',
				false
			),
			'Test case insensitive pattern' => array(
				'/AAA/i',
				'aaa',
				true
			),
			'Test same string' => array(
				'/^AAA$/',
				'AAA',
				true
			),
			'Test string contains' => array(
				'/AAA/',
				'BBBAAABBB',
				true
			),
			'test same string with special characters' => array(
				"/testing\n/",
				"testing\n",
				true
			)
		);
	}

	/**
	 * Test Pattern separator matching.
	 *
	 * @param string $separator Separator pattern to look for.
	 * @param string $subject String to compare the separator against.
	 * @param boolean $expected Expected result.
	 * @dataProvider separatorDataProvider
	 */
	public function testSeparatorMatch( $separator, $subject, $expected )
	{
		$this->object = new \Softonic\LogFormatter\Log\Separator\Pattern( $separator );
		$this->assertEquals( $expected, $this->object->matches( $subject ) );
	}
}
?>