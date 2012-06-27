<?php
/**
 * Pattern class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter\Log\Separator;

/**
 * Use regexp to match Error separator.
 *
 * @author narcis.davins
 */
class Pattern implements \Softonic\LogFormatter\Log\Separator
{
	/**
	 * Separator pattern.
	 *
	 * @var string
	 */
	protected $pattern;

	/**
	 * Initialize separator.
	 *
	 * @param string $pattern Separator pattern.
	 */
	public function __construct( $pattern )
	{
		$this->pattern = $pattern;
	}

	/**
	 * Returns if the line matches the separator.
	 *
	 * @param string $subject Subject to match the pattern against.
	 * @return boolean
	 */
	public function matches( $subject )
	{
		return (bool)preg_match( $this->pattern, $subject );
	}
}
?>
