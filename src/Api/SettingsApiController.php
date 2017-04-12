<?php

namespace Saritasa\PushNotifications\Api;

use Dingo\Api\Http\Request;
use Saritasa\DingoApi\BaseApiController;
use Saritasa\Laravel\Notifications\Services\NotificationSettingsService;

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

    public function setNotificationSettings(Request $request)
    {
        $input = $request->input();
        // TODO: validate input format here
        $this->notificationsSettings->update($input);
        return $this->getNotificationSettings();
    }
}