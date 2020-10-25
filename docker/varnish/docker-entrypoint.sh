#!/usr/bin/env sh

set -e

source /.varnish.$ENV

generate_vcl() {

    cat << EOT
vcl 4.0;

import std;
import directors;

probe elastic {
   .request = "GET /robots.txt HTTP/1.0"
              "Connection: close"
              "Accept-Encoding: text/html";
   .interval = 30s;
   .timeout = 25s;
   .window = 1;
   .threshold = 1;
   .expected_response = 200;
}

EOT

    IFS=','
    for ip in $VARNISH_BACKENDS ; do
        echo $ip | tr ':' ',' | {
          read -r name ip port;
          echo "
backend search_es_$name {
    .host = \"$ip\"; # $u
    .port = \"$port\";
    .connect_timeout = ${BACKEND_CONNECTION_TIMEOUT}s;
    .first_byte_timeout = ${BACKEND_FIRST_BYTE_TIMEOUT}s;
    .between_bytes_timeout = ${BACKEND_BETWEEN_BYTES_TIMEOUT}s;
    .probe = elastic;
}";
        }
    done


    echo "
sub vcl_init {
    new search_es = directors.round_robin();";

    for ip in $VARNISH_BACKENDS ; do
        echo $ip | tr ':' ',' | {
          read -r name ip port;
          echo "
    search_es.add_backend(search_es_${name});"
        }
    done

    echo "}"
}


generate_vcl > ${VARNISHD_VCL_PATH}

IFS=' '

# in background
# -S /etc/varnish/secret
# -p http_max_hdr=128 -p vsl_reclen=4084 -p http_resp_hdr_len=65536 -p http_resp_size=98304 -p workspace_backend=131072
$(command -v varnishd) -a :80 -T localhost:6082 -f ${VARNISHD_VCL_PATH} -s ${VARNISHD_MEMORY} -P /varnish.pid

# -D - daemoinize
sh -c '/usr/bin/varnishncsa -b -c -a -F '"'"'{"x": "'"'${ENV}'"'", "@timestamp":"%{%Y-%m-%dT%H:%M:%S%z}t", "frontend": "%{X-Backend}o", "vside": "%{Varnish:side}x", "remoteip":"%h","xforwardedfor":"%{X-Forwarded-For}i","method":"%m","url":"%{Host}i%U", "qs": "%q", "httpversion":"%H","status": %s,"bytes": %b, "ref":"%{Referer}i","ua":"%{User-agent}i", "clen": %{Content-Length}o, "bexecms": %{Varnish:time_firstbyte}x,"duration_usec":%D,"cache":"%{Varnish:handling}x","cf_ip": "%{CF-Connecting-IP}i", "cf_c": "%{CF-IPCountry}i", "metrics": "%{X-Metrics}o", "x_grace": %{X-Grace}o, "x_age": %{Age}o } '"'"' | sed -e '"'"'s/"\(bytes\|status\|clen\|duration_usec\|x_age\|x_grace\)": -/"\1": 0/g'"'"' '


