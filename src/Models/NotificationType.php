<?php

namespace Saritasa\PushNotifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Notification type
 *
 * @property integer $id
 * @property string $name
 * @property boolean $default_on
 * @method static Builder|NotificationType whereDefaultOn($value)
 * @method static Builder|NotificationType whereId($value)
 * @method static Builder|NotificationType whereName($value)
 * @mixin \Eloquent
 */
class NotificationType extends Model
{
    protected $casts = [
        'default_on' => 'boolean'
    ];
}
