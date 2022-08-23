<?php

namespace Saritasa\PushNotifications\Api;

use Dingo\Api\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Saritasa\DingoApi\Traits\CurrentApiUserTrait;
use Saritasa\LaravelControllers\Api\BaseApiController;
use Saritasa\PushNotifications\NotificationTransformer;
use Saritasa\PushNotifications\Events\NotificationRead;

class NotificationsApiController extends BaseApiController
{
    use CurrentApiUserTrait;

    public function getUserNotifications()
    {
        return $this->json($this->user()->unreadNotifications()->paginate(), new NotificationTransformer());
    }

    public function markNotificationsAsViewed(Request $request)
    {
        $ids = $request->get('notificationIds');
        $notifications = $this->user()->unreadNotifications()->whereIn('id', $ids)->get();
        $notifications->each(function (DatabaseNotification $notification) {
            $notification->markAsRead();
            event(new NotificationRead($this->user()->id, $notification->id));
        });
    }

    public function deleteNotifications(Request $request)
    {
        $ids = $request->get('notificationIds', []);
        $this->user()->notifications()->whereIn('id', $ids)->delete();

        return $this->response->noContent();
    }
}
