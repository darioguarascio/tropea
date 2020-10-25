<?php

if ($config->debug) {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  set_error_handler(function($errno, $errstr, $errfile, $errline) {
      $errstr = "{$errstr}\n{$errfile}:{$errline}";
      switch ($errno) {
        case E_ERROR:
          debug($errstr, "ERROR");
          break;
        case E_WARNING:
          debug($errstr, "WARNING");
          break;
        case E_NOTICE:
          debug($errstr, "E_GENERIC");
          break;
        case E_USER_DEPRECATED:
          //debug($errstr, "DEPRECATED");
          break;
        default:
          debug($errstr, "E_GENERIC");
          break;
      }
  });
}


register_shutdown_function(function() use ($di) {
    $last_error = error_get_last();
    if ($last_error['type'] === E_ERROR) {
        di()->getError(
            new Exception($last_error['message'] ."\n". $last_error['file'] . ':' . $last_error['line'])
        );
    }
});



return $config;
