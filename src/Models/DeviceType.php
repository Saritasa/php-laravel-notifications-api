<?php

namespace Saritasa\PushNotifications\Models;

use Saritasa\Enum;

/**
 * Device type: Android or iOS
 */
class DeviceType extends Enum
{
    /**
     * iOS: iPhone or iPad
     *
     * @var string
     */
    const IOS = 'ios';

    /**
     * Android phone or tablete
     *
     * @var string
     */
    const ANDROID = 'android';
}
