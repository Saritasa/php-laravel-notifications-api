<?php

namespace Saritasa\Laravel\Notifications\Services;

use App\Extensions\CurrentApiUserTrait;
use Saritasa\Laravel\Notifications\Models\NotificationSetting;
use Saritasa\Laravel\Notifications\Models\NotificationType;

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