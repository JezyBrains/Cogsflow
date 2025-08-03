<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-shield text-primary me-2"></i>
                Role & Permission Management
            </h1>
            <p class="text-muted mb-0">Manage system roles, permissions, and user access control</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleModal">
                <i class="fas fa-plus me-2"></i>Create Role
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="roleManagementTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">
                <i class="fas fa-user-shield me-2"></i>Roles
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">
                <i class="fas fa-key me-2"></i>Permissions
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                <i class="fas fa-users me-2"></i>User Assignments
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="roleManagementTabContent">
        <!-- Roles Tab -->
        <div class="tab-pane fade show active" id="roles" role="tabpanel">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">System Roles</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="rolesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Display Name</th>
                                    <th>Description</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Tab -->
        <div class="tab-pane fade" id="permissions" role="tabpanel">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">System Permissions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($permissions as $resource => $resourcePermissions): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-left-primary">
                                <div class="card-header bg-light">
                                    <h6 class="m-0 font-weight-bold text-primary text-capitalize">
                                        <i class="fas fa-cube me-2"></i><?= ucfirst(str_replace('_', ' ', $resource)) ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($resourcePermissions as $permission): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-sm">
                                            <i class="fas fa-key text-muted me-1"></i>
                                            <?= ucfirst($permission['action']) ?>
                                        </span>
                                        <code class="text-xs"><?= $permission['name'] ?></code>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Tab -->
        <div class="tab-pane fade" id="users" role="tabpanel">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">User Role Assignments</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Assigned Roles</th>
                                    <th>Role Count</th>
                                    <th>Member Since</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['username']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <?php if ($user['roles']): ?>
                                            <?php foreach (explode(', ', $user['roles']) as $role): ?>
                                                <span class="badge bg-primary me-1"><?= esc($role) ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted">No roles assigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= $user['role_count'] ?></span>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($user['user_created_at'])) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editUserRoles(<?= $user['id'] ?>)" title="Edit User Roles">
                                            <i class="fas fa-user-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Create Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="roleForm">
                <div class="modal-body">
                    <input type="hidden" id="roleId" name="role_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="roleName" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="roleName" name="name" required>
                                <div class="form-text">Use lowercase letters, numbers, and underscores only</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="roleDisplayName" class="form-label">Display Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="roleDisplayName" name="display_name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="roleDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="roleDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row" id="permissionsContainer">
                            <?php foreach ($permissions as $resource => $resourcePermissions): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-light py-2">
                                        <div class="form-check">
                                            <input class="form-check-input resource-checkbox" type="checkbox" id="resource_<?= $resource ?>" data-resource="<?= $resource ?>">
                                            <label class="form-check-label fw-bold text-capitalize" for="resource_<?= $resource ?>">
                                                <?= ucfirst(str_replace('_', ' ', $resource)) ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body py-2">
                                        <?php foreach ($resourcePermissions as $permission): ?>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="<?= $permission['id'] ?>" id="perm_<?= $permission['id'] ?>" data-resource="<?= $resource ?>">
                                            <label class="form-check-label text-sm" for="perm_<?= $permission['id'] ?>">
                                                <?= ucfirst($permission['action']) ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3" id="statusContainer" style="display: none;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="roleStatus" name="is_active" value="1" checked>
                            <label class="form-check-label" for="roleStatus">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Roles Modal -->
