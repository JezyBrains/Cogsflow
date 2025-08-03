<?php

use App\Models\UserRoleModel;

if (!function_exists('hasRole')) {
    /**
     * Check if current user has a specific role
     */
    function hasRole(string $roleName): bool
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return false;
        }
        
        $userRoleModel = new UserRoleModel();
        return $userRoleModel->userHasRole($userId, $roleName);
    }
}

if (!function_exists('hasPermission')) {
    /**
     * Check if current user has a specific permission
     */
    function hasPermission(string $permission): bool
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return false;
        }
        
        $userRoleModel = new UserRoleModel();
        return $userRoleModel->userHasPermission($userId, $permission);
    }
}

if (!function_exists('hasResourcePermission')) {
    /**
     * Check if current user has permission for a resource and action
     */
    function hasResourcePermission(string $resource, string $action): bool
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return false;
        }
        
        $userRoleModel = new UserRoleModel();
        return $userRoleModel->userHasResourcePermission($userId, $resource, $action);
    }
}

if (!function_exists('isAdmin')) {
    /**
     * Check if current user is an admin
     */
    function isAdmin(): bool
    {
        return hasRole('admin');
    }
}

if (!function_exists('isWarehouseStaff')) {
    /**
     * Check if current user is warehouse staff
     */
    function isWarehouseStaff(): bool
    {
        return hasRole('warehouse_staff');
    }
}

if (!function_exists('isStandardUser')) {
    /**
     * Check if current user is a standard user
     */
    function isStandardUser(): bool
    {
        return hasRole('standard_user');
    }
}

if (!function_exists('getUserRoles')) {
    /**
     * Get all roles for current user
     */
    function getUserRoles(): array
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return [];
        }
        
        $userRoleModel = new UserRoleModel();
        return $userRoleModel->getUserRoles($userId);
    }
}

if (!function_exists('getUserPermissions')) {
    /**
     * Get all permissions for current user
     */
    function getUserPermissions(): array
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return [];
        }
        
        $userRoleModel = new UserRoleModel();
        return $userRoleModel->getUserPermissions($userId);
    }
}

if (!function_exists('canAccess')) {
    /**
     * Check if user can access a resource with specific action
     * This is a more flexible function that checks both roles and permissions
     */
    function canAccess(string $resource, string $action = 'view'): bool
    {
        // Admin can access everything
        if (isAdmin()) {
            return true;
        }
        
        // Check specific permission
        if (hasResourcePermission($resource, $action)) {
            return true;
        }
        
        // Check role-based access for warehouse staff
        if (isWarehouseStaff()) {
            $warehouseResources = ['inventory', 'batches', 'dispatches', 'purchase_orders', 'expenses'];
            if (in_array($resource, $warehouseResources)) {
                // Warehouse staff can view, create, edit but not delete (except their own records)
                return in_array($action, ['view', 'create', 'edit']);
            }
        }
        
        // Standard users can only view most resources
        if (isStandardUser()) {
            $viewableResources = ['inventory', 'batches', 'dispatches', 'purchase_orders', 'expenses', 'reports'];
            return in_array($resource, $viewableResources) && $action === 'view';
        }
        
        return false;
    }
}

if (!function_exists('showIfCan')) {
    /**
     * Show content only if user has permission
     * Usage: <?= showIfCan('inventory', 'create', '<button>Create Item</button>') ?>
     */
    function showIfCan(string $resource, string $action, string $content): string
    {
        return canAccess($resource, $action) ? $content : '';
    }
}

if (!function_exists('hideIfCannot')) {
    /**
     * Hide content if user doesn't have permission
     */
    function hideIfCannot(string $resource, string $action, string $content): string
    {
        return canAccess($resource, $action) ? $content : '';
    }
}

if (!function_exists('roleBasedRedirect')) {
    /**
     * Redirect user based on their role
     */
    function roleBasedRedirect(): string
    {
        if (isAdmin()) {
            return site_url('dashboard');
        } elseif (isWarehouseStaff()) {
            return site_url('inventory');
        } else {
            return site_url('dashboard');
        }
    }
}
