<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $title ?? 'System Settings' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="mb-1">System Settings & Admin Panel</h4>
            <p class="text-muted">Manage system configuration and administrative utilities</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <!-- Export Dropdown -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Export Formats</h6></li>
                        <li><a class="dropdown-item" href="#" onclick="exportSettings('json')">
                            <i class="bx bx-file-blank me-2"></i>JSON Format
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportSettings('pdf')">
                            <i class="bx bx-file-pdf me-2"></i>PDF Report
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportSettings('excel')">
                            <i class="bx bx-spreadsheet me-2"></i>Excel Spreadsheet
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="exportSettings('backup')">
                            <i class="bx bx-archive me-2"></i>Full Backup
                        </a></li>
                    </ul>
                </div>
                <!-- Import Button -->
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bx bx-upload me-1"></i> Import
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- System Health Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0">
                        <i class="fas fa-heartbeat text-primary me-2"></i>System Health Status
                        <button class="btn btn-sm btn-outline-primary float-end" onclick="refreshHealthStatus()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </h6>
                </div>
                <div class="card-body">
                    <div id="health-status">
                        <?php if(isset($healthStatus)): ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <div class="badge badge-<?= $healthStatus['overall'] === 'healthy' ? 'success' : 'warning' ?> fs-6 p-2">
                                            <?= ucfirst($healthStatus['overall']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <?php foreach($healthStatus['checks'] as $check => $status): ?>
                                            <div class="col-md-6 mb-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-<?= $status['status'] === 'healthy' ? 'check text-success' : ($status['status'] === 'warning' ? 'exclamation-triangle text-warning' : 'times text-danger') ?> me-2"></i>
                                                    <small><?= $status['message'] ?></small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab">
                                <i class="fas fa-building me-2"></i>Company
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                                <i class="fas fa-cog me-2"></i>System
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="business-tab" data-bs-toggle="tab" data-bs-target="#business" type="button" role="tab">
                                <i class="fas fa-chart-line me-2"></i>Business
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                <i class="fas fa-shield-alt me-2"></i>Security
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button" role="tab">
                                <i class="fas fa-tools me-2"></i>Admin Tools
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="settingsTabsContent">
                        <!-- Company Settings -->
                        <div class="tab-pane fade show active" id="company" role="tabpanel">
                            <form action="<?= site_url('settings/update') ?>" method="post" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <input type="hidden" name="category" value="company">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Company Name *</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" 
                                                   value="<?= $settings['company']['company_name']['value'] ?? '' ?>" required>
                                            <div class="form-text"><?= $settings['company']['company_name']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_email" class="form-label">Company Email *</label>
                                            <input type="email" class="form-control" id="company_email" name="company_email" 
                                                   value="<?= $settings['company']['company_email']['value'] ?? '' ?>" required>
                                            <div class="form-text"><?= $settings['company']['company_email']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_phone" class="form-label">Company Phone</label>
                                            <input type="text" class="form-control" id="company_phone" name="company_phone" 
                                                   value="<?= $settings['company']['company_phone']['value'] ?? '' ?>">
                                            <div class="form-text"><?= $settings['company']['company_phone']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_address" class="form-label">Company Address</label>
                                            <textarea class="form-control" id="company_address" name="company_address" rows="3"><?= $settings['company']['company_address']['value'] ?? '' ?></textarea>
                                            <div class="form-text"><?= $settings['company']['company_address']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Company Settings
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- System Settings -->
                        <div class="tab-pane fade" id="system" role="tabpanel">
                            <form action="<?= site_url('settings/update') ?>" method="post" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <input type="hidden" name="category" value="system">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="system_name" class="form-label">System Name *</label>
                                            <input type="text" class="form-control" id="system_name" name="system_name" 
                                                   value="<?= $settings['system']['system_name']['value'] ?? '' ?>" required>
                                            <div class="form-text"><?= $settings['system']['system_name']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="base_url" class="form-label">Base URL *</label>
                                            <input type="url" class="form-control" id="base_url" name="base_url" 
                                                   value="<?= $settings['system']['base_url']['value'] ?? '' ?>" required>
                                            <div class="form-text"><?= $settings['system']['base_url']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="default_currency" class="form-label">Default Currency</label>
                                            <select class="form-select" id="default_currency" name="default_currency">
                                                <option value="KES" <?= ($settings['system']['default_currency']['value'] ?? '') === 'KES' ? 'selected' : '' ?>>KES - Kenyan Shilling</option>
                                                <option value="USD" <?= ($settings['system']['default_currency']['value'] ?? '') === 'USD' ? 'selected' : '' ?>>USD - US Dollar</option>
                                                <option value="EUR" <?= ($settings['system']['default_currency']['value'] ?? '') === 'EUR' ? 'selected' : '' ?>>EUR - Euro</option>
                                                <option value="GBP" <?= ($settings['system']['default_currency']['value'] ?? '') === 'GBP' ? 'selected' : '' ?>>GBP - British Pound</option>
                                            </select>
                                            <div class="form-text"><?= $settings['system']['default_currency']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="default_timezone" class="form-label">Default Timezone</label>
                                            <select class="form-select" id="default_timezone" name="default_timezone">
                                                <option value="Africa/Nairobi" <?= ($settings['system']['default_timezone']['value'] ?? '') === 'Africa/Nairobi' ? 'selected' : '' ?>>Africa/Nairobi</option>
                                                <option value="UTC" <?= ($settings['system']['default_timezone']['value'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                                <option value="America/New_York" <?= ($settings['system']['default_timezone']['value'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>America/New_York</option>
                                                <option value="Europe/London" <?= ($settings['system']['default_timezone']['value'] ?? '') === 'Europe/London' ? 'selected' : '' ?>>Europe/London</option>
                                            </select>
                                            <div class="form-text"><?= $settings['system']['default_timezone']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="date_format" class="form-label">Date Format</label>
                                            <select class="form-select" id="date_format" name="date_format">
                                                <option value="Y-m-d" <?= ($settings['system']['date_format']['value'] ?? '') === 'Y-m-d' ? 'selected' : '' ?>>YYYY-MM-DD</option>
                                                <option value="d/m/Y" <?= ($settings['system']['date_format']['value'] ?? '') === 'd/m/Y' ? 'selected' : '' ?>>DD/MM/YYYY</option>
                                                <option value="m/d/Y" <?= ($settings['system']['date_format']['value'] ?? '') === 'm/d/Y' ? 'selected' : '' ?>>MM/DD/YYYY</option>
                                                <option value="d-M-Y" <?= ($settings['system']['date_format']['value'] ?? '') === 'd-M-Y' ? 'selected' : '' ?>>DD-MMM-YYYY</option>
                                            </select>
                                            <div class="form-text"><?= $settings['system']['date_format']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update System Settings
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Business Settings -->
                        <div class="tab-pane fade" id="business" role="tabpanel">
                            <form action="<?= site_url('settings/update') ?>" method="post" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <input type="hidden" name="category" value="business">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                                            <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" 
                                                   value="<?= $settings['business']['low_stock_threshold']['value'] ?? '' ?>" min="1">
                                            <div class="form-text"><?= $settings['business']['low_stock_threshold']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="backup_retention_days" class="form-label">Backup Retention (Days)</label>
                                            <input type="number" class="form-control" id="backup_retention_days" name="backup_retention_days" 
                                                   value="<?= $settings['business']['backup_retention_days']['value'] ?? '' ?>" min="1" max="365">
                                            <div class="form-text"><?= $settings['business']['backup_retention_days']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="enable_notifications" name="enable_notifications" 
                                                       value="1" <?= ($settings['business']['enable_notifications']['value'] ?? false) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="enable_notifications">
                                                    Enable Notifications
                                                </label>
                                            </div>
                                            <div class="form-text"><?= $settings['business']['enable_notifications']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="auto_backup" name="auto_backup" 
                                                       value="1" <?= ($settings['business']['auto_backup']['value'] ?? false) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="auto_backup">
                                                    Enable Auto Backup
                                                </label>
                                            </div>
                                            <div class="form-text"><?= $settings['business']['auto_backup']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Business Settings
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Security Settings -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <form action="<?= site_url('settings/update') ?>" method="post" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <input type="hidden" name="category" value="security">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="session_timeout" class="form-label">Session Timeout (seconds)</label>
                                            <input type="number" class="form-control" id="session_timeout" name="session_timeout" 
                                                   value="<?= $settings['security']['session_timeout']['value'] ?? '' ?>" min="300" max="86400">
                                            <div class="form-text"><?= $settings['security']['session_timeout']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_min_length" class="form-label">Minimum Password Length</label>
                                            <input type="number" class="form-control" id="password_min_length" name="password_min_length" 
                                                   value="<?= $settings['security']['password_min_length']['value'] ?? '' ?>" min="6" max="50">
                                            <div class="form-text"><?= $settings['security']['password_min_length']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="enable_2fa" name="enable_2fa" 
                                                       value="1" <?= ($settings['security']['enable_2fa']['value'] ?? false) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="enable_2fa">
                                                    Enable Two-Factor Authentication
                                                </label>
                                            </div>
                                            <div class="form-text"><?= $settings['security']['enable_2fa']['description'] ?? '' ?></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Security Settings
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Admin Tools -->
                        <div class="tab-pane fade" id="admin" role="tabpanel">
                            <div class="row">
                                <!-- System Utilities -->
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-header bg-transparent">
                                            <h6 class="mb-0"><i class="fas fa-tools text-primary me-2"></i>System Utilities</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-outline-primary" onclick="adminUtility('clear_cache')">
                                                    <i class="fas fa-broom me-2"></i>Clear Cache
                                                </button>
                                                <button type="button" class="btn btn-outline-warning" onclick="adminUtility('reset_queue')">
                                                    <i class="fas fa-redo me-2"></i>Reset Queue Jobs
                                                </button>
                                                <button type="button" class="btn btn-outline-info" onclick="adminUtility('optimize_database')">
                                                    <i class="fas fa-database me-2"></i>Optimize Database
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Backup & Logs -->
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-header bg-transparent">
                                            <h6 class="mb-0"><i class="fas fa-archive text-success me-2"></i>Backup & Logs</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-outline-success" onclick="adminUtility('trigger_backup')">
                                                    <i class="fas fa-download me-2"></i>Trigger Backup
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" onclick="cleanLogs()">
                                                    <i class="fas fa-trash me-2"></i>Clean Old Logs
                                                </button>
                                                <a href="<?= site_url('settings/logs') ?>" class="btn btn-outline-info">
                                                    <i class="fas fa-list me-2"></i>View System Logs
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- System Information -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border-0 bg-light">
                                        <div class="card-header bg-transparent">
                                            <h6 class="mb-0">
                                                <i class="fas fa-info-circle text-info me-2"></i>System Information
                                                <button class="btn btn-sm btn-outline-info float-end" onclick="loadSystemInfo()">
                                                    <i class="fas fa-sync-alt"></i> Refresh
                                                </button>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="system-info" class="row">
                                                <div class="col-12 text-center">
                                                    <p class="text-muted">Click refresh to load system information</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Settings Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('settings/importSettings') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="settings_file" class="form-label">Settings File (JSON)</label>
                        <input type="file" class="form-control" id="settings_file" name="settings_file" accept=".json" required>
                        <div class="form-text">Select a JSON file exported from this system</div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> Importing settings will overwrite existing values. Make sure to export current settings first as backup.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Clean Logs Modal -->
<div class="modal fade" id="cleanLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clean System Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="log_retention_days" class="form-label">Keep logs from last (days)</label>
                    <input type="number" class="form-control" id="log_retention_days" value="30" min="1" max="365">
                    <div class="form-text">Logs older than this will be permanently deleted</div>
                </div>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. Deleted logs cannot be recovered.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmCleanLogs()">Clean Logs</button>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Admin utilities
function adminUtility(action) {
    if (!confirm('Are you sure you want to perform this action?')) {
        return;
    }
    
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    btn.disabled = true;
    
    fetch('<?= site_url('settings/adminUtility') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'action=' + action + '&<?= csrf_token() ?>=' + '<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        showAlert('danger', 'An error occurred: ' + error.message);
    });
}

// Load system information
function loadSystemInfo() {
    const container = document.getElementById('system-info');
    container.innerHTML = '<div class="col-12 text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    
    fetch('<?= site_url('settings/systemInfo') ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let html = '';
            Object.keys(data.data).forEach(key => {
                const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                html += `
                    <div class="col-md-6 mb-2">
                        <strong>${label}:</strong> ${data.data[key]}
                    </div>
                `;
            });
            container.innerHTML = html;
        } else {
            container.innerHTML = '<div class="col-12 text-center text-danger">Failed to load system information</div>';
        }
    })
    .catch(error => {
        container.innerHTML = '<div class="col-12 text-center text-danger">Error loading system information</div>';
    });
}

// Export settings
function exportSettings(format = 'json') {
    // Show loading state
    const loadingToast = showLoadingToast('Preparing export...');
    
    // Build the export URL with format parameter
    const exportUrl = '<?= site_url('settings/exportSettings') ?>?format=' + format;
    
    // Handle different export types
    switch(format) {
        case 'pdf':
            // For PDF, open in new tab to allow preview/download
            window.open(exportUrl, '_blank');
            break;
        case 'excel':
        case 'json':
        case 'backup':
            // For file downloads, use direct navigation
            window.location.href = exportUrl;
            break;
        default:
            window.location.href = exportUrl;
    }
    
    // Hide loading toast after a short delay
    setTimeout(() => {
        hideLoadingToast(loadingToast);
    }, 2000);
}

// Clean logs
function cleanLogs() {
    const modal = new bootstrap.Modal(document.getElementById('cleanLogsModal'));
    modal.show();
}

function confirmCleanLogs() {
    const days = document.getElementById('log_retention_days').value;
    const modal = bootstrap.Modal.getInstance(document.getElementById('cleanLogsModal'));
    modal.hide();
    
    fetch('<?= site_url('settings/adminUtility') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'action=clean_logs&days=' + days + '&<?= csrf_token() ?>=' + '<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'An error occurred: ' + error.message);
    });
}

// Refresh health status
function refreshHealthStatus() {
    const container = document.getElementById('health-status');
    container.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Refreshing...</div>';
    
    // Reload the page to refresh health status
    window.location.reload();
}

// Show alert
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

// Show loading toast
function showLoadingToast(message) {
    const toastId = 'loading-toast-' + Date.now();
    const toastHtml = `
        <div class="toast align-items-center text-white bg-primary border-0 position-fixed" 
             id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true" 
             style="top: 20px; right: 20px; z-index: 9999;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-spinner fa-spin me-2"></i>${message}
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { autohide: false });
    toast.show();
    
    return toastId;
}

// Hide loading toast
function hideLoadingToast(toastId) {
    const toastElement = document.getElementById(toastId);
    if (toastElement) {
        const toast = bootstrap.Toast.getInstance(toastElement);
        if (toast) {
            toast.hide();
        }
        setTimeout(() => {
            toastElement.remove();
        }, 500);
    }
}
</script>

<?= $this->endSection() ?>
