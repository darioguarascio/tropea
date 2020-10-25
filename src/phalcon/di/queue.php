<?php

Resque::setBackend( getenv('WORKER_QUEUE_REDIS_ADDRESS') );
