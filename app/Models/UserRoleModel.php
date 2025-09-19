<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table            = 'user_roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'role_id', 'assigned_by', 'assigned_at', 'expires_at', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id'     => 'required|integer',
        'role_id'     => 'required|integer',
        'assigned_by' => 'permit_empty|integer',
        'is_active'   => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer'  => 'User ID must be a valid integer'
        ],
        'role_id' => [
            'required' => 'Role ID is required',
            'integer'  => 'Role ID must be a valid integer'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setAssignedAt'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Set assigned_at timestamp before insert
     */
    protected function setAssignedAt(array $data)
    {
        if (!isset($data['data']['assigned_at'])) {
            $data['data']['assigned_at'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    /**
     * Get user roles with role details
     */
    public function getUserRoles($userId)
    {
        $db = \Config\Database::connect();
        
        // Add error handling to prevent "Call to a member function getResultArray() on false" error
        $query = $db->table('user_roles ur')
            ->select('ur.*, r.name, r.display_name, r.description')
            ->join('roles r', 'r.id = ur.role_id')
            ->where('ur.user_id', $userId)
            ->where('ur.is_active', 1)
            ->where('r.is_active', 1)
            ->get();
            
        // Check if query was successful
        if ($query === false) {
            log_message('error', 'Database error in getUserRoles: ' . $db->error()['message']);
            return [];
        }
        
        return $query->getResultArray();
    }

    /**
     * Get user permissions through roles
     */
    public function getUserPermissions($userId)
    {
        $db = \Config\Database::connect();
        
        // Add error handling to prevent "Call to a member function getResultArray() on false" error
        $query = $db->table('user_roles ur')
            ->select('p.name, p.resource, p.action, p.description')
            ->join('roles r', 'r.id = ur.role_id')
            ->join('role_permissions rp', 'rp.role_id = r.id')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('ur.user_id', $userId)
            ->where('ur.is_active', 1)
            ->where('r.is_active', 1)
            ->groupBy('p.id')
            ->get();
            
        // Check if query was successful
        if ($query === false) {
            log_message('error', 'Database error in getUserPermissions: ' . $db->error()['message']);
            return [];
        }
        
        return $query->getResultArray();
    }

    /**
     * Check if user has specific permission
     */
    public function userHasPermission($userId, $permission)
    {
        $db = \Config\Database::connect();
        
        $count = $db->table('user_roles ur')
            ->join('roles r', 'r.id = ur.role_id')
            ->join('role_permissions rp', 'rp.role_id = r.id')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('ur.user_id', $userId)
            ->where('ur.is_active', 1)
            ->where('r.is_active', 1)
            ->where('p.name', $permission)
            ->countAllResults();
            
        return $count > 0;
    }

    /**
     * Check if user has permission for resource and action
     */
    public function userHasResourcePermission($userId, $resource, $action)
    {
        $permission = $resource . '.' . $action;
        return $this->userHasPermission($userId, $permission);
    }

    /**
     * Check if user has any role
     */
    public function userHasRole($userId, $roleName)
    {
        $db = \Config\Database::connect();
        
        $count = $db->table('user_roles ur')
            ->join('roles r', 'r.id = ur.role_id')
            ->where('ur.user_id', $userId)
            ->where('ur.is_active', 1)
            ->where('r.is_active', 1)
            ->where('r.name', $roleName)
            ->countAllResults();
            
        return $count > 0;
    }

    /**
     * Assign role to user
     */
    public function assignRole($userId, $roleId, $assignedBy = null)
    {
        // Check if user already has this role
        $existing = $this->where('user_id', $userId)
                        ->where('role_id', $roleId)
                        ->where('is_active', 1)
                        ->first();
                        
        if ($existing) {
            return false; // User already has this role
        }

        $data = [
            'user_id' => $userId,
            'role_id' => $roleId,
            'assigned_by' => $assignedBy,
            'assigned_at' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ];

        return $this->insert($data);
    }

    /**
     * Remove role from user
     */
    public function removeRole($userId, $roleId)
    {
        return $this->where('user_id', $userId)
                   ->where('role_id', $roleId)
                   ->set('is_active', 0)
                   ->set('updated_at', date('Y-m-d H:i:s'))
                   ->update();
    }

    /**
     * Update user roles (replace all existing roles)
     */
    public function updateUserRoles($userId, $roleIds, $assignedBy = null)
    {
        $db = \Config\Database::connect();
        
        // Start transaction
        $db->transStart();
        
        // Deactivate all existing roles
        $this->where('user_id', $userId)
             ->set('is_active', 0)
             ->set('updated_at', date('Y-m-d H:i:s'))
             ->update();
        
        // Assign new roles
        if (!empty($roleIds)) {
            $data = [];
            foreach ($roleIds as $roleId) {
                $data[] = [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'assigned_by' => $assignedBy,
                    'assigned_at' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            
            $this->insertBatch($data);
        }
        
        // Complete transaction
        $db->transComplete();
        
        return $db->transStatus();
    }

    /**
     * Get all users with their roles
     */
    public function getUsersWithRoles()
    {
        $db = \Config\Database::connect();
        
        // Add error handling to prevent "Call to a member function getResultArray() on false" error
        $query = $db->table('users u')
            ->select('u.id, u.username, u.email, u.created_at as user_created_at, 
                     GROUP_CONCAT(r.display_name SEPARATOR ", ") as roles,
                     COUNT(ur.role_id) as role_count')
            ->join('user_roles ur', 'ur.user_id = u.id', 'left')
            ->join('roles r', 'r.id = ur.role_id AND ur.is_active = 1 AND r.is_active = 1', 'left')
            ->groupBy('u.id')
            ->orderBy('u.username')
            ->get();
            
        // Check if query was successful
        if ($query === false) {
            log_message('error', 'Database error in getUsersWithRoles: ' . $db->error()['message']);
            return [];
        }
        
        return $query->getResultArray();
    }

    /**
     * Get role assignment history for user
     */
    public function getUserRoleHistory($userId)
    {
        $db = \Config\Database::connect();
        
        // Add error handling to prevent "Call to a member function getResultArray() on false" error
        $query = $db->table('user_roles ur')
            ->select('ur.*, r.name, r.display_name, 
                     u_assigned.username as assigned_by_username')
            ->join('roles r', 'r.id = ur.role_id')
            ->join('users u_assigned', 'u_assigned.id = ur.assigned_by', 'left')
            ->where('ur.user_id', $userId)
            ->orderBy('ur.assigned_at', 'DESC')
            ->get();
            
        // Check if query was successful
        if ($query === false) {
            log_message('error', 'Database error in getUserRoleHistory: ' . $db->error()['message']);
            return [];
        }
        
        return $query->getResultArray();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($roleId)
    {
        $db = \Config\Database::connect();
        
        // Add error handling to prevent "Call to a member function getResultArray() on false" error
        $query = $db->table('user_roles ur')
            ->select('u.id, u.username, u.email, ur.assigned_at, ur.expires_at')
            ->join('users u', 'u.id = ur.user_id')
            ->where('ur.role_id', $roleId)
            ->where('ur.is_active', 1)
            ->orderBy('u.username')
            ->get();
            
        // Check if query was successful
        if ($query === false) {
            log_message('error', 'Database error in getUsersByRole: ' . $db->error()['message']);
            return [];
        }
        
        return $query->getResultArray();
    }

    /**
     * Check if role assignment is expired
     */
    public function isRoleExpired($userRoleId)
    {
        $userRole = $this->find($userRoleId);
        
        if (!$userRole || !$userRole['expires_at']) {
            return false;
        }
        
        return strtotime($userRole['expires_at']) < time();
    }

    /**
     * Clean up expired role assignments
     */
    public function cleanupExpiredRoles()
    {
        return $this->where('expires_at IS NOT NULL')
                   ->where('expires_at <', date('Y-m-d H:i:s'))
                   ->where('is_active', 1)
                   ->set('is_active', 0)
                   ->set('updated_at', date('Y-m-d H:i:s'))
                   ->update();
    }
}
