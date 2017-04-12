<?php

namespace Saritasa\PushNotifications\Models;

use Illuminate\Database\Query\Builder;
use Saritasa\Database\Eloquent\Entity;

/**
 * App\Model\Entities\DeviceType
 *
 * @property integer $id
 * @property string $name
 * @method static Builder|DeviceType whereId($value)
 * @method static Builder|DeviceType whereName($value)
 * @mixin \Eloquent
 */
class DeviceType extends Entity
{
    /**
     * The id of iphone device type.
     *
     * @var integer
     */
    const IPHONE_ID = 1;

    /**
     * The id of android device type.
     *
     * @var integer
     */
    const ANDROID_ID = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device_types';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
