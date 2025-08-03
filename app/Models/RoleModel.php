<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'display_name', 'description', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name'         => 'required|alpha_dash|min_length[3]|max_length[50]|is_unique[roles.name,id,{id}]',
        'display_name' => 'required|min_length[3]|max_length[100]',
        'description'  => 'permit_empty|max_length[500]',
        'is_active'    => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'Role name is required',
            'alpha_dash'  => 'Role name can only contain letters, numbers, dashes and underscores',
            'min_length'  => 'Role name must be at least 3 characters long',
            'max_length'  => 'Role name cannot exceed 50 characters',
            'is_unique'   => 'This role name already exists'
        ],
        'display_name' => [
            'required'   => 'Display name is required',
            'min_length' => 'Display name must be at least 3 characters long',
            'max_length' => 'Display name cannot exceed 100 characters'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all active roles
     */
    public function getActiveRoles()
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Get role with permissions
     */
    public function getRoleWithPermissions($roleId)
    {
        $role = $this->find($roleId);
        if (!$role) {
            return null;
        }

        $db = \Config\Database::connect();
        $permissions = $db->table('role_permissions rp')
            ->select('p.id, p.name, p.resource, p.action, p.description')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $roleId)
            ->get()
            ->getResultArray();

        $role['permissions'] = $permissions;
        return $role;
    }

    /**
     * Get all roles with permission counts
     */
    public function getRolesWithPermissionCounts()
    {
        $db = \Config\Database::connect();
        
        return $db->table('roles r')
            ->select('r.*, COUNT(rp.permission_id) as permission_count')
            ->join('role_permissions rp', 'rp.role_id = r.id', 'left')
            ->groupBy('r.id')
            ->orderBy('r.name')
            ->get()
            ->getResultArray();
    }

    /**
     * Assign permissions to role
     */
    public function assignPermissions($roleId, $permissionIds)
    {
        $db = \Config\Database::connect();
        
        // First, remove existing permissions
        $db->table('role_permissions')->where('role_id', $roleId)->delete();
        
        // Then add new permissions
        if (!empty($permissionIds)) {
            $data = [];
            foreach ($permissionIds as $permissionId) {
                $data[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            
            return $db->table('role_permissions')->insertBatch($data);
        }
        
        return true;
    }

    /**
     * Get users assigned to this role
     */
    public function getRoleUsers($roleId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('user_roles ur')
            ->select('u.id, u.username, u.email, ur.assigned_at, ur.is_active')
            ->join('users u', 'u.id = ur.user_id')
            ->where('ur.role_id', $roleId)
            ->where('ur.is_active', 1)
            ->get()
            ->getResultArray();
    }

    /**
     * Check if role can be deleted
     */
    public function canDelete($roleId)
    {
        $db = \Config\Database::connect();
        
        // Check if role has active users
        $userCount = $db->table('user_roles')
            ->where('role_id', $roleId)
            ->where('is_active', 1)
            ->countAllResults();
            
        return $userCount === 0;
    }

    /**
     * Delete role and cleanup related data
     */
    public function deleteRole($roleId)
    {
        if (!$this->canDelete($roleId)) {
            return false;
        }

        $db = \Config\Database::connect();
        
        // Start transaction
        $db->transStart();
        
        // Delete role permissions
        $db->table('role_permissions')->where('role_id', $roleId)->delete();
        
        // Delete user roles (inactive ones)
        $db->table('user_roles')->where('role_id', $roleId)->delete();
        
        // Delete the role
        $this->delete($roleId);
        
        // Complete transaction
        $db->transComplete();
        
        return $db->transStatus();
    }
}
