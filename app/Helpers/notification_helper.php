<?php

use App\Models\NotificationModel;
use App\Models\NotificationSettingsModel;
use App\Models\UserRoleModel;

if (!function_exists('sendNotification')) {
    /**
     * Send a notification to a specific user
     */
    function sendNotification($userId, $type, $title, $message, $data = null, $priority = 'normal')
    {
        $notificationModel = new NotificationModel();
        $settingsModel = new NotificationSettingsModel();

        // Check if user should receive this type of notification
        if (!$settingsModel->shouldReceiveNotification($userId, $type)) {
            return false;
        }

        return $notificationModel->createNotification($userId, $type, $title, $message, $data, $priority);
    }
}

if (!function_exists('sendBulkNotification')) {
    /**
     * Send notification to multiple users
     */
    function sendBulkNotification($userIds, $type, $title, $message, $data = null, $priority = 'normal')
    {
        $notificationModel = new NotificationModel();
        $settingsModel = new NotificationSettingsModel();

        // Filter users who should receive this notification
        $filteredUserIds = [];
        foreach ($userIds as $userId) {
            if ($settingsModel->shouldReceiveNotification($userId, $type)) {
                $filteredUserIds[] = $userId;
            }
        }

        if (empty($filteredUserIds)) {
            return false;
        }

        return $notificationModel->createBulkNotification($filteredUserIds, $type, $title, $message, $data, $priority);
    }
}

if (!function_exists('sendRoleBasedNotification')) {
    /**
     * Send notification to users with specific roles
     */
    function sendRoleBasedNotification($roles, $type, $title, $message, $data = null, $priority = 'normal')
    {
        $userRoleModel = new UserRoleModel();
        
        // Get users with specified roles
        $userIds = [];
        foreach ($roles as $role) {
            $roleUsers = $userRoleModel->getUsersByRole($role);
            foreach ($roleUsers as $user) {
                $userIds[] = $user['user_id'];
            }
        }

        // Remove duplicates
        $userIds = array_unique($userIds);

        if (empty($userIds)) {
            return false;
        }

        return sendBulkNotification($userIds, $type, $title, $message, $data, $priority);
    }
}

if (!function_exists('sendBatchNotification')) {
    /**
     * Send batch-related notification
     */
    function sendBatchNotification($batchId, $batchName, $action, $additionalData = [])
    {
        $data = array_merge([
            'batch_id' => $batchId,
            'batch_name' => $batchName,
            'action' => $action
        ], $additionalData);

        $title = '';
        $message = '';
        $priority = 'normal';

        switch ($action) {
            case 'created':
                $title = 'New Batch Created';
                $message = "Batch '{$batchName}' has been created and is ready for processing.";
                break;
            case 'updated':
                $title = 'Batch Updated';
                $message = "Batch '{$batchName}' has been updated.";
                break;
            case 'arrived':
                $title = 'Batch Arrival';
                $message = "Batch '{$batchName}' has arrived and is available for dispatch.";
                $priority = 'high';
                break;
            case 'low_stock':
                $title = 'Low Stock Alert';
                $message = "Batch '{$batchName}' is running low on stock.";
                $priority = 'high';
                break;
            default:
                $title = 'Batch Update';
                $message = "Batch '{$batchName}' has been {$action}.";
        }

        return sendRoleBasedNotification(['admin', 'warehouse_staff'], 'batch_arrival', $title, $message, $data, $priority);
    }
}

if (!function_exists('sendDispatchNotification')) {
    /**
     * Send dispatch-related notification
     */
    function sendDispatchNotification($dispatchId, $dispatchRef, $action, $additionalData = [])
    {
        $data = array_merge([
            'dispatch_id' => $dispatchId,
            'dispatch_ref' => $dispatchRef,
            'action' => $action
        ], $additionalData);

        $title = '';
        $message = '';
        $priority = 'normal';

        switch ($action) {
            case 'created':
                $title = 'New Dispatch Created';
                $message = "Dispatch '{$dispatchRef}' has been created and is ready for processing.";
                break;
            case 'shipped':
                $title = 'Dispatch Shipped';
                $message = "Dispatch '{$dispatchRef}' has been shipped and is on its way.";
                $priority = 'high';
                break;
            case 'delivered':
                $title = 'Dispatch Delivered';
                $message = "Dispatch '{$dispatchRef}' has been successfully delivered.";
                $priority = 'high';
                break;
            case 'delayed':
                $title = 'Dispatch Delayed';
                $message = "Dispatch '{$dispatchRef}' has been delayed.";
                $priority = 'high';
                break;
            case 'cancelled':
                $title = 'Dispatch Cancelled';
                $message = "Dispatch '{$dispatchRef}' has been cancelled.";
                $priority = 'high';
                break;
            default:
                $title = 'Dispatch Update';
                $message = "Dispatch '{$dispatchRef}' status has been updated to {$action}.";
        }

        return sendRoleBasedNotification(['admin', 'warehouse_staff'], 'dispatch_status', $title, $message, $data, $priority);
    }
}

