<?php

namespace Saritasa\PushNotifications\Repositories;

use Illuminate\Support\Facades\DB;
use Saritasa\Exceptions\NotFoundException;
use Saritasa\PushNotifications\Models\Notification;
use Saritasa\PushNotifications\Models\NotificationSetting;
use Saritasa\PushNotifications\Models\NotificationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;

/**
 * Store and retrieve notifications and notifications settings
 */
class NotificationRepository
{
    /**
     * Get list of notifications settings by userID.
     *
     * @param integer $userId
     *
     * @return Collection
     * @access public
     */
    public function getSettings(int $userId)
    {
        $query = NotificationType::select([
            'NotificationTypes.id',
            'NotificationTypes.name as notificationTypeName',
            DB::raw('coalesce(NotificationSettings.isOn, NotificationTypes.defaultOn) isOn')
        ]);

        $query->leftJoin('NotificationSettings', function (JoinClause $join) use ($userId) {
            $join->on('NotificationSettings.notificationTypeId', '=', 'NotificationTypes.id')
                ->where('NotificationSettings.userId', '=', $userId);
        })
            ->where('NotificationTypes.isSystem', '=', false)
            ->orderBy('NotificationTypes.id', 'asc');
        return $query->get();
    }

    /**
     * Save notifications setting.
     *
     * @param integer $id - id of notification type.
     * @param integer $on - status of notification type.
     * @param integer $userID
     *
     * @return void
     * @access public
     */
    public function updateSetting($id, $on, $userID)
    {
        NotificationSetting::updateOrCreate(
            ['notificationTypeId' => $id, 'userId' => $userID],
            ['notificationTypeId' => $id, 'isOn' => $on, 'userId' => $userID]
        );
    }

    /**
     * @param User $user
     * @param int $limit
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|int
     */
    public function getNotifications(User $user, $limit = 100, $page = 1)
    {
        return Notification::where('user_id', '=', $user->id)
            ->whereNotNull('delivered_at')
            ->selectRaw('notifications.*')
            ->orderBy('delivered_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($limit, null, null, $page);
    }

    /**
     * Get notifications for sending push
     *
     * @param integer $id Notification id
     * @param int $userId
     * @return Notification
     * @throws NotFoundException
     */
    public function find(int $id, int $userId = 0)
    {
        /** @var Builder $query */
        $query = Notification::query()
            ->join('NotificationTypes', 'NotificationTypes.id', '=', 'notifications.notification_type_id')
            ->leftJoin('NotificationSettings', function ($join) {
                $join->on('NotificationSettings.notificationTypeId', '=', 'notifications.notification_type_id')
                    ->on('NotificationSettings.userId', '=', 'notifications.user_id');
            })
            ->where('notifications.id', $id)
            ->select(['notifications.*', 'NotificationSettings.isOn', 'NotificationTypes.defaultOn']);

        if ($userId) {
            $query->where('notifications.user_id', $userId);
        }

        /** @var Notification $notification */
        $notification = $query->first();
        if (!$notification) {
            throw new NotFoundException(trans('notifications.not_found'));
        }

        $notification->can_push = $notification->isOn ?? $notification->defaultOn;

        return $notification;
    }

    /**
     * @param int $id
     * @param int $userId
     * @return bool|int|null
     */
    public function delete(int $id, int $userId)
    {
        $query = Notification::whereUserId($userId);
        if ($id) {
            $query->whereId($id);
        }
        return $query->delete();
    }

    /**
     * Mark notification messages as viewed by given ids.
     *
     * @param array $ids - ids of notification type.
     * @param int $userId
     * @param int $status
     *
     * @return bool|int
     */
    public function changeViewStatus(array $ids, int $userId, int $status = 1)
    {
        return Notification::whereUserId($userId)
            ->whereNotNull('delivered_at')
            ->whereIsViewed(false)
            ->whereIn('id', $ids)
            ->update(['is_viewed' => $status]);
    }

    /**
     * Count unread items
     *
     * @param User $user
     * @param int $status
     *
     * @return int
     */
    public function countViewStatus(User $user, int $status = 0)
    {
        return Notification::whereUserId($user->id)->where('is_viewed', $status)->count('id');
    }

    /**
     * Insert a notification into table
     *
     * @param int $toUserID
     * @param string $subject
     * @param string $message
     * @param int $typeId
     * @param array $data
     *
     * @return Notification
     */
    public function insert(int $toUserID, string $subject, string $message, int $typeId, array $data = [])
    {
        return Notification::create([
            'user_id' => $toUserID,
            'subject' => $subject,
            'message' => $message,
            'notification_type_id' => $typeId,
            'data' => $data,
        ]);
    }
}
