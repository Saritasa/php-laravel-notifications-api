<?php

namespace Saritasa\PushNotifications\Services;

/**
 * Interface IPushNotificationService
 *
 * @package App\Model\Services\PushServices
 */
interface PushNotificationServiceInterface
{
    /**
     * Send push notification to given device tokens or registration ids.
     *
     * @param mixed $to
     * @param string $message
     * @param array $options
     * @return bool
     */
    public function send($to, $message, $options = array());
}
