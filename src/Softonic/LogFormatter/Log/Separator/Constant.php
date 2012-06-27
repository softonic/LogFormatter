<?php
/**
 * Constant class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter\Log\Separator;

/**
 * Separator to match a line to the given constant string.
 *
 * @author narcis.davins
 */
class Constant implements \Softonic\LogFormatter\Log\Separator
{
	/**
	 * Constant string separator.
	 *
	 * @var string
	 */
	protected $string;

	/**
	 * Initialize separator.
	 *
	 * @param string $string Constant separator to use.
	 */
	public function __construct( $string )
	{
		$this->string = $string;
	}

	/**
	 * Returns if the line matches the separator.
	 *
	 * @param string $subject Subject to test the constant against.
	 * @return boolean
	 */
	public function matches( $subject )
	{
		return ( $this->string == $subject );
	}
}
?>