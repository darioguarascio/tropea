<?php


define('VERSION',           trim(file_get_contents('/VERSION')));


require_once('/php/vendor/autoload.php');
$di = new Phalcon\Di\FactoryDefault\Cli;

$di->setShared('db', function() {
    return new Phalcon\Db\Adapter\Pdo\Postgresql([
        'host'     => getenv('WORKER_CFG_PG_HOST'),
        'port'     => getenv('WORKER_CFG_PG_PORT'),
        'username' => getenv('WORKER_CFG_PG_USER'),
        'password' => getenv('WORKER_CFG_PG_PASS'),
    ]);
});

$console = new Phalcon\Cli\Console;
$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments["task"] = $arg;
    } elseif ($k === 2) {
        $arguments["action"] = $arg;
    } elseif ($k >= 3) {
        $arguments["params"][] = $arg;
    }
}



try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();

    exit(255);
}
