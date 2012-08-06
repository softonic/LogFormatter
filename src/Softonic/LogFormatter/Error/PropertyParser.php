<?php
/**
 * Property parser interface.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter\Error;

/**
 * Error property parsers interface.
 *
 * Classes used to extract information from each single Error should implement this interface.
 *
 * @author narcis.davins
 */
interface PropertyParser
{
	/**
	 * Gives the Error message, used to uniquely identify the Error.
	 *
	 * @param string $error Complete Error string.
	 * @return string
	 */
	public function getMessage( $error );

	/**
	 * Gives the line where the Error ocurred.
	 *
	 * @param string $error Complete Error string.
	 * @return string
	 */
	public function getLine( $error );

	/**
	 * Gives the Error type.
	 *
	 * This type is used to classify errors in reports.
	 *
	 * @param string $error Complete Error string.
	 * @return string
	 */
	public function getType( $error );

	/**
	 * Gives the file path where the Error ocurred.
	 *
	 * @param string $error Complete Error string.
	 * @return string
	 */
	public function getSourceFile( $error );

	/**
	 * Gives the severity of the Error.
	 *
	 * @param string $error Complete Error string.
	 * @return string
	 */
	public function getSeverity( $error );
}
?>