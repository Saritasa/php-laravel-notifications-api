<?php

namespace Saritasa\PushNotifications\Services;

use App\Extensions\CurrentApiUserTrait;
use Saritasa\PushNotifications\Models\NotificationSetting;
use Saritasa\PushNotifications\Models\NotificationType;
use Saritasa\PushNotifications\Responses\NotificationSettingDTO;

class NotificationSettingsService
{
    use CurrentApiUserTrait;

    public function get()
    {
        $userSettings = NotificationSetting::whereUserId($this->user()->id)
            ->keyBy('notification_type_id');
        $notificationTypes = NotificationType::all();
        $result = $notificationTypes->transform(function(NotificationType $type) use ($userSettings) {
            $userValue = $userSettings->get($type->id);
            return new NotificationSettingDTO($userValue, $type);
        });
        return $result->values();
    }

    public function update(array $input)
    {

    }
}