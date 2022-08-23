<?php

namespace Saritasa\PushNotifications;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\DatabaseNotification;
use Saritasa\Transformers\BaseTransformer;

/**
 * Transform Laravel notification, stored in Database into API structure
 */
class NotificationTransformer extends BaseTransformer
{
    public function transform(Arrayable $model)
    {
        return $this->transformNotification($model);
    }

    public function transformNotification(DatabaseNotification $notification)
    {
        return [
            'id' => $notification->id,
            'data' => $notification->data,
            'type' => $notification->type,
            'created_at' => $notification->createdAt->format(Carbon::ISO8601),
            'read_at' => $notification->readAt ? $notification->readAt->format(Carbon::ISO8601) : null,
        ];
    }
}
