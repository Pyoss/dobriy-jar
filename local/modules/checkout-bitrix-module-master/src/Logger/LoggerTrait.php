<?php

namespace TinkoffCheckout\Logger;

use Carbon\Carbon;
use Psr\Log\InvalidArgumentException;
use Stringable;

/**
 * This is a simple Logger trait that classes unable to extend AbstractLogger
 * (because they extend another class, etc) can include.
 *
 * It simply delegates all log-level-specific methods to the `log` method to
 * reduce boilerplate code that a simple Logger that does the same thing with
 * messages regardless of the error level has to implement.
 */
trait LoggerTrait
{
    /**
     * System is unusable.
     *
     * @param $message
     * @param$context
     *
     * @return void
     */
    public function emergency($message, $context = [])
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param $message
     * @param$context
     *
     * @return void
     */
    public function alert($message, $context = [])
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param $message
     * @param$context
     *
     * @return void
     */
    public function critical($message, $context = [])
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param $message
     * @param$context
     *
     * @return void
     */
    public function error($message, $context = [])
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param $message
     * @param$context
     *
     * @return void
     */
    public function warning($message, $context = [])
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param $message
     * @param$context
     *
     * @return void
     */
    public function notice($message, $context = [])
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param $message
     * @param$context
     *
     * @return void
     */
    public function info($message, $context = [])
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param $message
     * @param$context
     *
     * @return void
     */
    public function debug($message, $context = [])
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    protected function getDatePrefix()
    {
        return date('[d.m.Y H:i:s] ');
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param $message
     * @param$context
     *
     * @return void
     */
    abstract public function log($level, $message, $context = []);
}