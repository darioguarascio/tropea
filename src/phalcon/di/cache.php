<?php
return function () {
    $c = conf()->redis->cache;
    return new Phalcon\Cache\Backend\Redis(
        new Phalcon\Cache\Frontend\Data([
            "lifetime" => isset($c->expire) ? $c->expire : null
        ]),
        [
            "host"       => $c->host,
            "port"       => $c->port,
            "persistent" => false,
            "index"      => $c->db,
            'prefix'     => sprintf(':%s:',$c->version)
        ]
    );
};