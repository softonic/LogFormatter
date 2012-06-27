<?php
/**
 * HasCount interface.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter\Error;

/**
 * Property parsers implementing this interface can specify the count value for each Error type.
 *
 * This is used for example when your errors from the logs already contain the times an Error happened.
 *
 * @author narcis.davins
 */
interface HasCount
{
	/**
	 * Method to return the amount of errors.
	 *
	 * @abstract
	 * @return integer
	 */
	public function getCount();
}
?>