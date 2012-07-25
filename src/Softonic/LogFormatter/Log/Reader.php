<?php
/**
 * LogReader class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter\Log;

/**
 * Class used for extracting errors from a log file.
 *
 * @author narcis.davins
 */
class Reader
{
	/**
	 * Log file path.
	 *
	 * @var string
	 */
	protected $log_file;

	/**
	 * Log file handler.
	 *
	 * @var resource
	 */
	protected $file_handler;

	/**
	 * Separators.
	 *
	 * @var array
	 */
	protected $start_separators = array();


	/**
	 * Separators.
	 *
	 * @var array
	 */
	protected $end_separators = array();

	/**
	 * Log reader constructor.
	 *
	 * @param string $log_file Log file path.
	 *
	 * @throws \RuntimeException If file does not exist.
	 */
	public function __construct( $log_file )
	{
		if ( !file_exists( $log_file ) )
		{
			throw new \RuntimeException( "File $log_file does not exist" );
		}
		$this->log_file = $log_file;
		$this->file_handler = fopen( $log_file, 'r' );
	}

	/**
	 * Method to get the next Error from the file.
	 *
	 * @return string
	 */
	public function getNextError()
	{
		$buffer = '';

		$line = fgets( $this->file_handler );
		do
		{
			if ( $this->isErrorStarting( $line ) && trim( $buffer ) != '' )
			{
				fseek( $this->file_handler, -strlen( $line ), SEEK_CUR );
				break;
			}

			$buffer .= $line;

			if ( $this->isErrorEnding( $line ) )
			{
				break;
			}
		}
		while (
			!feof( $this->file_handler )
			&& ( $line = fgets( $this->file_handler ) ) !== false
		);

		$buffer = utf8_encode( $buffer );

		// Remove control characters except tab and line endings: \r \n.
		$buffer = preg_replace( '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/s', '', $buffer );
		return $buffer;
	}

	/**
	 * Add separator to detect Error ending.
	 *
	 * @param \Softonic\LogFormatter\Log\Separator $separator Separator to add.
	 */
	public function addEndSeparator( Separator $separator )
	{
		if ( !$separator instanceof Separator )
		{
			throw new \InvalidArgumentException( 'Argument passed to '.__METHOD__.' must implement \Softonic\LogFormatter\Log\Separator' );
		}
		$this->end_separators[] = $separator;
	}

	/**
	 * Add separator to detect Error starting.
	 *
	 * @param \Softonic\LogFormatter\Log\Separator $separator Separator to add.
	 */
	public function addStartSeparator( Separator $separator )
	{
		if ( !$separator instanceof Separator )
		{
			throw new \InvalidArgumentException( 'Argument passed to '.__METHOD__.' must implement \Softonic\LogFormatter\Log\Separator' );
		}
		$this->start_separators[] = $separator;
	}

	/**
	 * Is this line the Error end.
	 *
	 * @param string $line Current line.
	 * @return boolean
	 */
	protected function isErrorEnding( $line )
	{
		return $this->matchesSeparators( $this->end_separators, $line );
	}

	/**
	 * Is this line the Error start.
	 *
	 * @param string $line Current line.
	 * @return boolean
	 */
	protected function isErrorStarting( $line )
	{
		return $this->matchesSeparators( $this->start_separators, $line );
	}

	/**
	 * Check if the given line matches some of the given separators.
	 *
	 * @param array<\Softonic\LogFormatter\Log\Separator> $separators Separators to check.
	 * @param string $line Current line.
	 * @return boolean
	 */
	private function matchesSeparators( $separators, $line )
	{
		foreach ( $separators as $separator )
		{
			if ( $separator->matches( $line ) )
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Close file handler.
	 */
	public function __destruct()
	{
		if ( is_resource( $this->file_handler ) )
		{
			fclose( $this->file_handler );
		}
	}
}
?>