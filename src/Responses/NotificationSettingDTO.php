<?php

namespace Saritasa\PushNotifications\Responses;

use Saritasa\PushNotifications\Models\NotificationSetting;
use Saritasa\PushNotifications\Models\NotificationType;
use Saritasa\Transformers\DtoModel;

class NotificationSettingDTO extends DtoModel
{
    protected $id;
    protected $name;
    protected $isOn;

    protected static $collectionKey = 'settings';

    public function __construct(?NotificationSetting $userSetting, NotificationType $notificationType)
    {
        $this->id = $notificationType->id;
        $this->name = $notificationType->name;
        $this->isOn = $userSetting ? $userSetting->is_on : $notificationType->default_on;
    }
}
