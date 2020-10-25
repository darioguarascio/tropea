<?php

$config = new \Phalcon\Config;
foreach ([ 'app', ENV ] as $conf) {
    $conf = sprintf('%s/config/%s.yml', APP_PATH, $conf);
    if (file_exists($conf)) {
        $config->merge(new Phalcon\Config\Adapter\Yaml($conf));
    }
}

return $config;