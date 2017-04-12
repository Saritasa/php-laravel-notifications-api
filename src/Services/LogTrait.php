<?php

namespace Saritasa\PushNotifications\Services;

/**
 * Trait LogTrait provide methods for logging message.
 *
 * @package App\Model\Services\PushServices
 */
trait LogTrait
{
    /**
     * File path of the log;
     * @var string
     */
    protected $logFile = '';

    /**
     * Indicate if service in debug mode or not.
     * @var bool
     */
    protected $isDebug;

    /**
     * Log message with given type.
     *
     * @param string $message
     * @param string $type
     * @return void
     * @access protected
     */
    protected function log($message, $type = self::LOG_TYPE_INFO)
    {
        if ($this->logFile) {
            @file_put_contents(
                $this->logFile,
                "\r\n[" . date("Y-m-d H:i:s") . "] local.$type: [" . static::class . "]" . $message,
                FILE_APPEND
            );
        }
    }

    /**
     * Log error message.
     *
     * @param $message
     * @return void
     * @access protected
     */
    protected function error($message)
    {
        if ($this->logFile) {
            $this->log($message, self::LOG_TYPE_ERROR);
        }
    }

    /**
     * Log info message.
     *
     * @param $message
     * @return void
     * @access protected
     */
    protected function info($message)
    {
        if ($this->logFile && $this->isDebug) {
            $this->log($message, self::LOG_TYPE_INFO);
        }
    }
}
