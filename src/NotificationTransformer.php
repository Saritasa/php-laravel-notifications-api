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
            'created_at' => $notification->created_at->format(Carbon::ISO8601),
            'read_at' => $notification->read_at ? $notification->read_at->format(Carbon::ISO8601) : null,
        ];
    }
}
