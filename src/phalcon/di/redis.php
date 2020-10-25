<?php

return function(string $type, bool $newIstance = false) {
    $fn = function($t = null) use ($type) {
        $type = is_null($t) ? $type: $t;
        $config = $this->getConfig()->redis->{$type};
        $redis = new Redis;
        $redis->connect($config->host, $config->port);
        if (isset($config->prefix)) {
            $redis->setOption(Redis::OPT_PREFIX, $config->prefix.':');
        }
        if (isset($config->db)) {
            $redis->select($config->db);
        }
        return $redis;
    };
    if ($newIstance) {
        return $fn;
    }
    return $fn($type);
};