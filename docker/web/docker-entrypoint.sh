#!/usr/bin/env sh

set -e

echo $0 $1 $2
cd /php && composer du

/usr/local/bin/apache2-foreground