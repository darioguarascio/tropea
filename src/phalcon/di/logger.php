<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Bramus\Monolog\Formatter\ColoredLineFormatter;

return function ($name = 'cli') {
    $fn = function() use ($name) {
        $log = new Logger($name);

        $handler = new StreamHandler('php://stderr', $this->getConfig()->debug ? Logger::DEBUG : Logger::INFO );
        $handler->setFormatter(new ColoredLineFormatter());
        $log->pushHandler($handler);
        return $log;
    };
    return $fn;
};