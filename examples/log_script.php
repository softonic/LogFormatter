<?php
/**
 * Library use example.
 *
 * @package Softonic
 * @subpackage LogFormatter
 * @author narcis.davins
 */

require_once __DIR__ . '/../src/Softonic/Autoload.php';
require_once __DIR__ . '/../external/Pimple.php';

\Softonic\Autoload::register();

$container = new \Softonic\LogFormatter\Container();

$separator_string = "-----------------------\n";
$end_separators = array( new \Softonic\LogFormatter\Log\Separator\Constant( $separator_string ) );
$separators = array( new \Softonic\LogFormatter\Log\Separator\Pattern( '/^\[[^\]]+\]/' ) );

$container->addErrorEndSeparators( $end_separators );
$container->addErrorStartSeparators( $separators );

$container['error_path_replacements'] = array(
	array(
		'@/var/www/([^/]+)/www@',
		'@/var/www/([^/]+)/libs@',
	),
	array(
		'@PATH_CORE@',
		'@PATH_LIBS@',
	)
);

$collector = $container['collector'];
$collector->collectFromLog( __DIR__ . '/phperrors.log', 'test' );

$file = '/tmp/errorlog.xml';
$collector->write( $file );
?>
