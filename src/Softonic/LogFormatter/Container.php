<?php
/**
 * Dependency injection Container class.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

namespace Softonic\LogFormatter;

/**
 * Softonic\LogFormatter dependency injection container.
 *
 * Requires Pimple to be included.
 *
 * @see http://pimple.sensiolabs.org/
 * @author narcis.davins
 */
class Container extends \Pimple
{
	/**
	 * Initialize container.
	 */
	public function __construct()
	{
		parent::__construct();
		$this['error_class'] = '\Softonic\LogFormatter\Error';
		$this['file_class'] = '\Softonic\LogFormatter\File';
		$this['log_reader_class'] = '\Softonic\LogFormatter\Log\Reader';
		$this['error_processor_class'] = '\Softonic\LogFormatter\Error\Processor';
		$this['collector_class'] = '\Softonic\LogFormatter\Collector';
		$this['property_parser_class'] = '\Softonic\LogFormatter\Error\PhpPropertyParser';
		$this['error_path_replacements'] = array(
			array(),
			array()
		);

		$this->prepareCollector();
		$this->prepareProcessor();
		$this->preparePropertyParser();
		$this->prepareReader();
	}

	/**
	 * Prepare collector dependencies.
	 */
	protected function prepareCollector()
	{
		$this['collector'] = function( $container ){ return new $container['collector_class']( $container ); };
	}

	/**
	 * Prepare property parser dependencies.
	 */
	protected function preparePropertyParser()
	{
		$this['property_parser'] = function( $container ){ return new $container['property_parser_class'](); };
	}

	/**
	 * Prepare reader dependencies.
	 */
	protected function prepareReader()
	{
		$this['log_reader_path'] = '';
		$this['log_reader'] = function( $container ){
			return new $container['log_reader_class']( $container['log_reader_path'] );
		};
	}

	/**
	 * Prepare processor dependencies.
	 */
	protected function prepareProcessor()
	{
		$this['error_processor'] = $this->share(
			function( $container ){
				return new $container['error_processor_class']( $container );
			}
		);
	}

	/**
	 * Add Error start separators.
	 *
	 * @param array<\Softonic\LogFormatter\Log\Separator> $separators Separators that will match error start.
	 */
	public function addErrorStartSeparators( array $separators )
	{
		$this['log_reader'] = $this->extend( 'log_reader', function( $reader ) use ( $separators ){
			foreach ( $separators as $separator )
			{
				$reader->addStartSeparator( $separator );
			}
			return $reader;
		} );
	}

	/**
	 * Add Error end separators.
	 *
	 * @param array<\Softonic\LogFormatter\Log\Separator> $separators Separators that will match error end.
	 */
	public function addErrorEndSeparators( array $separators )
	{
		$this['log_reader'] = $this->extend( 'log_reader', function( $reader ) use ( $separators ){
			foreach ( $separators as $separator )
			{
				$reader->addEndSeparator( $separator );
			}
			return $reader;
		} );
	}

	/**
	 * Delete all separators previously added.
	 */
	public function resetSeparators()
	{
		$this->prepareReader();
	}
}
?>