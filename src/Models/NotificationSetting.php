<?php

namespace Saritasa\Laravel\Notifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * Notification setting
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $notification_type_id
 * @property boolean $is_on
 * @property-read NotificationType $notificationType
 * @method static Builder|NotificationSetting whereId($value)
 * @method static Builder|NotificationSetting whereIsOn($value)
 * @method static Builder|NotificationSetting whereNotificationTypeId($value)
 * @method static Builder|NotificationSetting whereUserId($value)
 * @mixin \Eloquent
 */
class NotificationSetting extends Model
{
    protected $casts = [
        'is_on' => 'boolean'
    ];

    public function notificationType(): BelongsTo
    {
        return $this->belongsTo(NotificationType::class);
    }
}
