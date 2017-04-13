<?php

namespace Saritasa\PushNotifications\Api;

use Saritasa\DingoApi\BaseApiController;
use Saritasa\PushNotifications\Models\Notification;
use Saritasa\Transformers\BaseTransformer;

class NotificationsApiController extends BaseApiController
{
    public function getUserNotifications()
    {
        return $this->json(new Notification(), new BaseTransformer());
    }

    public function markNotificationsAsViewed()
    {

    }
}