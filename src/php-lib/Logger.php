<?php

use Monolog\Logger as Monlogger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RedisHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;

#use gh_rboliveira\TelegramHandler\TelegramHandler;
use pahanini\Monolog\Formatter\CliFormatter;


class Logger extends \Phalcon\Di\Injectable {

    private $logger, $logsConfig;

    protected function getConfig() : Phalcon\Config {
        return new Phalcon\Config([
            'redisHost'         => getEnv('LOGGER_REDIS_HOST'),
            'redisPort'         => getEnv('LOGGER_REDIS_PORT'),
            'redisDb'           => getEnv('LOGGER_REDIS_DB'),
            'redisKey'          => getEnv('LOGGER_REDIS_KEY'),
            'stdoutLogLevel'    => Monlogger::toMonologLevel( getEnv('LOGGER_LEVEL_STDOUT') ),
            'redisLogLevel'     => Monlogger::toMonologLevel( getEnv('LOGGER_LEVEL_REDIS') ),
            'syslogLogLevel'    => Monlogger::toMonologLevel( getEnv('LOGGER_LEVEL_SYSLOG') ),
            'stdoutCliFormat'   => getEnv('LOGGER_STDOUT_CLI_FORMAT'),
        ]);
    }


    public function __construct(
        string $loggingChannel  = null,
        string $workerName      = null,
        string $stdOut          = 'php://stdout',
        array  $handlers        = array()
    ) {
        $this->logsConfig       = $this->getConfig();


        $this->workerName       = !is_null($workerName) ?
                                    $workerName: 
                                    sprintf("%s:%s:%s",
                                        strtolower(get_called_class()),
                                        gethostname(),
                                        uniqid() );


        $loggingChannel = !is_null($loggingChannel) ? $loggingChannel : getenv('ENV');
        $this->logger = new Monlogger($loggingChannel);

        $messages = array();

        $syslog = $this->getSyslogHandler();
        $telegram = $this->getTelegramHandler();
        $slack = $this->getSlackWebhookHandler();

        # In case of frontend env, logs are sent to a local redis instance, then forwarded by a background process
        if ($loggingChannel == 'frontend') {

            $stdout = null; //new NullHandler;

            # INFO+ logs to local redis
            // $redis = new \Redis;
            // $redis->pconnect($this->di->getConfig()->logs->redisHost, $this->di->getConfig()->logs->redisPort);
            // $redis->select(isset($config->db) ? $config->db : 0);

            try {
                $redis = $this->getLocalRedisHandler();
                $slack    = null;//new NullHandler;
                $telegram = null;//new NullHandler;
            } catch (\Exception $exception){
                $redis = null;//new NullHandler;
                $messages[] = [
                    Monlogger::ERROR,
                    $exception->getMessage(),
                    [
                        'exception' => $exception,
                        'file'  => $exception->getFile(),
                        'line'  => $exception->getLine(),
                        'trace' => $exception->getTraceAsString(),
                    ]
                ];
            }

            $this->logger->pushProcessor(function($record) {
                return $this->frontendProcessor($record);
            });
            $this->logger->pushProcessor(new WebProcessor);
            $this->logger->pushProcessor(new MemoryUsageProcessor);

        }
        else {
            # Default logger to stdout for non-frontend loggers
            $stdout = new StreamHandler($stdOut, $this->logsConfig->stdoutLogLevel);
            if (isset($_SERVER['JSON'])) {
                $stdout->setFormatter(new JsonFormatter);
            } elseif (isset($_SERVER['CLI'])) {
                $stdout->setFormatter(new CliFormatter);
            } else {
                if ($this->logsConfig->cliFormatter) {
                    $stdout->setFormatter(new CliFormatter);
                } else {
                    $stdout->setFormatter(new LineFormatter($this->logsConfig->stdoutCliFormat));
                }
            }

            # INFO+ logs to redis (remote, ingest instance) and ultimately to kibana
            try {
                $redis = $this->getPreKibanaRedisHandler();

            } catch (\Exception $exception) {
                $redis = null;//new NullHandler;

                $messages[] = [
                    Monlogger::ERROR,
                    $exception->getMessage(),
                    [
                        'exception' => $exception,
                        'file'  => $exception->getFile(),
                        'line'  => $exception->getLine(),
                        'trace' => $exception->getTraceAsString(),
                    ]
                ];
            }


            # Processor
            $this->logger->pushProcessor(function ($record) {
                return $this->backendProcessor($record);
            });
        }

        # Set only active handlers / or handlers passed in input
        $handlers = !empty($handlers) ? $handlers : array_filter([
            $syslog,
            $stdout,
            $slack,
            $redis,
            $telegram
        ]);
        $this->logger->setHandlers($handlers);

        # Register as handler
        ErrorHandler::register($this->logger);

        # If there were exceptions during logger creation
        foreach ($messages as $m) {
            call_user_func_array([$this->logger,'log'], $m);
        }
    }

