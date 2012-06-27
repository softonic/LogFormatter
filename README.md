#LogFormatter#

This library used to parse and convert any type of error log into an XML that can be then collected by jenkins errorlogs-plugin http://github.com/Softonic/errorlogs-plugin

## Dependencies ##
This library requires PHP >= 5.3 and Pimple ( http://pimple.sensiolabs.org/ )

## basic usage ##

Initialization: include Pimple and register an autoloader. You can both use the library bundled autoloader, or use any other one that can autoload PSR-0 standard.

    require_once __DIR__ . '/../src/Softonic/Autoload.php';
    require_once __DIR__ . '/../external/Pimple.php';

    \Softonic\Autoload::register();

Instantiate Container

    $container = new \Softonic\LogFormatter\Container();

We must configure the library to be able to identify when it is reading a new error. To do so we must define separators. Any separator must implement \Softonic\LogFormatter\Log\Separators interface. Separators can both be used to detect error start, or error ending.

    $end_separators = array( new \Softonic\LogFormatter\Log\Separator\Constant( '--------' ) );
    $separators = array( new \Softonic\LogFormatter\Log\Separator\Pattern( '/^\[[^\]]+\]/' ) );

    $container->addErrorEndSeparators( $end_separators );
    $container->addErrorStartSeparators( $separators );

The library reads the error logs line by line, and checks if the new line matches any of the separators.

Modify error paths, this step is optional, but it is really useful for being able to view the errors in Jenkins, as normally we will have different code paths in production and jenkins builds. You can define as many replaces as you want.

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

Then in jenkins all we would need to do is to add a small step to modify the paths to the correct path in our build, for example:

    WS_PATH=$WORKSPACE;
    WS_PATH="${WS_PATH//\//\\/}"
    sed -i "s/@PATH_CORE@/$WS_PATH\/source\/core3/g" $WORKSPACE/checkstyle-result.xml
    sed -i "s/@PATH_LIBS@/$WS_PATH\/source\/libs/g" $WORKSPACE/checkstyle-result.xml

Defining the class to use for detecting error properties (message, source file, line...). By default, if nothing specified it uses \Checkstyle_logs\Error\PhpPropertyParser class. Which if you follow all steps defined here, should be enough for parsing php log files, otherwise you might need to implement \Checkstyle_logs\Error\PropertyParser interface and set the classname to $container['property_parser_class'].

Get a collector object, start reading from logs:

    $collector = $container['collector'];
    $collector->collectFromLog( __DIR__ . '/phperrors.log', 'server1' );
    $collector->collectFromLog( __DIR__ . '/phperrors2.log', 'server2' );

Once everything is collected, dump the results to the xml file.

    $file = '/tmp/errorlog.xml';
    $collector->write( $file );