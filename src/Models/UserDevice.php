<?php

namespace Saritasa\PushNotifications\Models;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Saritasa\Database\Eloquent\Entity;

/**
 * App\Model\Entities\UserDevice
 *
 * @property integer $id
 * @property integer $user_id
 * @property DeviceType $device_type
 * @property string $device_token
 * @property string $hash
 * @property Carbon $created_at
 * @method static Builder|UserDevice whereId($value)
 * @method static Builder|UserDevice whereHash($value)
 * @method static Builder|UserDevice whereUserId($value)
 * @method static Builder|UserDevice whereDeviceType($value)
 * @method static Builder|UserDevice whereDeviceToken($value)
 * @method static Builder|UserDevice whereCreatedAt($value)
 * @mixin \Eloquent
 */
class UserDevice extends Entity
{
    /**
     * @var string
     */
    const DEVICE_TYPE = 'device_type';

    /**
     * @var string
     */
    const DEVICE_TOKEN = 'device_token';

    /**
     * @var string
     */
    const ACCESS_TOKEN = 'access_token';

    /**
     * @var string
     */
    const ACCESS_TOKEN_SECRET = 'access_token_secret';

    /**
     * Web type
     *
     * @var string
     */
    const WEB_TYPE = 'web';
    /**
     * Validation rule of required device type.
     *
     * @var string
     */
    const DEVICE_TYPE_RULE = 'required|in:android,ios,web';

    /**
     * Validation rule of required if device type is android.
     *
     * @var string
     */
    const RULE_REQUIRED_IF_ANDROID = 'required_if:device_type,android';

    /**
     * Validation rule of required if device type is ios.
     *
     * @var string
     */
    const RULE_REQUIRED_IF_IOS = 'required_if:device_type,ios';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'device_type',
        'device_token'
    ];

    protected $enums = [
        'purchase_type' => DeviceType::class
    ];

    /**
     * Fill the model with an array of attributes.
     * Override fill function
     *
     * @param  array  $attributes
     * @return $this
     * @access public
     */
    public function fill(array $attributes)
    {
        parent::fill($attributes);

        if (isset($attributes[static::DEVICE_TOKEN])) {
            $this->setDeviceToken($attributes[static::DEVICE_TOKEN]);
        }

        return $this;
    }

    /**
     * Set device token.
     *
     * @param mixed $value
     * @return $this
     */
    public function setDeviceToken($value)
    {
        $this->device_token = $value;
        $this->hash = static::toHash($value);
        return $this;
    }

    /**
     * Get hash of given device token.
     *
     * @param string $deviceToken
     * @return string
     * @access public
     * @static
     */
    public static function toHash($deviceToken)
    {
        return sha1($deviceToken);
    }

    /**
     * Find by device token.
     *
     * @param string $deviceToken
     * @return UserDevice
     * @access public
     * @static
     */
    public static function findByDeviceToken($deviceToken)
    {
        return static::where('hash', '=', static::toHash($deviceToken))->first();
    }

    /**
     * Get device tokens by given user id.
     *
     * @param int $userID
     * @return array
     * @access public
     * @static
     */
    public static function getByUserID($userID)
    {
        return static::where('user_id', '=', $userID)->get()->all();
    }

    /**
     * Get device tokens by given user id.
     *
     * @param int $userID
     * @return array
     * @access public
     * @static
     */
    public static function getDevicesForSendingPush($userID)
    {
        $listDevices = static::getByUserID($userID);
        $listByTypes = [];
        foreach ($listDevices as $device) {
            /* @var UserDevice $device */
            $listByTypes[$device->device_type_id][] = $device->device_token;
        }
        return $listByTypes;
    }

    /**
     * Delete by device token.
     *
     * @param string $deviceToken
     * @return bool|null
     * @access public
     * @static
     */
    public static function deleteByDeviceToken($deviceToken)
    {
        return static::where('hash', '=', static::toHash($deviceToken))->delete();
    }

    /**
     * Return common validation rules of all user fields
     *
     * @return array
     */
    public static function authRules()
    {
        return [
            static::DEVICE_TOKEN => static::RULE_REQUIRED,
            static::DEVICE_TYPE => static::DEVICE_TYPE_RULE,
        ];
    }

    /**
     * Return validation rules for authentication social using oAuth V2
     *
     * @return array
     */
    public static function oAuthV2Rules()
    {
        $rules = static::authRules();
        $rules[UserDevice::ACCESS_TOKEN] = static::RULE_REQUIRED;
        return $rules;
    }

    /**
     * Return validation rules for authentication social using oAuth V1
     *
     * @return array
     */
    public static function oAuthV1Rules()
    {
        $rules = static::authRules();
        $rules[UserDevice::ACCESS_TOKEN] = static::RULE_REQUIRED;
        $rules[UserDevice::ACCESS_TOKEN_SECRET] = static::RULE_REQUIRED;
        return $rules;
    }
}
