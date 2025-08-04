<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-cog me-2"></i>
                        Notification Settings
                    </h5>
                    <a href="<?= site_url('notifications') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>
                        Back to Notifications
                    </a>
                </div>

                <div class="card-body">
                    <!-- Settings Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $summary['total_types'] ?></h3>
                                    <small>Total Types</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $summary['enabled_types'] ?></h3>
                                    <small>Enabled</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $summary['email_notifications'] ?></h3>
                                    <small>Email Alerts</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $summary['enabled_percentage'] ?>%</h3>
                                    <small>Enabled Rate</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Settings Form -->
                    <form id="notificationSettingsForm">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">Configure your notification preferences</h6>
                                
                                <?php if (empty($settings)): ?>
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        No notification settings found. Default settings will be created when you save.
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Notification Type</th>
                                                    <th class="text-center">Enabled</th>
                                                    <th class="text-center">Delivery Method</th>
                                                    <th class="text-center">Sound</th>
                                                    <th class="text-center">Desktop</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($settings as $setting): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar avatar-sm me-3">
                                                                    <span class="avatar-initial rounded-circle bg-<?= $setting['color'] ?>">
                                                                        <i class="bx <?= $setting['icon'] ?> text-white"></i>
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0"><?= esc($setting['display_name']) ?></h6>
                                                                    <small class="text-muted"><?= esc($setting['description']) ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" 
                                                                       type="checkbox" 
                                                                       name="settings[<?= $setting['notification_type'] ?>][enabled]"
                                                                       id="enabled_<?= $setting['notification_type'] ?>"
                                                                       value="1"
                                                                       <?= $setting['enabled'] ? 'checked' : '' ?>>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <select class="form-select form-select-sm" 
                                                                    name="settings[<?= $setting['notification_type'] ?>][delivery_method]"
                                                                    style="width: auto; display: inline-block;">
                                                                <option value="in_app" <?= $setting['delivery_method'] === 'in_app' ? 'selected' : '' ?>>
                                                                    In-App Only
                                                                </option>
                                                                <option value="email" <?= $setting['delivery_method'] === 'email' ? 'selected' : '' ?>>
                                                                    Email Only
                                                                </option>
                                                                <option value="both" <?= $setting['delivery_method'] === 'both' ? 'selected' : '' ?>>
                                                                    Both
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" 
                                                                       type="checkbox" 
                                                                       name="settings[<?= $setting['notification_type'] ?>][sound_enabled]"
                                                                       value="1"
                                                                       <?= $setting['sound_enabled'] ? 'checked' : '' ?>>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" 
                                                                       type="checkbox" 
                                                                       name="settings[<?= $setting['notification_type'] ?>][desktop_enabled]"
                                                                       value="1"
                                                                       <?= $setting['desktop_enabled'] ? 'checked' : '' ?>>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="mb-3">Quick Actions</h6>
                                <div class="btn-group me-3">
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="enableAll()">
                                        <i class="bx bx-check-circle me-1"></i>
                                        Enable All
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="disableAll()">
                                        <i class="bx bx-x-circle me-1"></i>
                                        Disable All
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="enableSounds()">
                                        <i class="bx bx-volume-full me-1"></i>
                                        Enable All Sounds
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="disableSounds()">
                                        <i class="bx bx-volume-mute me-1"></i>
                                        Disable All Sounds
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-2"></i>
                                    Save Settings
                                </button>
                                <button type="button" class="btn btn-outline-secondary ms-2" onclick="resetForm()">
                                    <i class="bx bx-reset me-2"></i>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#notificationSettingsForm').on('submit', function(e) {
        e.preventDefault();
        saveSettings();
    });
});

function saveSettings() {
    const formData = new FormData($('#notificationSettingsForm')[0]);
    
    $.ajax({
        url: '<?= site_url('notifications/update-settings') ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $('button[type="submit"]').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Saving...');
        }
    })
    .done(function(response) {
        if (response.success) {
            showToast('success', response.message);
            // Optionally reload the page to reflect changes
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', response.error);
        }
    })
    .fail(function() {
        showToast('error', 'Failed to save settings');
    })
    .always(function() {
        $('button[type="submit"]').prop('disabled', false).html('<i class="bx bx-save me-2"></i>Save Settings');
    });
}

function enableAll() {
    $('input[name*="[enabled]"]').prop('checked', true);
}

function disableAll() {
    $('input[name*="[enabled]"]').prop('checked', false);
}

function enableSounds() {
    $('input[name*="[sound_enabled]"]').prop('checked', true);
}

function disableSounds() {
    $('input[name*="[sound_enabled]"]').prop('checked', false);
}

function resetForm() {
    if (confirm('Are you sure you want to reset all settings to their original values?')) {
        location.reload();
    }
}

function showToast(type, message) {
    // Simple toast implementation - you can replace with your preferred toast library
    const toastClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const toast = $(`
        <div class="alert ${toastClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('body').append(toast);
    
    setTimeout(() => {
        toast.alert('close');
    }, 5000);
}
</script>
<?= $this->endSection() ?>
