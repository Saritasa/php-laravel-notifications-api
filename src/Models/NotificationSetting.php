<?php

namespace Saritasa\PushNotifications\Models;

use Carbon\Carbon;
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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read NotificationType $notificationType
 *
 * @method static Builder|NotificationSetting whereId($value)
 * @method static Builder|NotificationSetting whereIsOn($value)
 * @method static Builder|NotificationSetting whereNotificationTypeId($value)
 * @method static Builder|NotificationSetting whereUserId($value)
 * @mixin \Eloquent
 */
class NotificationSetting extends Model
{
    protected $table = 'NotificationSettings';

    public const ID = 'id';
    public const USER_ID = 'userId';
    public const NOTIFICATION_TYPE_ID = 'notificationTypeId';
    public const IS_ON = 'isOn';

    protected $casts = [
        self::IS_ON => 'boolean',
    ];

    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    protected $fillable = [ self::USER_ID, self::NOTIFICATION_TYPE_ID, self::IS_ON];

    public function notificationType(): BelongsTo
    {
        return $this->belongsTo(NotificationType::class);
    }
}
