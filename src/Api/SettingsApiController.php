<?php

namespace Saritasa\PushNotifications\Api;

use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Illuminate\Validation\Rule;
use Saritasa\LaravelControllers\Api\BaseApiController;
use Saritasa\PushNotifications\Services\NotificationSettingsService;
use Saritasa\PushNotifications\Models\DeviceType as DeviceTypes;
use Saritasa\PushNotifications\Models\UserDevice;
use Saritasa\PushNotifications\Requests\UdpateNotificationsSettingsRequest;

class SettingsApiController extends BaseApiController
{
    /**
     * @var NotificationSettingsService
     */
    private $notificationsSettings;

    public function __construct(NotificationSettingsService $notificationsSettings)
    {
        parent::__construct();
        $this->notificationsSettings = $notificationsSettings;
    }

    public function getNotificationSettings()
    {
        return $this->json($this->notificationsSettings->get());
    }

    public function setNotificationSettings(UdpateNotificationsSettingsRequest $request)
    {
        $input = $request->validated();
        $this->notificationsSettings->update($input);
        return $this->getNotificationSettings();
    }

    /**
     * Save user device token
     *
     * @param Request $request
     * @return Response
     */
    public function saveUserDevice(Request $request)
    {
        $this->validate($request, [
            UserDevice::DEVICE_ID => 'string|required',
            UserDevice::DEVICE_TYPE => Rule::in(DeviceTypes::getConstants())
        ]);

        $deviceToken = $request->get('deviceId');
        $deviceType = $request->get('deviceType');

        // check token exists first
        $userDevice = UserDevice::findByDeviceToken($deviceToken);

        // remove current device token kept by other users
        if ($userDevice && $this->user->id != $userDevice->id) {
            $userDevice->delete();
            $userDevice = null;
        }
        if (!$userDevice) {
            UserDevice::create([
                UserDevice::DEVICE_TYPE => $deviceType,
                UserDevice::DEVICE_ID => $deviceToken,
                'userId' => $this->user->id
            ]);
        }

        return $this->response->created();
    }

    /**
     * Delete device token.
     *
     * @param string $deviceToken
     * @return bool|null
     */
    public function deleteUserDevice($deviceToken)
    {
        return UserDevice::deleteByDeviceToken($deviceToken);
    }
}
