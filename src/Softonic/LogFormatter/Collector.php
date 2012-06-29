<?php
/**
 * Collector class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter;

/**
 * Class that represents a checkstyle log.
 *
 * @author narcis.davins
 */
class Collector
{
	/**
	 * Xml header.
	 *
	 * @var string
	 */
	const FILE_HEADER = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<errorlog version=\"0.1\">\n";

	/**
	 * Xml footer.
	 *
	 * @var string
	 */
	const FILE_FOOTER = "</errorlog>";

	/**
	 * Dependency injection container.
	 *
	 * @var \Softonic\LogFormatter\Container
	 */
	protected $container;

	/**
	 * Error processor.
	 *
	 * @var \Softonic\LogFormatter\Error\Processor
	 */
	protected $error_processor;

	/**
	 * Initialize collector.
	 *
	 * @param \Softonic\LogFormatter\Container $container Dependency injection container.
	 */
	public function __construct( Container $container )
	{
		$this->container = $container;
		$this->error_processor = $container['error_processor'];
	}

	/**
	 * Collect errors from the given log.
	 *
	 * @param string $path Log file path.
	 * @param string $package Package.
	 * @param string $severity Severity.
	 */
	public function collectFromLog( $path, $package, $severity = 'error' )
	{
		$this->container['log_reader_path'] = $path;
		$reader = $this->container['log_reader'];

		while ( $error = $reader->getNextError() )
		{
			$this->error_processor->addErrorToFile( $error, $package, $severity );
		}
	}

	/**
	 * Write all collected errors to file.
	 *
	 * @param string $file_path File where to write the output.
	 */
	public function write( $file_path )
	{
		file_put_contents( $file_path, static::FILE_HEADER );
		foreach ( $this->error_processor->getFiles() as $file )
		{
			$file->write( $file_path );
		}
		file_put_contents( $file_path, static::FILE_FOOTER, FILE_APPEND );
	}
}
?>