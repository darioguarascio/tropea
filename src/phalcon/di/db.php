<?php
return function () {
    $config = $this->getConfig()->db;

    $connection = new Phalcon\Db\Adapter\Pdo\Postgresql([
        'host'     => $config->host,
        'port'     => isset($config->port) ? $config->port : 5432,
        'username' => $config->user,
        'password' => $config->pass,
        // 'dbname'   => $config->database->dbname,
        // 'charset'  => $config->database->charset
    ]);

    return $connection;
};