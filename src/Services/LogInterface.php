<?php

namespace Saritasa\PushNotifications\Services;

/**
 * Interface ILogInterface is used for implementation logging messages.
 *
 * @package App\Model\Services\PushServices
 */
interface LogInterface
{
    /**
     * Log type error.
     * @var string
     */
    const LOG_TYPE_ERROR = 'ERROR';

    /**
     * Log type info.
     * @var string
     */
    const LOG_TYPE_INFO = 'INFO';
}
