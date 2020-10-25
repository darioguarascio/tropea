

#FROM varnish:6.5





FROM    alpine:edge

ARG     VARNISH_VERSION

RUN     set -x && \
        apk add --no-cache --upgrade varnish=${VARNISH_VERSION} && \
        rm -rf /tmp/* /var/cache/apk/*

COPY 	./.varnish.* /
COPY    ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint



#HEALTHCHECK --interval=10s --timeout=3s \
#  CMD test -e /proc/`cat /varnish.pid` || exit 1


CMD     ["/usr/local/bin/docker-entrypoint"]