<div class="modal fade" id="userRolesModal" tabindex="-1" aria-labelledby="userRolesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userRolesModalLabel">Edit User Roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userRolesForm">
                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="user_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Assign Roles</label>
                        <div id="userRolesContainer">
                            <!-- Roles will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Roles
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable for roles
    $('#rolesTable').DataTable({
        ajax: {
            url: '<?= base_url('roles/get-roles-data') ?>',
            type: 'GET'
        },
        columns: [
            { data: 'name' },
            { data: 'display_name' },
            { data: 'description' },
            { data: 'permission_count', className: 'text-center' },
            { data: 'user_count', className: 'text-center' },
            { data: 'status', className: 'text-center' },
            { data: 'created_at', className: 'text-center' },
            { data: 'actions', orderable: false, className: 'text-center' }
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        responsive: true
    });

    // Initialize DataTable for users
    $('#usersTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 25,
        responsive: true
    });

    // Handle resource checkbox changes
    $(document).on('change', '.resource-checkbox', function() {
        const resource = $(this).data('resource');
        const isChecked = $(this).is(':checked');
        
        $(`.permission-checkbox[data-resource="${resource}"]`).prop('checked', isChecked);
    });

    // Handle individual permission checkbox changes
    $(document).on('change', '.permission-checkbox', function() {
        const resource = $(this).data('resource');
        const totalPerms = $(`.permission-checkbox[data-resource="${resource}"]`).length;
        const checkedPerms = $(`.permission-checkbox[data-resource="${resource}"]:checked`).length;
        
        const resourceCheckbox = $(`#resource_${resource}`);
        if (checkedPerms === 0) {
            resourceCheckbox.prop('checked', false).prop('indeterminate', false);
        } else if (checkedPerms === totalPerms) {
            resourceCheckbox.prop('checked', true).prop('indeterminate', false);
        } else {
            resourceCheckbox.prop('checked', false).prop('indeterminate', true);
        }
    });

    // Handle role form submission
    $('#roleForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const roleId = $('#roleId').val();
        const url = roleId ? `<?= base_url('roles/update') ?>/${roleId}` : '<?= base_url('roles/create') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#roleModal').modal('hide');
                    $('#rolesTable').DataTable().ajax.reload();
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                    if (response.errors) {
                        displayFormErrors(response.errors);
                    }
                }
            },
            error: function() {
                showToast('error', 'An error occurred while saving the role');
            }
        });
    });

    // Handle user roles form submission
    $('#userRolesForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const userId = $('#editUserId').val();
        
        $.ajax({
            url: `<?= base_url('roles/update-user-roles') ?>/${userId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#userRolesModal').modal('hide');
                    location.reload(); // Reload to update user roles display
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function() {
                showToast('error', 'An error occurred while updating user roles');
            }
        });
    });

    // Reset form when modal is hidden
    $('#roleModal').on('hidden.bs.modal', function() {
        $('#roleForm')[0].reset();
        $('#roleId').val('');
        $('#roleModalLabel').text('Create Role');
        $('#statusContainer').hide();
        $('.permission-checkbox').prop('checked', false);
        $('.resource-checkbox').prop('checked', false).prop('indeterminate', false);
        clearFormErrors();
    });
});

// Edit role function
function editRole(roleId) {
    $.ajax({
        url: `<?= base_url('roles/get-role') ?>/${roleId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const role = response.role;
                
                $('#roleId').val(role.id);
                $('#roleName').val(role.name);
                $('#roleDisplayName').val(role.display_name);
                $('#roleDescription').val(role.description);
                $('#roleStatus').prop('checked', role.is_active == 1);
                
                // Check permissions
                $('.permission-checkbox').prop('checked', false);
                if (role.permissions) {
                    role.permissions.forEach(function(permission) {
                        $(`#perm_${permission.id}`).prop('checked', true);
                    });
                }
                
                // Update resource checkboxes
                $('.resource-checkbox').each(function() {
                    const resource = $(this).data('resource');
                    const totalPerms = $(`.permission-checkbox[data-resource="${resource}"]`).length;
                    const checkedPerms = $(`.permission-checkbox[data-resource="${resource}"]:checked`).length;
                    
                    if (checkedPerms === 0) {
                        $(this).prop('checked', false).prop('indeterminate', false);
                    } else if (checkedPerms === totalPerms) {
                        $(this).prop('checked', true).prop('indeterminate', false);
                    } else {
                        $(this).prop('checked', false).prop('indeterminate', true);
                    }
                });
                
                $('#roleModalLabel').text('Edit Role');
                $('#statusContainer').show();
                $('#roleModal').modal('show');
            } else {
                showToast('error', response.message);
            }
        },
        error: function() {
            showToast('error', 'An error occurred while loading role data');
        }
    });
}

// Delete role function
function deleteRole(roleId) {
    if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
        $.ajax({
            url: `<?= base_url('roles/delete') ?>/${roleId}`,
            type: 'DELETE',
            success: function(response) {
                if (response.success) {
                    $('#rolesTable').DataTable().ajax.reload();
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function() {
                showToast('error', 'An error occurred while deleting the role');
            }
        });
    }
}

// Manage permissions function
function managePermissions(roleId) {
    // This could open a separate modal or redirect to a detailed permissions page
    editRole(roleId);
}

// Assign users function
function assignUsers(roleId) {
    // This could open a separate modal for user assignment
    showToast('info', 'User assignment feature coming soon');
}

// Edit user roles function
function editUserRoles(userId) {
    $.ajax({
        url: `<?= base_url('roles/get-user-roles') ?>/${userId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#editUserId').val(userId);
                
                // Build roles checkboxes
                let rolesHtml = '';
                <?php foreach ($roles as $role): ?>
                const hasRole<?= $role['id'] ?> = response.roles.some(r => r.role_id == <?= $role['id'] ?>);
                rolesHtml += `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" value="<?= $role['id'] ?>" id="userRole<?= $role['id'] ?>" ${hasRole<?= $role['id'] ?> ? 'checked' : ''}>
                        <label class="form-check-label" for="userRole<?= $role['id'] ?>">
                            <?= esc($role['display_name']) ?>
                            <small class="text-muted d-block"><?= esc($role['description']) ?></small>
                        </label>
                    </div>
                `;
                <?php endforeach; ?>
                
                $('#userRolesContainer').html(rolesHtml);
                $('#userRolesModal').modal('show');
            } else {
                showToast('error', response.message);
            }
        },
        error: function() {
            showToast('error', 'An error occurred while loading user roles');
        }
    });
}

// Utility functions
function showToast(type, message) {
    // Implementation depends on your toast library
    console.log(`${type}: ${message}`);
}

function displayFormErrors(errors) {
    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Display new errors
    for (const field in errors) {
        const input = $(`[name="${field}"]`);
        input.addClass('is-invalid');
        input.after(`<div class="invalid-feedback">${errors[field]}</div>`);
    }
}

function clearFormErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
}
</script>
<?= $this->endSection() ?>
