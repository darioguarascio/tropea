<?php

return function ($exception = null) {
    // http_response_code(500);
    return function($exception) {
        // Logging exception
        try {
            redis('local')->lpush('debug', (string) $exception);
        } catch (\Exception $e) {

        }

        if (php_sapi_name() == 'cli' || conf()->debug) {
            if (function_exists('dump')) {
                dump($exception);
            } else {
                var_dump($exception);
            }
        } else {
            $controller = new \ErrorController;
            $controller->beforeExecuteRoute($this->getDispatcher());
            $controller->http500Action();
            $controller->afterExecuteRoute($this->getDispatcher());
        }
    };
}
;

// try {
//     // if (isset($_SERVER['DEBUG_CARRIER_K'])) {
//     //     app()->getRedisSearch(\Search\Search::getConfig('TYPE_LIVE'))->del(
//     //         $_SERVER['DEBUG_CARRIER_K']
//     //     );
//     // }
//     // app()->getRedisLocal()->rpush('queue:background',json_encode(array(
//     //     'task' => 'errorNotification',
//     //     'data' => array(
//     //         'url' => sprintf("%s%s", @$_SERVER['HTTP_HOST'], @$_SERVER['REQUEST_URI']),
//     //         'file' => str_replace('/home/virail/','',$exception->getFile()),
//     //         'line' => $exception->getLine(),
//     //         'errorMessage' => $exception->getMessage(),
//     //         'trace' => $exception->getTraceAsString(),
//     //         '_SERVER' => json_encode($_SERVER,JSON_PRETTY_PRINT)
//     //     )
//     // )));
// } catch (\Exception $e) {

// }
