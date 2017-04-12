<?php

namespace Saritasa\PushNotifications\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Saritasa\PushNotifications\Models\DeviceType;
use Saritasa\PushNotifications\Models\Notification;
use Saritasa\PushNotifications\Models\UserDevice;
use Saritasa\PushNotifications\Repositories\NotificationRepository;
use Saritasa\PushNotifications\Services\PushNotificationManager;

/**
 * Job for sending notification
 */
class NotificationPush implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    /**
     * Which queue will be used by default
     *
     * @var string
     */
    const DEFAULT_QUEUE = 'notification';

    /**
     * Default badge value
     *
     * @var int
     */
    const DEFAULT_BADGE = 1;

    /**
     * The id of notification.
     *
     * @var int
     */
    protected $id;


    /**
     * Create a new job instance.
     *
     * @param int $id Notification ID
     */
    public function __construct(int $id)
    {
        $this->id = $id;
        $this->onQueue(static::DEFAULT_QUEUE);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @access public
     */
    public function handle()
    {
        /** @var NotificationRepository $repo */
        $repo = app(NotificationRepository::class);
        $notification = $repo->find($this->id);
        $notification->badge = static::DEFAULT_BADGE;

        $devices = UserDevice::getDevicesForSendingPush($notification->user_id);
        $this->sendNotification($notification, $devices);

        $this->markAsSent($notification);
    }

    /**
     * Send push notification.
     *
     * @param Notification $notification
     * @param array $devices
     * @access private
     */
    private function sendNotification($notification, $devices)
    {
        if (!$notification->can_push || empty($devices)) {
            return;
        }
        $data = $notification->toArray();
        unset($data['data']);
        $pushManager = PushNotificationManager::getInstance();
        if (!empty($devices[DeviceType::ANDROID_ID])) {
            $pushManager->sendPushAndroid($devices[DeviceType::ANDROID_ID], json_encode($data));
        }
        if (!empty($devices[DeviceType::IPHONE_ID])) {
            $options = ['badge' => (int) $notification->badge];
            $pushManager->sendPushIOS($devices[DeviceType::IPHONE_ID], json_encode($data), $options);
        }
    }

    /**
     * Mark the notification as sent.
     *
     * @param Notification $notification
     * @access private
     */
    private function markAsSent($notification)
    {
        unset($notification->can_push);
        unset($notification->badge);
        $notification->update(['delivered_at' => Carbon::now()]);
    }
}
