#!/usr/bin/env sh

set -e

cd /php && composer du > /dev/null 2>/dev/null;
cd /app && exec php cli.php worker $1 $2 $3
