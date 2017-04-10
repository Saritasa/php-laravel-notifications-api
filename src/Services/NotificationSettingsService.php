<?php

namespace App\Services;

use App\Api\V1\Responses\NotificationSettingDTO;
use App\Extensions\CurrentApiUserTrait;
use App\Models\NotificationSetting;
use App\Models\NotificationType;
use App\Repositories\Base\CachingRepository;
use App\Repositories\NotificationSettingsRepository;
use App\Repositories\NotificationTypesRepository;
use App\Repositories\PreferenceRepository;

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