<?php
/**
 * Autoload class.
 *
 * @package Softonic
 * @subpackage Softonic
 * @author narcis.davins
 */

namespace Softonic;

/**
 * Softonic\LogFormatter autoloader.
 */
class Autoload
{
	/**
	 * Registers autoloader as an SPL autoloader.
	 */
	static public function register()
	{
		ini_set( 'unserialize_callback_func', 'spl_autoload_call' );
		spl_autoload_register( array( new self, 'autoload' ) );
	}

	/**
	 * Autoload function.
	 *
	 * @param string $class Class name.
	 */
	public function autoload( $class )
	{
		if ( 0 !== strpos( $class, 'Softonic' ) )
		{
			return;
		}

		$filename = __DIR__ . str_replace( '\\', DIRECTORY_SEPARATOR, substr( $class, strlen( 'Softonic' ) ) ) . '.php';
		if ( file_exists( $filename ) )
		{
			require_once $filename;
		}
	}
}
