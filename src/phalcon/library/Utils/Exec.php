<?php namespace Utils;

class Exec {

    /**
     * Helper function to execute shell commands
     *
     * @return string | bool
     */    
    public static function cmd($cmd, $wd = null, $timeout = 60, callable $cb = null) {
        $descriptorspec = array(
           0 => array("pipe", "r"),
           1 => array("pipe", "w"),
           2 => array("pipe", "w")
        );
        $pipes = array();

        $endtime = time()+$timeout;
        
        $process = proc_open($cmd, $descriptorspec, $pipes, $wd);

        $output = '';
        if (is_resource($process)) {
            do {
                $timeleft = $endtime - time();
                $read = array($pipes[1]);
                $exeptions = NULL;
                $write = NULL;
                if (false === stream_select($read, $write, $exeptions, $timeleft, NULL) ) {
                    return false;
                }
                if (!empty($read)) {
                    $chunk = trim(fgets($pipes[1], 8192));
                    if (is_callable($cb) && strlen($chunk) > 0) {
                        $cb($chunk);
                    }
                    $output .= $chunk;
                }
            } while(!feof($pipes[1]) && $timeleft > 0);
            if ($timeleft <= 0) {
                proc_terminate($process);
                return false;
            } else {
                return $output;
            }
        } else {
            return false;
        }
    }

}