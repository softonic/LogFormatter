<?php
/**
 * Processor class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter\Error;

/**
 * Class used to process errors from logs.
 *
 * @author narcis.davins
 */
class Processor
{
	/**
	 * File objects created.
	 *
	 * @var array
	 */
	protected $files = array();

	/**
	 * Error objects created.
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Dependency container.
	 *
	 * @var \Softonic\LogFormatter\Container
	 */
	protected $container;

	/**
	 * Initialize processor instance.
	 *
	 * @param \Softonic\LogFormatter\Container $container Dependency injection container.
	 */
	public function __construct( \Softonic\LogFormatter\Container $container )
	{
		$this->container = $container;
	}

	/**
	 * Get all affected files.
	 *
	 * @static
	 * @return array<\Softonic\LogFormatter\File>
	 */
	public function getFiles()
	{
		return $this->files;
	}

	/**
	 * Processes an Error and adds it to the file it needs.
	 *
	 * @param string $error_message Error message.
	 * @param string $package Package used to classify errors.
	 * @param string $severity Severity of the error.
	 */
	public function addErrorToFile( $error_message, $package, $severity = 'error' )
	{
		$error_message = htmlspecialchars( $error_message );
		$error_message = $this->replacePaths( $error_message );

		$parser = $this->getErrorPropertyParser();

		$file = $this->getFile( $parser, $error_message );
		$error = $this->getError( $parser, $error_message, $severity, $package );

		if ( $parser instanceof HasCount )
		{
			$error->increaseCount( $parser->getCount( $error_message ) );
		}

		$file->addError( $error );
	}

	/**
	 * Get Error.
	 *
	 * @param \Softonic\LogFormatter\Error\PropertyParser $parser Parser.
	 * @param string $error Error message.
	 * @param string $severity Severity.
	 * @param string $package Package.
	 *
	 * @return \Softonic\LogFormatter\Error
	 */
	protected function getError( \Softonic\LogFormatter\Error\PropertyParser $parser, $error, $severity, $package )
	{
		$message = $parser->getMessage( $error );
		$source = $this->getSource( $message, $package, $parser->getType( $error ) );
		if ( array_key_exists( $source, $this->errors ) )
		{
			$this->errors[$source]->increaseCount();
			return $this->errors[$source];
		}

		return $this->errors[$source] = new $this->container['error_class'](
		$parser->getLine( $error ),
			$severity,
			$message,
			$source
		);
	}

	/**
	 * Get file.
	 *
	 * @param \Softonic\LogFormatter\Error\PropertyParser $parser Parser.
	 * @param string $error Message.
	 * @return \Softonic\LogFormatter\File
	 */
	protected function getFile( \Softonic\LogFormatter\Error\PropertyParser $parser, $error )
	{
		$file = $parser->getSourceFile( $error );
		if ( !array_key_exists( $file, $this->files ) )
		{
			$this->files[$file] = new $this->container['file_class']( $file );
		}
		return $this->files[$file];
	}

	/**
	 * Search and replace paths.
	 *
	 * This is useful for changing easily each log path to a common PATTERN for then changing this PATTERN to jenkins path.
	 *
	 * @param string $error Error message.
	 * @return string
	 */
	protected function replacePaths( $error )
	{
		list( $patterns, $replacements ) = $this->container['error_path_replacements'];
		return preg_replace(
			$patterns,
			$replacements,
			$error
		);
	}

	/**
	 * Get error property parser.
	 *
	 * @return \Softonic\LogFormatter\Error\PropertyParser
	 */
	protected function getErrorPropertyParser()
	{
		return $this->container['property_parser'];
	}

	/**
	 * Generate the Error source.
	 *
	 * @param string $message Error message.
	 * @param string $package Package name.
	 * @param string $type Type.
	 * @return string
	 */
	protected function getSource( $message, $package, $type )
	{
		$pattern = '@[^a-zA-Z0-9]@';
		$message = preg_replace( $pattern, '-', $message );
		$package = preg_replace( $pattern, '-', $package );
		$type = preg_replace( $pattern, '-', $type );
		return "ErrorLog.$package.$type.$message";
	}
}
?>