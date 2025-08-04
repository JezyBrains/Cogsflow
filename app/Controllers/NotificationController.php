<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificationModel;
use App\Models\NotificationSettingsModel;
use App\Models\SystemLogModel;

class NotificationController extends BaseController
{
    protected $notificationModel;
    protected $settingsModel;
    protected $systemLogModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->settingsModel = new NotificationSettingsModel();
        $this->systemLogModel = new SystemLogModel();
    }

    /**
     * Display notifications inbox
     */
    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Notifications',
            'notifications' => $this->notificationModel->getUserNotifications($userId, 100),
            'unreadCount' => $this->notificationModel->getUnreadCount($userId),
            'stats' => $this->notificationModel->getNotificationStats($userId)
        ];

        return view('notifications/index', $data);
    }

    /**
     * Get notifications for AJAX requests
     */
    public function getNotifications()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $limit = $this->request->getGet('limit') ?? 20;
        $unreadOnly = $this->request->getGet('unread_only') ?? false;

        $notifications = $this->notificationModel->getUserNotifications($userId, $limit, $unreadOnly);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId = null)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        if ($notificationId) {
            $result = $this->notificationModel->markAsRead($notificationId, $userId);
        } else {
            // Mark all as read
            $result = $this->notificationModel->markAllAsRead($userId);
        }

        if ($result) {
            $unreadCount = $this->notificationModel->getUnreadCount($userId);
            return $this->response->setJSON([
                'success' => true,
                'message' => $notificationId ? 'Notification marked as read' : 'All notifications marked as read',
                'unread_count' => $unreadCount
            ]);
        }

        return $this->response->setJSON(['error' => 'Failed to update notification']);
    }

    /**
     * Delete notification
     */
    public function delete($notificationId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        // Verify notification belongs to user
        $notification = $this->notificationModel->where('id', $notificationId)
                                               ->where('user_id', $userId)
                                               ->first();

        if (!$notification) {
            return $this->response->setJSON(['error' => 'Notification not found']);
        }

        if ($this->notificationModel->delete($notificationId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        }

        return $this->response->setJSON(['error' => 'Failed to delete notification']);
    }

    /**
     * Display notification settings
     */
    public function settings()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Notification Settings',
            'settings' => $this->settingsModel->getUserSettings($userId),
            'summary' => $this->settingsModel->getPreferencesSummary($userId)
        ];

        return view('notifications/settings', $data);
    }

    /**
     * Update notification settings
     */
    public function updateSettings()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $settings = $this->request->getPost('settings');
        if (!$settings) {
            return $this->response->setJSON(['error' => 'No settings provided']);
        }

        try {
            $result = $this->settingsModel->updateBulkSettings($userId, $settings);

            if ($result) {
                // Log the settings update
                $this->systemLogModel->addLog(
                    'info',
                    'Notification settings updated',
                    'NotificationController::updateSettings',
                    $userId,
                    ['settings_count' => count($settings)]
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Notification settings updated successfully'
                ]);
            }

            return $this->response->setJSON(['error' => 'Failed to update settings']);

        } catch (\Exception $e) {
            log_message('error', 'Failed to update notification settings: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'An error occurred while updating settings']);
        }
    }

    /**
     * Create a new notification (for system use)
     */
    public function create()
    {
        // This method is typically called by other controllers/services
        $data = $this->request->getJSON(true);

        $required = ['user_id', 'type', 'title', 'message'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->response->setJSON(['error' => "Field '{$field}' is required"]);
            }
        }

        $notificationId = $this->notificationModel->createNotification(
            $data['user_id'],
            $data['type'],
            $data['title'],
            $data['message'],
            $data['data'] ?? null,
            $data['priority'] ?? 'normal'
        );

        if ($notificationId) {
            return $this->response->setJSON([
                'success' => true,
                'notification_id' => $notificationId,
                'message' => 'Notification created successfully'
            ]);
        }

        return $this->response->setJSON(['error' => 'Failed to create notification']);
    }

    /**
     * Create bulk notifications
     */
    public function createBulk()
    {
        $data = $this->request->getJSON(true);

        $required = ['user_ids', 'type', 'title', 'message'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->response->setJSON(['error' => "Field '{$field}' is required"]);
            }
        }

        $result = $this->notificationModel->createBulkNotification(
            $data['user_ids'],
            $data['type'],
            $data['title'],
            $data['message'],
            $data['data'] ?? null,
            $data['priority'] ?? 'normal'
        );

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Bulk notifications created successfully',
                'count' => count($data['user_ids'])
            ]);
        }

        return $this->response->setJSON(['error' => 'Failed to create bulk notifications']);
    }

    /**
     * Get unread notification count (for AJAX polling)
     */
    public function getUnreadCount()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $count = $this->notificationModel->getUnreadCount($userId);
        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $count
        ]);
    }

    /**
     * Get recent notifications for header dropdown
     */
    public function getRecent()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $notifications = $this->notificationModel->getUserNotifications($userId, 10);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        // Format notifications for display
        foreach ($notifications as &$notification) {
            $notification['time_ago'] = $this->timeAgo($notification['created_at']);
            $notification['is_unread'] = is_null($notification['read_at']);
            
            // Decode data if present
            if ($notification['data']) {
                $notification['data'] = json_decode($notification['data'], true);
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Helper function to format time ago
     */
    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . 'm ago';
        if ($time < 86400) return floor($time/3600) . 'h ago';
        if ($time < 2592000) return floor($time/86400) . 'd ago';
        if ($time < 31536000) return floor($time/2592000) . 'mo ago';
        return floor($time/31536000) . 'y ago';
    }
}
