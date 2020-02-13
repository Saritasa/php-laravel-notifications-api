<?php

namespace Saritasa\PushNotifications\Models;

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
    public const ID = 'id';
    public const USER_ID = 'user_id';
    public const NOTIFICATION_TYPE_ID = 'notification_type_id';
    public const IS_ON = 'is_on';

    public $timestamps = false;

    protected $casts = [
        'is_on' => 'boolean'
    ];

    protected $fillable = [ 'user_id', 'notification_type_id', 'is_on'];

    public function notificationType(): BelongsTo
    {
        return $this->belongsTo(NotificationType::class);
    }
}
