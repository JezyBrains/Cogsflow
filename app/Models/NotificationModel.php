<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'type', 'title', 'message', 'data', 'priority', 'read_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'type' => 'required|max_length[50]',
        'title' => 'required|max_length[255]',
        'message' => 'required',
        'priority' => 'in_list[low,normal,high,critical]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer'
        ],
        'type' => [
            'required' => 'Notification type is required',
            'max_length' => 'Notification type cannot exceed 50 characters'
        ],
        'title' => [
            'required' => 'Notification title is required',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'message' => [
            'required' => 'Notification message is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Create a new notification
     */
    public function createNotification($userId, $type, $title, $message, $data = null, $priority = 'normal')
    {
        $notificationData = [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data ? json_encode($data) : null,
            'priority' => $priority,
        ];

        return $this->insert($notificationData);
    }

    /**
     * Create notification for multiple users
     */
    public function createBulkNotification($userIds, $type, $title, $message, $data = null, $priority = 'normal')
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data ? json_encode($data) : null,
                'priority' => $priority,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        return $this->insertBatch($notifications);
    }

    /**
     * Get notifications for a user
     */
    public function getUserNotifications($userId, $limit = 50, $unreadOnly = false)
    {
        $builder = $this->where('user_id', $userId);
        
        if ($unreadOnly) {
            $builder->where('read_at IS NULL');
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($limit)
                      ->find();
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('read_at IS NULL')
                   ->countAllResults();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId = null)
    {
        $builder = $this->where('id', $notificationId);
        
        if ($userId) {
            $builder->where('user_id', $userId);
        }
        
        return $builder->update(['read_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('read_at IS NULL')
                   ->update(['read_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Delete old notifications
     */
    public function cleanOldNotifications($days = 30)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at <', $cutoffDate)->delete();
    }

    /**
     * Get notifications by type
     */
    public function getNotificationsByType($type, $limit = 100)
    {
        return $this->where('type', $type)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->find();
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStats($userId = null)
    {
        $builder = $this->select('type, priority, COUNT(*) as count, MAX(created_at) as latest');
        
        if ($userId) {
            $builder->where('user_id', $userId);
        }
        
        return $builder->groupBy(['type', 'priority'])
                      ->orderBy('latest', 'DESC')
                      ->find();
    }

    /**
     * Get recent critical notifications
     */
    public function getCriticalNotifications($limit = 10)
    {
        return $this->where('priority', 'critical')
                   ->where('created_at >', date('Y-m-d H:i:s', strtotime('-24 hours')))
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->find();
    }

    protected function beforeInsert(array $data)
    {
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        return $data;
    }
}
