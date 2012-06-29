<?php
/**
 * Error class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter;

/**
 * Error entity.
 *
 * @author narcis.davins
 */
class Error
{
	/**
	 * Error output format.
	 *
	 * @var string
	 */
	const ERROR_XML_ELEMENT = "<error line=\"%d\" column=\"1\" severity=\"%s\" message=\"%s\" source=\"%s\"/>\n";

	/**
	 * Times that this Error ocurred.
	 *
	 * @var integer
	 */
	protected $count = 1;

	/**
	 * Line where this Error is produced.
	 *
	 * @var integer
	 */
	protected $line;

	/**
	 * Severity of the Error.
	 *
	 * @var string
	 */
	protected $severity;

	/**
	 * Error message.
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * Error source.
	 *
	 * @var string
	 */
	protected $source;

	/**
	 * Initialize error.
	 *
	 * @param integer $line Error line.
	 * @param string $severity Error severity.
	 * @param string $message Error message.
	 * @param string $source Error source.
	 */
	public function __construct( $line, $severity, $message, $source )
	{
		$this->line = $line;
		$this->severity = $severity;
		$this->message = $message;
		$this->source = $source;
	}

	/**
	 * Increase Error count.
	 *
	 * @param integer $count Number of occurrences to add.
	 */
	public function increaseCount( $count = 1 )
	{
		$this->count += $count;
	}

	/**
	 * Write Error to file.
	 *
	 * @param string $file_path File path where to append the error output.
	 */
	public function write( $file_path )
	{
		for ( $i = 0; $i < $this->count; ++$i )
		{
			file_put_contents(
				$file_path,
				sprintf(
					static::ERROR_XML_ELEMENT,
					$this->line,
					$this->severity,
					$this->message . "($i)", // Number needed for the plugin to count each Error as a different one.
					$this->source
				),
				FILE_APPEND
			);
		}
	}
}
?>