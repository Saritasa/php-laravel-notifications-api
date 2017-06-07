<?php

namespace Saritasa\PushNotifications\Api;

use Dingo\Api\Http\Request;
use Saritasa\DingoApi\Traits\CurrentApiUserTrait;
use Saritasa\Laravel\Controllers\Api\BaseApiController;
use Saritasa\PushNotifications\NotificationTransformer;

class NotificationsApiController extends BaseApiController
{
    use CurrentApiUserTrait;

    public function getUserNotifications()
    {
        return $this->json($this->user()->unreadNotifications, new NotificationTransformer());
    }

    public function markNotificationsAsViewed(Request $request)
    {
        $ids = $request->get('notification_ids');
        $notifications = $this->user()->unreadNotifications->whereIn('id', $ids);
        $notifications->markAsRead();
    }
}
