<?php

namespace Saritasa\PushNotifications\Models;

use Illuminate\Database\Query\Builder;
use Saritasa\Database\Eloquent\Entity;

/**
 * App\Model\Entities\Notification
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $subject
 * @property string $message
 * @property integer $notification_type_id
 * @property boolean $is_queued
 * @property boolean $is_viewed
 * @property \Carbon\Carbon $delivered_at
 * @property \Carbon\Carbon $created_at
 * @method static Builder|Notification whereId($value)
 * @method static Builder|Notification whereUserId($value)
 * @method static Builder|Notification whereSubject($value)
 * @method static Builder|Notification whereMessage($value)
 * @method static Builder|Notification whereIsQueued($value)
 * @method static Builder|Notification whereIsViewed($value)
 * @method static Builder|Notification whereNotificationTypeId($value)
 * @method static Builder|Notification whereDeliveredAt($value)
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon $updated_at
 * @property int            $badge
 * @property array          $data
 * @property mixed          $can_push
 */
class Notification extends Entity
{
    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'data',
        'notification_type_id',
        'is_queued',
        'is_viewed',
        'delivered_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'is_queued',
        'modified_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_viewed' => 'boolean',
        'is_queued' => 'boolean',
        'data' => 'array',
    ];
}