if (!function_exists('sendInventoryNotification')) {
    /**
     * Send inventory-related notification
     */
    function sendInventoryNotification($itemName, $action, $currentStock = null, $threshold = null, $additionalData = [])
    {
        $data = array_merge([
            'item_name' => $itemName,
            'action' => $action,
            'current_stock' => $currentStock,
            'threshold' => $threshold
        ], $additionalData);

        $title = '';
        $message = '';
        $priority = 'normal';

        switch ($action) {
            case 'low_stock':
                $title = 'Low Stock Alert';
                $message = "'{$itemName}' is running low on stock (Current: {$currentStock}, Threshold: {$threshold}).";
                $priority = 'high';
                break;
            case 'out_of_stock':
                $title = 'Out of Stock Alert';
                $message = "'{$itemName}' is out of stock and needs immediate attention.";
                $priority = 'critical';
                break;
            case 'overstock':
                $title = 'Overstock Alert';
                $message = "'{$itemName}' has excess stock that may need attention.";
                $priority = 'normal';
                break;
            case 'restock':
                $title = 'Item Restocked';
                $message = "'{$itemName}' has been restocked (New stock: {$currentStock}).";
                break;
            default:
                $title = 'Inventory Update';
                $message = "'{$itemName}' inventory has been updated.";
        }

        return sendRoleBasedNotification(['admin', 'warehouse_staff'], 'inventory_threshold', $title, $message, $data, $priority);
    }
}

if (!function_exists('sendSystemNotification')) {
    /**
     * Send system-related notification
     */
    function sendSystemNotification($action, $message, $priority = 'normal', $additionalData = [])
    {
        $data = array_merge([
            'action' => $action,
            'timestamp' => date('Y-m-d H:i:s')
        ], $additionalData);

        $title = '';

        switch ($action) {
            case 'backup_completed':
                $title = 'Backup Completed';
                break;
            case 'backup_failed':
                $title = 'Backup Failed';
                $priority = 'critical';
                break;
            case 'maintenance_start':
                $title = 'Maintenance Started';
                $priority = 'high';
                break;
            case 'maintenance_end':
                $title = 'Maintenance Completed';
                break;
            case 'error':
                $title = 'System Error';
                $priority = 'critical';
                break;
            case 'warning':
                $title = 'System Warning';
                $priority = 'high';
                break;
            default:
                $title = 'System Notification';
        }

        return sendRoleBasedNotification(['admin'], 'system_error', $title, $message, $data, $priority);
    }
}

if (!function_exists('sendUserManagementNotification')) {
    /**
     * Send user management notification
     */
    function sendUserManagementNotification($username, $action, $additionalData = [])
    {
        $data = array_merge([
            'username' => $username,
            'action' => $action,
            'timestamp' => date('Y-m-d H:i:s')
        ], $additionalData);

        $title = '';
        $message = '';
        $priority = 'normal';

        switch ($action) {
            case 'created':
                $title = 'New User Created';
                $message = "User account '{$username}' has been created.";
                break;
            case 'role_changed':
                $title = 'User Role Changed';
                $message = "User '{$username}' role has been updated.";
                $priority = 'high';
                break;
            case 'deactivated':
                $title = 'User Deactivated';
                $message = "User account '{$username}' has been deactivated.";
                $priority = 'high';
                break;
            case 'login_failed':
                $title = 'Failed Login Attempt';
                $message = "Multiple failed login attempts detected for user '{$username}'.";
                $priority = 'high';
                break;
            default:
                $title = 'User Management Update';
                $message = "User '{$username}' has been {$action}.";
        }

        return sendRoleBasedNotification(['admin'], 'user_management', $title, $message, $data, $priority);
    }
}
