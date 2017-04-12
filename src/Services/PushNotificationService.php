<?php

namespace Saritasa\PushNotifications\Services;

/**
 * Abstract class PushNotificationService
 *
 * @package App\Model\Services\PushServices
 */
abstract class PushNotificationService implements PushNotificationServiceInterface, LogInterface
{
    use LogTrait;
}
