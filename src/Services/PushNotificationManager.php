<?php

namespace Saritasa\PushNotifications\Services;

use LogicException;

/**
 * Class PushNotificationManager is used to manage push notification services (ios and android).
 * @package App\Model\Services\PushServices
 */
class PushNotificationManager implements LogInterface
{
    use LogTrait;

    /**
     * Service type of Android.
     * @var string
     */
    const TYPE_ANDROID          = 'A';

    /**
     * Service type of IOS.
     * @var string
     */
    const TYPE_IOS              = 'I';

    /**
     * Supported service list.
     * @var array
     */
    protected $services = array();

    /**
     * Instance of PushNotificationManager.
     * @var PushNotificationManager
     */
    private static $instance = null;

    /**
     * PushNotificationManager constructor.
     * @access private
     */
    private function __construct()
    {
        $this->logFile = config('push.LOG_FILE');
        $this->isDebug = config('push.IS_DEBUG');
    }

    /**
     * Get an instance of PushNotificationManager.
     *
     * @return PushNotificationManager
     * @access public
     * @static
     */
    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Create AndroidPushNotificationService.
     *
     * @return AndroidPushNotificationService
     * @access private
     */
    private function createAndroidPushNotificationService()
    {
        $apiKey = config('push.GOOGLE_API_KEY');
        return new AndroidPushNotificationService($apiKey, $this->logFile, $this->isDebug);
    }

    /**
     * Create ApplePushNotificationService.
     *
     * @return ApplePushNotificationService
     * @access private
     */
    private function createApplePushNotificationService()
    {
        $isSandbox = config('push.IS_SANDBOX');
        $passpharse = $isSandbox ? config('push.IOS_SANDBOX_PASSPHARSE') : config('push.IOS_PRODUCTION_PASSPHARSE');
        $appleService = new ApplePushNotificationService($isSandbox, '', $passpharse, $this->logFile, $this->isDebug);
        return $appleService;
    }

    /**
     * Get service for push notification.
     *
     * @param string $type
     * @return PushNotificationService
     * @throws LogicException
     * @access private
     */
    private function getService($type)
    {
        if (!empty($this->services[$type])) {
            return $this->services[$type];
        }
        switch ($type) {
            case self::TYPE_ANDROID:
                $this->services[$type] = $this->createAndroidPushNotificationService();
                return $this->services[$type];
            case self::TYPE_IOS:
                $this->services[$type] = $this->createApplePushNotificationService();
                return $this->services[$type];
            default:
                throw new LogicException('Unknown notification type: '.$type);
        }
    }

    /**
     * Send push notification for Android.
     *
     * @param mixed $to
     * @param string $message
     * @param array $options
     * @return bool
     * @access public
     */
    public function sendPushAndroid($to, $message, array $options = array())
    {
        return $this->getService(self::TYPE_ANDROID)->send($to, $message, $options);
    }

    /**
     * Send push notification for IOS.
     *
     * @param mixed $to
     * @param string $message
     * @param array $options
     * @return bool
     * @access public
     */
    public function sendPushIOS($to, $message, array $options = array())
    {
        return $this->getService(self::TYPE_IOS)->send($to, $message, $options);
    }
}
