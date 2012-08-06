<?php
/**
 * SqlPropertyParser class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter\Error;

/**
 * Class to parse SQL errors.
 *
 * @author narcis.davins
 */
class SqlPropertyParser implements PropertyParser
{
	/**
	 * Get the Error message.
	 *
	 * @param string $error Error to parse.
	 * @return string
	 */
	public function getMessage( $error )
	{
		if ( preg_match( '/\nERROR:\s?(.*?)\n(QUERY|TRACE):/s', $error, $matches ) )
		{
			return $matches[1] . ' in ' . $this->getSourceFile( $error ) . ' on line ' . $this->getLine( $error );
		}

		trigger_error( "Could not parse error message from:\n $error\n", E_USER_WARNING );
		return $error;
	}

	/**
	 * Get the Error line.
	 *
	 * @param string $error Error to parse.
	 * @return integer
	 */
	public function getLine( $error )
	{
		if ( preg_match( '/(.*?) @PATH[^\(]*?\((\d+)\)/', self::getTraceSourceFileLine( $error ), $matches ) )
		{
			return $matches[2];
		}

		trigger_error( "Could not parse error line from:\n $error\n", E_USER_WARNING );
		return 0;
	}

	/**
	 * Get Error type.
	 *
	 * @param string $error Error to parse.
	 * @return string
	 */
	public function getType( $error )
	{
		return 'SQL';
	}

	/**
	 * Get file where the Error ocurred.
	 *
	 * @param string $error Error to parse.
	 * @return string
	 */
	public function getSourceFile( $error )
	{
		if ( preg_match( '/(.*?) (@PATH[^\(]*?)\(\d+\)/', self::getTraceSourceFileLine( $error ), $matches ) )
		{
			return $matches[2];
		}
		trigger_error( "Could not parse source file from:\n $error\n", E_USER_WARNING );
		return 'Unknown';
	}

	/**
	 * Get line from trace.
	 *
	 * @param string $error Error to parse.
	 * @return mixed
	 */
	protected function getTraceSourceFileLine( $error )
	{
		foreach ( $this->getTrace( $error ) as $trace_line )
		{
			if ( false === strpos( $trace_line, '/core/db.class.php' ) )
			{
				return $trace_line;
			}
		}
	}

	/**
	 * Get trace from Error.
	 *
	 * @static
	 * @param string $error Error to parse.
	 * @return array
	 */
	protected function getTrace( $error )
	{
		list( , $trace ) = preg_split( '@\nTRACE:\n@s', $error );
		return explode( "\n", $trace );
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