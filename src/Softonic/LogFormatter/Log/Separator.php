<?php
/**
 * Separator interface.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter\Log;

/**
 * Interface for classes used to detect Error separators.
 *
 * @author narcis.davins
 */
interface Separator
{
	/**
	 * Returns if the line matches the separator.
	 *
	 * @abstract
	 * @param string $subject Subject to test the separator against.
	 * @return boolean
	 */
	public function matches( $subject );
}
?>