<?php
error_reporting(E_ALL);

define('APP_PATH',          '/app');
define('START_MICROTIME',   microtime(true));
define('VERSION',           trim(file_get_contents('/VERSION')));
define('ENV',               getenv('ENV'));
include '/php/vendor/autoload.php';

try {


    $di = new Phalcon\Di\FactoryDefault;

    $files = array (
        'config',
        'cache',
        'error',
        'locale',
        'logger',
        'permacache',
        'redis',
        'router',
        'url',
        'view',
        'queue',
        'app'
    );

    foreach ($files as $filename) {
        $di->setShared($filename, require_once(APP_PATH. '/di/'.$filename.'.php'));
    }

    if (conf()->debug) {
        (new Snowair\Debugbar\ServiceProvider(APP_PATH . '/config/debugbar.php'))->start();
    }

    $response = $di->getApp()->handle();

    $response->send();

} catch (\Exception $e) {
    # @TODO: monolog + errpage here
    throw $e;
}
