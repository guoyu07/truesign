<?php


namespace Royal\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;

class Logger {

    /**
     * @var \Monolog\Logger
     */
    static $logger = null;

    static function getLogger ($level='') {
        if (!static::$logger) {
//            $logPath = APPLICATION_PATH.'/logs';
            $logPath = CURRECT_APPLICATION_PATH.'/logs';
            if (php_sapi_name() != 'cli') {
                if($level == 'CODELOGIC'){
                    $fileName = 'web_code_logic';
                    $formatter = new LineFormatter("\n[%datetime%]\n[%level_name%][%extra.ip%][%extra.class%:%extra.line%]: %message% %context% %extra.url%\nrequest_source=> \n%extra.request_source%\n", 'H:i:s', true, true);

                }
                else{
                    $fileName = 'web';
                    $formatter = new LineFormatter("[%datetime%][%level_name%][%extra.ip%][%extra.class%:%extra.line%]: %message% %context% %extra.url%\n", 'H:i:s', true, true);
                }
            } else {
                if($level == 'CODELOGIC'){
                    $fileName = 'cli_code_logic';
                    $formatter = new LineFormatter("[%datetime%][%level_name%][%extra.class%:%extra.line%]: %message% %context% %extra.url%\n%extra.request_source%\n", 'H:i:s', true, true);
                }
                else{
                    $fileName = 'cli';
                    $formatter = new LineFormatter("[%datetime%][%level_name%][%extra.class%:%extra.line%]: %message% %context%\n", 'H:i:s', true, true);
                }
            }

            $conf = \Yaf_Registry::get('config');
            $logger = new \Monolog\Logger('log');
            $logger->pushProcessor(new IProcessor());

            $criticalHandler = new FileHandler(sprintf('%s/%s_critical.log', $logPath, $fileName),\Monolog\Logger::CRITICAL);
            $logger->pushHandler($criticalHandler);


            $errorHandler = new FileHandler(sprintf('%s/%s_error.log', $logPath, $fileName),  \Monolog\Logger::ERROR);
            $logger->pushHandler($errorHandler);

            $normalHandler = new FileHandler(sprintf('%s/%s.log', $logPath, $fileName), $conf->application->logger->level);
            $logger->pushHandler($normalHandler);

            $codelogicHandler = new FileHandler(sprintf('%s/%s.log', $logPath, 'code_logic'), \Monolog\Logger::CODELOGIC);
            $logger->pushHandler($codelogicHandler);

            $alertHandler = new FileHandler(sprintf('%s/%s_timeout.log', $logPath, $fileName),  \Monolog\Logger::ALERT, false);
            $logger->pushHandler($alertHandler);

            foreach ($logger->getHandlers() as $handler) {
                $handler->setFormatter($formatter);
            }

            static::$logger = $logger;
        }

        return static::$logger;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    static function emergency($message, array $context = array()) {
        static::getLogger()->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    static function alert($message, array $context = array()) {
        static::getLogger()->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    static function critical($message, array $context = array()) {
        static::getLogger()->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    static function error($message, array $context = array()) {
        static::getLogger()->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    static function warning($message, array $context = array()) {
        static::getLogger()->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    static function notice($message, array $context = array()) {
        static::getLogger()->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    static function info($message, array $context = array()) {

        static::getLogger()->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    static function debug($message, array $context = array()) {
        static::getLogger()->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    static function log($level, $message, array $context = array()) {
        static::getLogger($level)->log($level, $message, $context);
    }
}