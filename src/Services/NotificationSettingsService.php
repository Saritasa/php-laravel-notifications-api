<?php

namespace Saritasa\PushNotifications\Services;

use Saritasa\DingoApi\Traits\CurrentApiUserTrait;
use Saritasa\PushNotifications\Models\NotificationSetting;
use Saritasa\PushNotifications\Models\NotificationType;
use Saritasa\PushNotifications\Responses\NotificationSettingDTO;

class NotificationSettingsService
{
    use CurrentApiUserTrait;

    public function get()
    {
        $userSettings = NotificationSetting::whereUserId($this->user()->id)->get()
            ->keyBy('notification_type_id');
        $notificationTypes = NotificationType::all();
        $result = $notificationTypes->transform(function (NotificationType $type) use ($userSettings) {
            $userValue = $userSettings->get($type->id);
            return new NotificationSettingDTO($userValue, $type);
        });
        return $result->values();
    }

    public function update(array $input)
    {
        $newSettings = collect($input)->keyBy('id');
        $existingSettings = NotificationSetting::whereUserId($this->user()->id)->get()
            ->keyBy(NotificationSetting::NOTIFICATION_TYPE_ID);

        $toUpdate = $existingSettings->intersectByKeys($newSettings);
        $toUpdate->each(function (NotificationSetting $setting) use ($newSettings) {
            $newValue = $newSettings->get($setting->notification_type_id)[NotificationSetting::IS_ON];
            if ($setting->is_on != $newValue) {
                $setting->is_on = $newValue;
                $setting->save();
            }
        });

        $toCreate = $newSettings->except($existingSettings->keys());
        $toCreate->each(function (array $input, $notificationTypeId) {
            NotificationSetting::create([
                NotificationSetting::USER_ID => $this->user()->id,
                NotificationSetting::NOTIFICATION_TYPE_ID => $notificationTypeId,
                NotificationSetting::IS_ON => $input[NotificationSetting::IS_ON],
            ]);
        });
    }

    public function isOn($user, int $settingTypeId): bool
    {
        $value = NotificationSetting::whereUserId($user->id)
            ->where(NotificationSetting::NOTIFICATION_TYPE_ID, $settingTypeId)
            ->first('is_on');
        return $value ? $value->is_on : NotificationType::find($settingTypeId)->default_on;
    }
}
