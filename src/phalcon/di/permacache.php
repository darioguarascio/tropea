<?php
return function () {
    $c = conf()->redis->permacache;
    return new Phalcon\Cache\Backend\Redis(
        new \Utils\CacheFrontendGzip([
            "lifetime" => -1, //$c->expire
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