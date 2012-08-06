<?php
/**
 * PhpPropertyParser class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter\Error;

/**
 * Class used to extract properties from php Error logs.
 *
 * @author narcis.davins
 */
class PhpPropertyParser implements PropertyParser
{
	/**
	 * Pattern used to extract the error properties.
	 *
	 * @var string
	 */
	const PROPERTIES_PATTERN = '/^(?:\[.*?\]\s*)?(?P<message>.*? in (?P<source>@PATH[^\s]*) on line (?P<line>\d+))/s';

	/**
	 * Extracts the message needed to identify this Error message.
	 *
	 * @param string $error Error message.
	 * @return string
	 */
	public function getMessage( $error )
	{
		if ( $source = $this->extractProperty( $error, 'message' ) )
		{
			return $source;
		}
		return $error;
	}

	/**
	 * Extracts the line where this Error occurred.
	 *
	 * @param string $error Error message.
	 * @return integer
	 */
	public function getLine( $error )
	{
		if ( $source = $this->extractProperty( $error, 'line' ) )
		{
			return $source;
		}
		return 0;
	}

	/**
	 * Return the type of the errors this class processes.
	 *
	 * @param string $error Complete Error string.
	 * @return string
	 */
	public function getType( $error )
	{
		return 'PHP';
	}

	/**
	 * Extracts the source file where this Error occurred.
	 *
	 * @param string $error Complete Error string.
	 * @return string
	 */
	public function getSourceFile( $error )
	{
		if ( $source = $this->extractProperty( $error, 'source' ) )
		{
			return $source;
		}
		return 'Unknown';
	}

	/**
	 * Extracts the property requested from the error.
	 *
	 * @param string $error Complete Error string.
	 * @param string $property Property to extract.
	 *
	 * @return string
	 */
	protected function extractProperty( $error, $property )
	{
		if ( preg_match( static::PROPERTIES_PATTERN, $error, $matches ) && isset( $matches[$property] ) )
		{
			return $matches[$property];
		}
		trigger_error( "Could not parse {$property} from:\n$error\n", E_USER_WARNING );
		return false;
	}

	/**
	 * Gives the severity of the Error.
	 *
	 * @param string $error Complete Error string.
	 * @return string
	 */
	public function getSeverity( $error )
	{
		return \Softonic\LogFormatter\Error::SEVERITY_ERROR;
	}
}
?>