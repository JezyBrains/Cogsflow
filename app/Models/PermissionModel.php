<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table            = 'permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'resource', 'action', 'description'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name'        => 'required|alpha_dash|min_length[3]|max_length[100]|is_unique[permissions.name,id,{id}]',
        'resource'    => 'required|alpha_dash|min_length[3]|max_length[50]',
        'action'      => 'required|alpha_dash|min_length[3]|max_length[50]',
        'description' => 'permit_empty|max_length[500]'
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'Permission name is required',
            'alpha_dash'  => 'Permission name can only contain letters, numbers, dashes and underscores',
            'min_length'  => 'Permission name must be at least 3 characters long',
            'max_length'  => 'Permission name cannot exceed 100 characters',
            'is_unique'   => 'This permission name already exists'
        ],
        'resource' => [
            'required'   => 'Resource is required',
            'alpha_dash' => 'Resource can only contain letters, numbers, dashes and underscores',
            'min_length' => 'Resource must be at least 3 characters long',
            'max_length' => 'Resource cannot exceed 50 characters'
        ],
        'action' => [
            'required'   => 'Action is required',
            'alpha_dash' => 'Action can only contain letters, numbers, dashes and underscores',
            'min_length' => 'Action must be at least 3 characters long',
            'max_length' => 'Action cannot exceed 50 characters'
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
     * Get permissions grouped by resource
     */
    public function getPermissionsGroupedByResource()
    {
        $permissions = $this->orderBy('resource, action')->findAll();
        
        $grouped = [];
        foreach ($permissions as $permission) {
            $grouped[$permission['resource']][] = $permission;
        }
        
        return $grouped;
    }

    /**
     * Get permissions for a specific resource
     */
    public function getResourcePermissions($resource)
    {
        return $this->where('resource', $resource)->orderBy('action')->findAll();
    }

    /**
     * Get all available resources
     */
    public function getResources()
    {
        return $this->distinct()->select('resource')->orderBy('resource')->findAll();
    }

    /**
     * Get all available actions
     */
    public function getActions()
    {
        return $this->distinct()->select('action')->orderBy('action')->findAll();
    }

    /**
     * Check if permission exists by name
     */
    public function permissionExists($name)
    {
        return $this->where('name', $name)->countAllResults() > 0;
    }

    /**
     * Check if permission exists by resource and action
     */
    public function permissionExistsByResourceAction($resource, $action)
    {
        return $this->where('resource', $resource)
                   ->where('action', $action)
                   ->countAllResults() > 0;
    }

    /**
     * Get permission by resource and action
     */
    public function getByResourceAction($resource, $action)
    {
        return $this->where('resource', $resource)
                   ->where('action', $action)
                   ->first();
    }

    /**
     * Get roles that have this permission
     */
    public function getPermissionRoles($permissionId)
    {
        $db = \Config\Database::connect();
        
        return $db->table('role_permissions rp')
            ->select('r.id, r.name, r.display_name')
            ->join('roles r', 'r.id = rp.role_id')
            ->where('rp.permission_id', $permissionId)
            ->where('r.is_active', 1)
            ->get()
            ->getResultArray();
    }

    /**
     * Check if permission can be deleted
     */
    public function canDelete($permissionId)
    {
        $db = \Config\Database::connect();
        
        // Check if permission is assigned to any roles
        $roleCount = $db->table('role_permissions')
            ->where('permission_id', $permissionId)
            ->countAllResults();
            
        return $roleCount === 0;
    }

    /**
     * Delete permission and cleanup related data
     */
    public function deletePermission($permissionId)
    {
        if (!$this->canDelete($permissionId)) {
            return false;
        }

        $db = \Config\Database::connect();
        
        // Start transaction
        $db->transStart();
        
        // Delete role permissions
        $db->table('role_permissions')->where('permission_id', $permissionId)->delete();
        
        // Delete the permission
        $this->delete($permissionId);
        
        // Complete transaction
        $db->transComplete();
        
        return $db->transStatus();
    }

    /**
     * Create permission from resource and action
     */
    public function createFromResourceAction($resource, $action, $description = null)
    {
        $name = $resource . '.' . $action;
        
        if ($this->permissionExists($name)) {
            return false;
        }
        
        $data = [
            'name' => $name,
            'resource' => $resource,
            'action' => $action,
            'description' => $description ?: ucfirst($action) . ' ' . ucfirst($resource)
        ];
        
        return $this->insert($data);
    }

    /**
     * Bulk create permissions for a resource
     */
    public function createResourcePermissions($resource, $actions, $descriptions = [])
    {
        $data = [];
        foreach ($actions as $action) {
            $name = $resource . '.' . $action;
            
            if (!$this->permissionExists($name)) {
                $data[] = [
                    'name' => $name,
                    'resource' => $resource,
                    'action' => $action,
                    'description' => $descriptions[$action] ?? ucfirst($action) . ' ' . ucfirst($resource),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        }
        
        if (!empty($data)) {
            return $this->insertBatch($data);
        }
        
        return true;
    }
}
