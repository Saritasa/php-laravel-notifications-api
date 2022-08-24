<?php

namespace Saritasa\PushNotifications\Api;

use Saritasa\PushNotifications\Requests\NotificationRequest;
use Dingo\Api\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Saritasa\DingoApi\Traits\CurrentApiUserTrait;
use Saritasa\LaravelControllers\Api\BaseApiController;
use Saritasa\PushNotifications\NotificationTransformer;
use Saritasa\PushNotifications\Events\NotificationRead;

class NotificationsApiController extends BaseApiController
{
    use CurrentApiUserTrait;

    public function __construct(NotificationTransformer $transformer) //phpcs:ignore
    {
        parent::__construct($transformer);
    }

    public function getUserNotifications(NotificationRequest $request)
    {
        if (is_null($request->type) || $request->type === '') {
            $responseData = $this->user->unreadNotifications();
        } else {
            $responseData = $this->user->unreadNotifications()
                ->where('type', $request->type);
        }
        return $this->response->paginator(
            $responseData->paginate($request->per_page),
            $this->transformer
        );
    }

    public function markNotificationsAsViewed(Request $request)
    {
        $ids = $request->get('notification_ids');
        $notifications = $this->user()->unreadNotifications()->whereIn('id', $ids)->get();
        $notifications->each(function (DatabaseNotification $notification) {
            $notification->markAsRead();
            event(new NotificationRead($this->user()->id, $notification->id));
        });
    }

    public function deleteNotifications(Request $request)
    {
        $ids = $request->get('notification_ids', []);
        $this->user()->notifications()->whereIn('id', $ids)->delete();

        return $this->response->noContent();
    }
}
