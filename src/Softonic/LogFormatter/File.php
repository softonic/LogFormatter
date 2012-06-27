<?php
/**
 * File class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter;

/**
 * File entity.
 *
 * @author narcis.davins
 */
class File
{
	/**
	 * File errors.
	 *
	 * @var SplObjectStorage
	 */
	protected $errors;

	/**
	 * File path.
	 *
	 * @var string $path
	 */
	protected $path;

	/**
	 * Create a new File entity.
	 *
	 * @param string $path File entity source path.
	 */
	public function __construct( $path )
	{
		$this->errors = new \SplObjectStorage();
		$this->path = $path;
	}

	/**
	 * Add a new Error to the file.
	 *
	 * @param \Softonic\LogFormatter\Error $error Error to add to the file.
	 */
	public function addError( \Softonic\LogFormatter\Error $error )
	{
		if ( !$this->errors->contains( $error ) )
		{
			$this->errors->attach( $error );
		}
	}

	/**
	 * Write entity to given file.
	 *
	 * @param string $file_path File where to write the output.
	 */
	public function write( $file_path )
	{
		file_put_contents( $file_path, "<file name=\"$this->path\">\n", FILE_APPEND );
		foreach ( $this->errors as $error )
		{
			$error->write( $file_path );
		}
		file_put_contents( $file_path, "</file>\n", FILE_APPEND );
	}
}
?>