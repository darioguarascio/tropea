<?php

use Phalcon\Cli\Task;

use Illuminate\Support\Collection;

class WorkerTask extends Task {


    /**
     * Load entries in a queue. Proof of concept
     *
     * @see `docker-compose run worker docker-entrypoint load 150`
     */
    public function loadAction(array $params = array()) {
        Resque::setBackend( getenv('WORKER_QUEUE_REDIS_ADDRESS') );
        Resque::dequeue('test');

        for ($i = 0; $i < $params[0]; $i++) {
            Resque::enqueue('pg', 'Import', array( 'i' => $i, 'rnd' => rand() ));
            echo '.';
        }
        echo "\n";
    }


    /**
     * Run a worker on queued data
     *
     * @see `docker-compose run worker`
     * @see `docker-compose up --build --scale worker=3 worker`
     */
    public function mainAction(array $params = array()) {
        Resque::setBackend( getenv('WORKER_QUEUE_REDIS_ADDRESS') );

        $interval = 1;
        $BLOCKING = false;
        $logger = new Monolog\Logger('worker', [
            new Monolog\Handler\StreamHandler('php://stdout', Psr\Log\LogLevel::WARNING)
        ]);

        $worker = new Resque_Worker(['*']);
        $worker->setLogger($logger);

        $PIDFILE = getenv('PIDFILE');
        if ($PIDFILE) {
            file_put_contents($PIDFILE, getmypid()) or
                die('Could not write PID information to ' . $PIDFILE);
        }

        $logger->log(Psr\Log\LogLevel::NOTICE, 'Starting worker {worker}', array('worker' => $worker));
        $worker->work($interval, $BLOCKING);
    }




    /**
     * Helper function to execute shell commands
     *
     * @param  string   $cmd
     * @param  int      $timeout
     * @param  callable
     *
     * @return string | bool
     */
    public static function shellExec(string $cmd, int $timeout = 90, callable $cb = null) {
        $descriptorspec = array(
           0 => array("pipe", "r"),
           1 => array("pipe", "w"),
           2 => array("pipe", "w")
        );

        $pipes = array();
        $endtime = time()+$timeout;
        $process = proc_open($cmd, $descriptorspec, $pipes);
        $output = '';

        if (is_resource($process)) {
            do {
                $timeleft = $endtime - time();
                $read = array($pipes[1]);
                $exeptions = NULL;
                $write = NULL;
                stream_select($read, $write, $exeptions, $timeleft, NULL);
                if(!empty($read)) {
                    $chunk = fgets($pipes[1], 8192);
                    if (!is_null($cb)) {
                        $cb($chunk);
                    } else {
                        $output .= $chunk;
                    }
                }
            } while(!feof($pipes[1]) && $timeleft > 0);
            if ($timeleft <= 0) {
                proc_terminate($process);
                throw new Exception('Timeout ('.$timeout.'): ' . $cmd, 1);
            } else {
                return $output;
            }
        } else {
            throw new Exception("sys error", 1);
        }
    }

}
