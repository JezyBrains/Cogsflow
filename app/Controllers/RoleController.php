<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\UserRoleModel;
use App\Models\SystemLogModel;

class RoleController extends BaseController
{
    protected $roleModel;
    protected $permissionModel;
    protected $userRoleModel;
    protected $systemLogModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
        $this->userRoleModel = new UserRoleModel();
        $this->systemLogModel = new SystemLogModel();
    }

    /**
     * Display role management interface
     */
    public function index()
    {
        $data = [
            'title' => 'Role Management',
            'roles' => $this->roleModel->getRolesWithPermissionCounts(),
            'permissions' => $this->permissionModel->getPermissionsGroupedByResource(),
            'users' => $this->userRoleModel->getUsersWithRoles()
        ];

        return view('roles/index', $data);
    }

    /**
     * Get roles data for DataTables
     */
    public function getRolesData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $roles = $this->roleModel->getRolesWithPermissionCounts();
        
        $data = [];
        foreach ($roles as $role) {
            $userCount = $this->userRoleModel->where('role_id', $role['id'])
                                           ->where('is_active', 1)
                                           ->countAllResults();
            
            $actions = '<div class="btn-group" role="group">';
            $actions .= '<button type="button" class="btn btn-sm btn-outline-primary" onclick="editRole(' . $role['id'] . ')" title="Edit Role">';
            $actions .= '<i class="fas fa-edit"></i></button>';
            $actions .= '<button type="button" class="btn btn-sm btn-outline-info" onclick="managePermissions(' . $role['id'] . ')" title="Manage Permissions">';
            $actions .= '<i class="fas fa-key"></i></button>';
            $actions .= '<button type="button" class="btn btn-sm btn-outline-success" onclick="assignUsers(' . $role['id'] . ')" title="Assign Users">';
            $actions .= '<i class="fas fa-users"></i></button>';
            
            if ($this->roleModel->canDelete($role['id'])) {
                $actions .= '<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRole(' . $role['id'] . ')" title="Delete Role">';
                $actions .= '<i class="fas fa-trash"></i></button>';
            }
            $actions .= '</div>';

            $status = $role['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';

            $data[] = [
                'id' => $role['id'],
                'name' => $role['name'],
                'display_name' => $role['display_name'],
                'description' => $role['description'] ?: '-',
                'permission_count' => $role['permission_count'],
                'user_count' => $userCount,
                'status' => $status,
                'created_at' => date('M j, Y', strtotime($role['created_at'])),
                'actions' => $actions
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * Create new role
     */
    public function create()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $rules = [
            'name' => 'required|alpha_dash|min_length[3]|max_length[50]|is_unique[roles.name]',
            'display_name' => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[500]',
            'permissions' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'display_name' => $this->request->getPost('display_name'),
            'description' => $this->request->getPost('description'),
            'is_active' => 1
        ];

        $roleId = $this->roleModel->insert($data);

        if (!$roleId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create role',
                'errors' => $this->roleModel->errors()
            ]);
        }

        // Assign permissions if provided
        $permissions = $this->request->getPost('permissions');
        if (!empty($permissions)) {
            $this->roleModel->assignPermissions($roleId, $permissions);
        }

        // Log the action
        $this->systemLogModel->logAction(
            'role_created',
            'Role created: ' . $data['display_name'],
            ['role_id' => $roleId, 'role_data' => $data],
            session()->get('user_id')
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Role created successfully',
            'role_id' => $roleId
        ]);
    }

    /**
     * Get role details for editing
     */
    public function getRole($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $role = $this->roleModel->getRoleWithPermissions($id);
        
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'role' => $role
        ]);
    }

    /**
     * Update existing role
     */
    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }

        $rules = [
            'name' => "required|alpha_dash|min_length[3]|max_length[50]|is_unique[roles.name,id,{$id}]",
            'display_name' => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[500]',
            'is_active' => 'permit_empty|in_list[0,1]',
            'permissions' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'display_name' => $this->request->getPost('display_name'),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        if (!$this->roleModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update role',
                'errors' => $this->roleModel->errors()
            ]);
        }

        // Update permissions if provided
        $permissions = $this->request->getPost('permissions');
        if ($permissions !== null) {
            $this->roleModel->assignPermissions($id, $permissions);
        }

        // Log the action
        $this->systemLogModel->logAction(
            'role_updated',
            'Role updated: ' . $data['display_name'],
            ['role_id' => $id, 'role_data' => $data],
            session()->get('user_id')
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Role updated successfully'
        ]);
    }

    /**
     * Delete role
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }

        if (!$this->roleModel->canDelete($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete role. It is assigned to active users.'
            ]);
        }

        if (!$this->roleModel->deleteRole($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete role'
            ]);
        }

        // Log the action
        $this->systemLogModel->logAction(
            'role_deleted',
            'Role deleted: ' . $role['display_name'],
            ['role_id' => $id, 'role_data' => $role],
            session()->get('user_id')
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    /**
     * Manage role permissions
     */
    public function managePermissions($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        if ($this->request->getMethod() === 'POST') {
            // Update permissions
            $permissions = $this->request->getPost('permissions') ?? [];
            
            if ($this->roleModel->assignPermissions($id, $permissions)) {
                // Log the action
                $role = $this->roleModel->find($id);
                $this->systemLogModel->logAction(
                    'role_permissions_updated',
                    'Permissions updated for role: ' . $role['display_name'],
                    ['role_id' => $id, 'permission_ids' => $permissions],
                    session()->get('user_id')
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Permissions updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update permissions'
                ]);
            }
        }

        // Get role with current permissions
        $role = $this->roleModel->getRoleWithPermissions($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }

        $allPermissions = $this->permissionModel->getPermissionsGroupedByResource();
        $currentPermissionIds = array_column($role['permissions'], 'id');

        return $this->response->setJSON([
            'success' => true,
            'role' => $role,
            'all_permissions' => $allPermissions,
            'current_permissions' => $currentPermissionIds
        ]);
    }

    /**
     * Assign users to role
     */
    public function assignUsers($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        if ($this->request->getMethod() === 'POST') {
            // Update user assignments
            $userIds = $this->request->getPost('users') ?? [];
            $assignedBy = session()->get('user_id');
            
            // Get current users
            $currentUsers = $this->userRoleModel->getUsersByRole($id);
            $currentUserIds = array_column($currentUsers, 'id');
            
            // Remove users no longer assigned
            foreach ($currentUserIds as $userId) {
                if (!in_array($userId, $userIds)) {
                    $this->userRoleModel->removeRole($userId, $id);
                }
            }
            
            // Add new users
            foreach ($userIds as $userId) {
                if (!in_array($userId, $currentUserIds)) {
                    $this->userRoleModel->assignRole($userId, $id, $assignedBy);
                }
            }

            // Log the action
            $role = $this->roleModel->find($id);
            $this->systemLogModel->logAction(
                'role_users_updated',
                'User assignments updated for role: ' . $role['display_name'],
                ['role_id' => $id, 'user_ids' => $userIds],
                $assignedBy
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User assignments updated successfully'
            ]);
        }

        // Get role and available users
        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }

        // Get all users and current assignments
        $db = \Config\Database::connect();
        $allUsers = $db->table('users')
            ->select('id, username, email')
            ->orderBy('username')
            ->get()
            ->getResultArray();

        $currentUsers = $this->userRoleModel->getUsersByRole($id);
        $currentUserIds = array_column($currentUsers, 'id');

        return $this->response->setJSON([
            'success' => true,
            'role' => $role,
            'all_users' => $allUsers,
            'current_users' => $currentUserIds
        ]);
    }

    /**
     * Get user roles and permissions
     */
    public function getUserRoles($userId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $userRoles = $this->userRoleModel->getUserRoles($userId);
        $userPermissions = $this->userRoleModel->getUserPermissions($userId);

        return $this->response->setJSON([
            'success' => true,
            'roles' => $userRoles,
            'permissions' => $userPermissions
        ]);
    }

    /**
     * Update user roles
     */
    public function updateUserRoles($userId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $roleIds = $this->request->getPost('roles') ?? [];
        $assignedBy = session()->get('user_id');

        if ($this->userRoleModel->updateUserRoles($userId, $roleIds, $assignedBy)) {
            // Log the action
            $this->systemLogModel->logAction(
                'user_roles_updated',
                'Roles updated for user ID: ' . $userId,
                ['user_id' => $userId, 'role_ids' => $roleIds],
                $assignedBy
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User roles updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update user roles'
            ]);
        }
    }
}