    /**
     * Helper function to optimize record form
     *
     * @return array
     */
    private function frontendProcessor(array $record) : array {
        $record['@timestamp'] = $record['datetime']->format('Y-m-d\TH:i:s.uP');
        $record['extra'] = array_merge(isset($record['extra']) && is_array($record['extra']) ? $record['extra'] : array(), array(
            'frontend'  => gethostname(),
            'v'         => VERSION
        ));
        return $record;
    }


    /**
     * Helper function to optimize record form
     *
     * @return array
     */
    private function backendProcessor(array $record) : array {
        $record['@timestamp'] = $record['datetime']->format('Y-m-d\TH:i:s.uP');
        $record['extra'] = array_merge($record['extra'], array(
            'host'     => gethostname(),
            'v'        => VERSION,
            'workerId' => $this->workerName,
        ));
        return $record;
    }

    /**
     * @return Monolog\Logger
     */
    public function getLogger() : \Monolog\Logger {
        return $this->logger;
    }

    /**
     * @return SyslogHandler
     */
    private function getSyslogHandler() : SyslogHandler {
        $syslog = new SyslogHandler(gethostname(), LOG_USER, $this->logsConfig->syslogLogLevel);
        $syslog->setFormatter(new LineFormatter("%channel%.%level_name%: %message% %context% %extra%"));
        return $syslog;
    }

    /**
     * @return TelegramHandler
     */
    private function getTelegramHandler() : ?TelegramHandler {
        return null;
        # CRITICAL+ to telegram
        $telegram = new TelegramHandler($this->logsConfig->telegramLogLevel);
        $telegram->setBotToken( $this->di->getConfig()->application->telegram->viraildisasterwarningsystem->apiToken );
        $telegram->setRecipients([
            $this->di->getConfig()->application->telegram->viraildisasterwarningsystem->channelId
        ]);
        return $telegram;
    }


    /**
     * @return SlackWebhookHandler
     */
    private function getSlackWebhookHandler() : ?SlackWebhookHandler {
        return null;
        return new SlackWebhookHandler(
            $token              = $this->logsConfig->slackWebHook,
            $channel            = $this->logsConfig->slackDebugChannel,
            $username           = $this->workerName,
            $useAttachment      = true,
            $iconEmoji          = isset($_SERVER['SLACK_ICON']) ? $_SERVER['SLACK_ICON'] : null,
            $useShortAttachment = true,
            $incContextAndExtra = isset($_SERVER['SLACK_SIMPLE']) ? false : true,
            $level              = $this->logsConfig->slackLogLevel
        );
    }


    /**
     * @return RedisHandler
     */
    private function getPreKibanaRedisHandler() : RedisHandler {
        $redis = new \Redis;
        $redis->pconnect($this->logsConfig->redisHost, $this->logsConfig->redisPort);
        if (isset($this->logsConfig->redisAuth) && !is_null($this->logsConfig->redisAuth)) {
            if (!$redis->auth($this->logsConfig->redisAuth) ) {
                throw new Exception("Redis auth failed");
            }
        }
        if (isset($this->logsConfig->redisDb) && $this->logsConfig->redisDb > 0) {
            $redis->select($this->logsConfig->redisDb);
        }
        // throw new Exception("Redis auth failed");
        $redis = new RedisHandler($redis, $this->logsConfig->redisKey, $this->logsConfig->redisLogLevel);
        $redis->setFormatter(new JsonFormatter);
        return $redis;
    }


    /**
     * @return RedisHandler
     */
    private function getLocalRedisHandler() : RedisHandler {
        $redis = new \Redis;
        $config = $this->di->getConfig()->redis->local;
        $redis->pconnect($config->host, $config->port, $config->timeout, $config->label);
        $redis->select($config->db);

        $redis = new RedisHandler($redis, $this->logsConfig->redisKey.':0', $this->logsConfig->redisLogLevel);
        $redis->setFormatter(new JsonFormatter);
        return $redis;
    }


}
