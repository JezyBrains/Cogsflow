<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $title ?? 'System Settings' ?><?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
/* Fix modal overlay issues for settings page */
.modal {
    z-index: 9999 !important;
}
.modal-backdrop {
    z-index: 9998 !important;
}
.modal-dialog {
    z-index: 10000 !important;
    pointer-events: auto !important;
    position: relative !important;
}
.modal-content {
    z-index: 10001 !important;
    pointer-events: auto !important;
    position: relative !important;
    background-color: #fff !important;
    border: 1px solid rgba(0,0,0,.2) !important;
    border-radius: 0.5rem !important;
    box-shadow: 0 0.5rem 1rem rgba(165, 163, 174, 0.15) !important;
}
.modal.show {
    display: block !important;
}
.modal .btn-close {
    z-index: 10002 !important;
    position: relative !important;
}
body.modal-open {
    overflow: hidden !important;
}

/* Enhanced visual feedback styles */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    border-radius: inherit;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { opacity: 0.3; }
    50% { opacity: 0.7; }
    100% { opacity: 0.3; }
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% { background-position: 1rem 0; }
    100% { background-position: 0 0; }
}

.alert-enhanced {
    border-left: 4px solid;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.alert-success.alert-enhanced {
    border-left-color: #28a745;
}

.alert-danger.alert-enhanced {
    border-left-color: #dc3545;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
}

.badge {
    font-size: 0.75em;
}

.card-stats {
    transition: transform 0.2s;
}

.card-stats:hover {
    transform: translateY(-2px);
}

/* Loading toast styles */
.toast {
    min-width: 300px;
}

.toast .toast-body {
    font-weight: 500;
}

/* Modal enhancements */
.modal-xl {
    max-width: 1200px;
}

.modal-header.bg-info {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.modal-header.bg-danger {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

/* Progress section styling */
#resetProgressSection {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 2px solid #dee2e6;
}

#resetProgressSection .progress {
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}

#resetProgressSection .card-body {
    padding: 1rem;
}

/* Debug modal enhancements */
#debugModal .table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

#debugModal .table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

/* Button success animation */
.btn-success {
    transition: all 0.3s ease;
    transform: scale(1.05);
}

/* Detailed log styling */
#detailedLog pre {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    line-height: 1.4;
}

#detailedLog .card-body {
    padding: 1rem;
}

/* Status message animations */
.alert {
    animation: slideInDown 0.3s ease-out;
}

@keyframes slideInDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
<?= $this->endSection() ?>

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
                                    <button type="submit" class="btn btn-success">
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
                                                <option value="TSH" <?= ($settings['system']['default_currency']['value'] ?? '') === 'TSH' ? 'selected' : '' ?>>TSH - Tanzanian Shilling</option>
                                                <option value="USD" <?= ($settings['system']['default_currency']['value'] ?? '') === 'USD' ? 'selected' : '' ?>>USD - US Dollar</option>
                                                <!-- <option value="EUR" <?= ($settings['system']['default_currency']['value'] ?? '') === 'EUR' ? 'selected' : '' ?>>EUR - Euro</option> -->
                                                <!-- <option value="GBP" <?= ($settings['system']['default_currency']['value'] ?? '') === 'GBP' ? 'selected' : '' ?>>GBP - British Pound</option> -->
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
                                
                                <!-- Unit of Measure Settings -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6 class="text-muted mb-3">
                                            <i class="bx bx-ruler me-2"></i>Unit of Measure Settings
                                        </h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="default_weight_unit" class="form-label">Default Weight Unit *</label>
                                            <select class="form-select" id="default_weight_unit" name="default_weight_unit" required>
                                                <option value="kg" <?= ($settings['system']['default_weight_unit']['value'] ?? 'kg') === 'kg' ? 'selected' : '' ?>>Kilograms (kg)</option>
                                                <option value="mt" <?= ($settings['system']['default_weight_unit']['value'] ?? '') === 'mt' ? 'selected' : '' ?>>Metric Tonnes (MT)</option>
                                                <option value="ton" <?= ($settings['system']['default_weight_unit']['value'] ?? '') === 'ton' ? 'selected' : '' ?>>Tonnes (ton)</option>
                                                <option value="lbs" <?= ($settings['system']['default_weight_unit']['value'] ?? '') === 'lbs' ? 'selected' : '' ?>>Pounds (lbs)</option>
                                                <option value="g" <?= ($settings['system']['default_weight_unit']['value'] ?? '') === 'g' ? 'selected' : '' ?>>Grams (g)</option>
                                            </select>
                                            <div class="form-text">
                                                <i class="bx bx-info-circle me-1"></i>
                                                This unit will be used throughout the system for all weight measurements (Purchase Orders, Batches, Inventory, etc.)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="show_secondary_unit" class="form-label">Show Secondary Unit</label>
                                            <select class="form-select" id="show_secondary_unit" name="show_secondary_unit">
                                                <option value="1" <?= ($settings['system']['show_secondary_unit']['value'] ?? '1') === '1' ? 'selected' : '' ?>>Yes - Show conversion (e.g., 1000 kg = 1 MT)</option>
                                                <option value="0" <?= ($settings['system']['show_secondary_unit']['value'] ?? '') === '0' ? 'selected' : '' ?>>No - Show primary unit only</option>
                                            </select>
                                            <div class="form-text">
                                                <i class="bx bx-info-circle me-1"></i>
                                                Display weight in both primary and secondary units for clarity
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Unit Conversion Preview -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading">
                                                <i class="bx bx-calculator me-2"></i>Unit Conversion Preview
                                            </h6>
                                            <div id="unit-preview">
                                                <p class="mb-1"><strong>Example conversions with selected unit:</strong></p>
                                                <ul class="mb-0">
                                                    <li>1 kg = 0.001 MT</li>
                                                    <li>1000 kg = 1 MT</li>
                                                    <li>1 MT = 1000 kg</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
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

                            <!-- Database Management -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border-danger bg-light">
                                        <div class="card-header bg-transparent border-danger">
                                            <h6 class="mb-0 text-danger">
                                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>Database Management
                                                <small class="text-muted">(Dangerous Operations)</small>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-danger mb-3">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Warning:</strong> These operations will permanently delete data. A backup will be created automatically before any operation.
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="d-grid gap-2">
                                                        <button type="button" class="btn btn-outline-warning" onclick="showDatabaseResetModal('clear_data')">
                                                            <i class="fas fa-eraser me-2"></i>Clear All Data
                                                        </button>
                                                        <small class="text-muted">Removes all data but keeps table structure and system settings</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-grid gap-2">
                                                        <button type="button" class="btn btn-outline-danger" onclick="showDatabaseResetModal('reset_database')">
                                                            <i class="fas fa-database me-2"></i>Reset Database
                                                        </button>
                                                        <small class="text-muted">Completely resets database to fresh installation state</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="debugDatabase()">
                                                        <i class="fas fa-bug me-2"></i>Debug Database Tables
                                                    </button>
                                                    <small class="text-muted ms-2">Show table information for troubleshooting</small>
                                                </div>
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

<!-- Database Reset Confirmation Modal -->
<div class="modal fade" id="databaseResetModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="resetModalTitle">Confirm Database Operation</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-triangle me-2"></i>DANGER: This action cannot be undone!
                    </h6>
                    <p class="mb-0" id="resetModalDescription">This operation will permanently delete data from your database.</p>
                </div>
                
                <div class="mb-3">
                    <h6>What will happen:</h6>
                    <ul id="resetModalSteps">
                        <li>A backup will be created automatically</li>
                        <li>Selected data will be permanently deleted</li>
                        <li>The operation cannot be reversed</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <strong>Type "CONFIRM" to proceed:</strong>
                    </label>
                    <input type="text" class="form-control" id="confirmationText" placeholder="Type CONFIRM here">
                    <div class="form-text">This confirmation is required to prevent accidental data loss.</div>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="acknowledgeRisk">
                    <label class="form-check-label" for="acknowledgeRisk">
                        I understand that this action will permanently delete data and cannot be undone
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelResetBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmResetBtn" onclick="confirmDatabaseReset()" disabled>
                    <i class="fas fa-exclamation-triangle me-2"></i>Proceed with Operation
                </button>
            </div>
            
            <!-- Progress Section (Hidden by default) -->
            <div id="resetProgressSection" class="modal-body border-top" style="display: none;">
                <div class="text-center">
                    <h6 class="mb-3">
                        <i class="fas fa-cog fa-spin me-2"></i>
                        <span id="progressTitle">Processing Database Reset...</span>
                    </h6>
                    
                    <!-- Progress Bar -->
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             id="resetProgressBar" role="progressbar" style="width: 0%">
                            <span id="progressText">0%</span>
                        </div>
                    </div>
                    
                    <!-- Status Messages -->
                    <div id="statusMessages" class="text-start">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="currentStatus">Initializing...</span>
                        </div>
                    </div>
                    
                    <!-- Detailed Log -->
                    <div class="mt-3">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#detailedLog">
                            <i class="fas fa-list me-1"></i>Show Detailed Log
                        </button>
                        <div class="collapse mt-2" id="detailedLog">
                            <div class="card card-body bg-dark text-light" style="max-height: 200px; overflow-y: auto;">
                                <pre id="logContent" class="mb-0 text-light"></pre>
                            </div>
                        </div>
                    </div>
                </div>
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
    // Get action description for better UX
    const actionDescriptions = {
        'clear_cache': 'Clear System Cache',
        'reset_queue': 'Reset Queue Jobs',
        'optimize_database': 'Optimize Database',
        'trigger_backup': 'Create Database Backup',
        'clean_logs': 'Clean System Logs'
    };
    
    const actionName = actionDescriptions[action] || action;
    
    if (!confirm(`Are you sure you want to perform this action: ${actionName}?`)) {
        return;
    }
    
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    // Show enhanced loading state
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    btn.disabled = true;
    btn.classList.add('btn-loading');
    
    // Show loading toast
    const loadingToast = showLoadingToast(`Executing ${actionName}...`);
    
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
        // Hide loading toast
        hideLoadingToast(loadingToast);
        
        // Reset button state
        btn.innerHTML = originalText;
        btn.disabled = false;
        btn.classList.remove('btn-loading');
        
        if (data.success) {
            // Show success with enhanced feedback
            showEnhancedAlert('success', actionName + ' Completed', data.message);
            
            // Add success animation
            btn.classList.add('btn-success');
            setTimeout(() => {
                btn.classList.remove('btn-success');
            }, 2000);
            
        } else {
            showEnhancedAlert('danger', actionName + ' Failed', data.message);
        }
    })
    .catch(error => {
        hideLoadingToast(loadingToast);
        
        // Reset button state
        btn.innerHTML = originalText;
        btn.disabled = false;
        btn.classList.remove('btn-loading');
        
        showEnhancedAlert('danger', 'Network Error', 'An error occurred: ' + error.message);
    });
}

// Enhanced alert function
function showEnhancedAlert(type, title, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} fa-2x"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">${title}</h6>
                    <p class="mb-0">${message}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-dismiss after 8 seconds for enhanced alerts
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 8000);
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

// Database reset modal functions
let currentResetAction = '';

function showDatabaseResetModal(action) {
    currentResetAction = action;
    
    const modal = new bootstrap.Modal(document.getElementById('databaseResetModal'));
    const title = document.getElementById('resetModalTitle');
    const description = document.getElementById('resetModalDescription');
    const steps = document.getElementById('resetModalSteps');
    
    // Reset form
    document.getElementById('confirmationText').value = '';
    document.getElementById('acknowledgeRisk').checked = false;
    document.getElementById('confirmResetBtn').disabled = true;
    
    if (action === 'clear_data') {
        title.textContent = 'Clear All Data';
        description.textContent = 'This will remove all business data (batches, dispatches, inventory, etc.) but keep system settings, users, and table structure intact.';
        steps.innerHTML = `
            <li>A complete database backup will be created</li>
            <li>All business data will be permanently deleted</li>
            <li>System settings and users will be preserved</li>
            <li>Table structure will remain intact</li>
            <li>You can start fresh with clean data</li>
        `;
    } else if (action === 'reset_database') {
        title.textContent = 'Reset Database Completely';
        description.textContent = 'This will completely reset the database to a fresh installation state. ALL data including users and settings will be lost.';
        steps.innerHTML = `
            <li>A complete database backup will be created</li>
            <li>All tables will be dropped and recreated</li>
            <li>All data including users will be permanently deleted</li>
            <li>Essential system data will be restored</li>
            <li>You will need to log in with default admin credentials</li>
        `;
    }
    
    modal.show();
}

function confirmDatabaseReset() {
    const confirmText = document.getElementById('confirmationText').value;
    const acknowledged = document.getElementById('acknowledgeRisk').checked;
    
    if (confirmText !== 'CONFIRM' || !acknowledged) {
        showAlert('danger', 'Please complete all confirmation requirements.');
        return;
    }
    
    // Show progress section and hide confirmation
    showResetProgress();
    
    const actionMap = {
        'clear_data': 'clear_all_data',
        'reset_database': 'reset_database'
    };
    
    // Start the reset process with visual feedback
    performDatabaseReset(actionMap[currentResetAction]);
}

function showResetProgress() {
    // Hide confirmation section
    document.querySelector('#databaseResetModal .modal-body').style.display = 'none';
    document.querySelector('#databaseResetModal .modal-footer').style.display = 'none';
    
    // Show progress section
    document.getElementById('resetProgressSection').style.display = 'block';
    
    // Disable close button during operation
    document.getElementById('cancelResetBtn').disabled = true;
    document.querySelector('#databaseResetModal .btn-close').disabled = true;
    
    // Update modal title
    const modalTitle = document.getElementById('resetModalTitle');
    modalTitle.innerHTML = '<i class="fas fa-cog fa-spin me-2"></i>Processing Database Operation...';
    
    // Initialize progress
    updateProgress(0, 'Starting database operation...');
    addLogEntry('=== Database Reset Operation Started ===');
}

function updateProgress(percentage, status, logMessage = null) {
    const progressBar = document.getElementById('resetProgressBar');
    const progressText = document.getElementById('progressText');
    const currentStatus = document.getElementById('currentStatus');
    
    progressBar.style.width = percentage + '%';
    progressText.textContent = Math.round(percentage) + '%';
    currentStatus.textContent = status;
    
    if (logMessage) {
        addLogEntry(logMessage);
    }
    
    // Change progress bar color based on percentage
    progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated';
    if (percentage >= 100) {
        progressBar.classList.add('bg-success');
    } else if (percentage >= 50) {
        progressBar.classList.add('bg-warning');
    } else {
        progressBar.classList.add('bg-info');
    }
}

function addLogEntry(message) {
    const logContent = document.getElementById('logContent');
    const timestamp = new Date().toLocaleTimeString();
    logContent.textContent += `[${timestamp}] ${message}\n`;
    
    // Auto-scroll to bottom
    const logContainer = logContent.parentElement;
    logContainer.scrollTop = logContainer.scrollHeight;
}

function performDatabaseReset(action) {
    // Simulate progress steps
    updateProgress(10, 'Validating operation...', 'Checking user permissions and confirmation');
    
    setTimeout(() => {
        updateProgress(20, 'Creating backup...', 'Starting database backup process');
        
        setTimeout(() => {
            updateProgress(40, 'Processing database operation...', 'Executing ' + action + ' operation');
            
            // Actual API call
            fetch('<?= site_url('settings/adminUtility') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'action=' + action + '&confirmation=true&<?= csrf_token() ?>=' + '<?= csrf_hash() ?>'
            })
            .then(response => {
                updateProgress(70, 'Processing server response...', 'Received response from server');
                return response.json();
            })
            .then(data => {
                updateProgress(90, 'Finalizing operation...', 'Processing operation results');
                
                setTimeout(() => {
                    if (data.success) {
                        updateProgress(100, 'Operation completed successfully!', 'SUCCESS: ' + data.message);
                        
                        // Show success state
                        document.getElementById('statusMessages').innerHTML = `
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Success!</strong> ${data.message}
                            </div>
                        `;
                        
                        // Show completion actions
                        showCompletionActions(action === 'reset_database');
                        
                    } else {
                        updateProgress(100, 'Operation failed!', 'ERROR: ' + data.message);
                        
                        // Show error state
                        document.getElementById('statusMessages').innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Error!</strong> ${data.message}
                            </div>
                        `;
                        
                        showCompletionActions(false);
                    }
                }, 500);
            })
            .catch(error => {
                updateProgress(100, 'Operation failed!', 'NETWORK ERROR: ' + error.message);
                
                document.getElementById('statusMessages').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Network Error!</strong> ${error.message}
                    </div>
                `;
                
                showCompletionActions(false);
            });
        }, 1000);
    }, 500);
}

function showCompletionActions(isFullReset) {
    const progressSection = document.getElementById('resetProgressSection');
    
    const actionsHtml = `
        <div class="mt-4 text-center">
            <h6>What's Next?</h6>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-info" onclick="debugDatabase()">
                    <i class="fas fa-bug me-1"></i>Check Database Status
                </button>
                ${isFullReset ? 
                    '<button type="button" class="btn btn-outline-primary" onclick="redirectToLogin()"><i class="fas fa-sign-in-alt me-1"></i>Go to Login</button>' :
                    '<button type="button" class="btn btn-outline-success" onclick="reloadPage()"><i class="fas fa-refresh me-1"></i>Reload Page</button>'
                }
                <button type="button" class="btn btn-secondary" onclick="closeResetModal()">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    `;
    
    progressSection.insertAdjacentHTML('beforeend', actionsHtml);
}

function redirectToLogin() {
    addLogEntry('Redirecting to login page...');
    setTimeout(() => {
        window.location.href = '<?= site_url('auth/login') ?>';
    }, 1000);
}

function reloadPage() {
    addLogEntry('Reloading page...');
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

function closeResetModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('databaseResetModal'));
    modal.hide();
    
    // Reset modal state
    setTimeout(() => {
        resetModalState();
    }, 500);
}

function resetModalState() {
    // Show confirmation section
    document.querySelector('#databaseResetModal .modal-body').style.display = 'block';
    document.querySelector('#databaseResetModal .modal-footer').style.display = 'block';
    
    // Hide progress section
    document.getElementById('resetProgressSection').style.display = 'none';
    
    // Reset form
    document.getElementById('confirmationText').value = '';
    document.getElementById('acknowledgeRisk').checked = false;
    document.getElementById('confirmResetBtn').disabled = true;
    
    // Reset progress
    updateProgress(0, 'Initializing...');
    document.getElementById('logContent').textContent = '';
    
    // Re-enable buttons
    document.getElementById('cancelResetBtn').disabled = false;
    document.querySelector('#databaseResetModal .btn-close').disabled = false;
}

// Enable/disable confirm button based on form completion
document.addEventListener('DOMContentLoaded', function() {
    const confirmText = document.getElementById('confirmationText');
    const acknowledgeRisk = document.getElementById('acknowledgeRisk');
    const confirmBtn = document.getElementById('confirmResetBtn');
    
    function updateConfirmButton() {
        const textValid = confirmText.value === 'CONFIRM';
        const riskAcknowledged = acknowledgeRisk.checked;
        confirmBtn.disabled = !(textValid && riskAcknowledged);
    }
    
    confirmText.addEventListener('input', updateConfirmButton);
    acknowledgeRisk.addEventListener('change', updateConfirmButton);
});

// Debug database function
function debugDatabase() {
    // Show loading state
    const loadingToast = showLoadingToast('Analyzing database tables...');
    
    fetch('<?= site_url('settings/adminUtility') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'action=debug_database&<?= csrf_token() ?>=' + '<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingToast(loadingToast);
        
        if (data.success) {
            // Calculate statistics
            let totalRows = 0;
            let tablesWithData = 0;
            let emptyTables = 0;
            
            Object.keys(data.tables).forEach(tableName => {
                const table = data.tables[tableName];
                if (typeof table.row_count === 'number') {
                    totalRows += table.row_count;
                    if (table.has_data) {
                        tablesWithData++;
                    } else {
                        emptyTables++;
                    }
                }
            });
            
            // Create enhanced table info with visual indicators
            let tableInfo = `
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5>${data.total_tables}</h5>
                                <small>Total Tables</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5>${tablesWithData}</h5>
                                <small>Tables with Data</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5>${emptyTables}</h5>
                                <small>Empty Tables</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h6>Detailed Table Information:</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Table Name</th>
                                <th>Row Count</th>
                                <th>Status</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            // Sort tables by row count (descending)
            const sortedTables = Object.keys(data.tables).sort((a, b) => {
                const aCount = typeof data.tables[a].row_count === 'number' ? data.tables[a].row_count : 0;
                const bCount = typeof data.tables[b].row_count === 'number' ? data.tables[b].row_count : 0;
                return bCount - aCount;
            });
            
            sortedTables.forEach(tableName => {
                const table = data.tables[tableName];
                const hasDataBadge = table.has_data ? 
                    '<span class="badge bg-warning">Has Data</span>' : 
                    '<span class="badge bg-success">Empty</span>';
                
                // Determine table type
                const systemTables = ['migrations', 'settings', 'users', 'roles', 'permissions'];
                const tableType = systemTables.includes(tableName) ? 
                    '<span class="badge bg-info">System</span>' : 
                    '<span class="badge bg-secondary">Business</span>';
                
                const rowClass = table.has_data ? 'table-warning' : '';
                
                tableInfo += `
                    <tr class="${rowClass}">
                        <td><strong>${tableName}</strong></td>
                        <td>${table.row_count}</td>
                        <td>${hasDataBadge}</td>
                        <td>${tableType}</td>
                    </tr>
                `;
            });
            
            tableInfo += `
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Total Records:</strong> ${totalRows.toLocaleString()} rows across all tables
                </div>
            `;
            
            // Show in enhanced modal
            const modalHtml = `
                <div class="modal fade" id="debugModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-database me-2"></i>Database Analysis Report
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ${tableInfo}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" onclick="refreshDebugInfo()">
                                    <i class="fas fa-sync-alt me-1"></i>Refresh
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing debug modal if any
            const existingModal = document.getElementById('debugModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Add new modal
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const debugModal = new bootstrap.Modal(document.getElementById('debugModal'));
            debugModal.show();
            
        } else {
            showAlert('danger', 'Failed to get database info: ' + data.message);
        }
    })
    .catch(error => {
        hideLoadingToast(loadingToast);
        showAlert('danger', 'Error getting database info: ' + error.message);
    });
}

function refreshDebugInfo() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('debugModal'));
    modal.hide();
    setTimeout(() => {
        debugDatabase();
    }, 300);
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

// Fix modal overlay issues for settings modals
// Wait for jQuery to load
document.addEventListener('DOMContentLoaded', function() {
    // Check if jQuery is loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    $(document).ready(function() {
    // Check if Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JavaScript is not loaded!');
        return;
    }
    
    // Initialize modals properly - remove backdrop to fix overlay issues
    const importModal = new bootstrap.Modal(document.getElementById('importModal'), {
        backdrop: false,
        keyboard: true,
        focus: true
    });

    const cleanLogsModal = new bootstrap.Modal(document.getElementById('cleanLogsModal'), {
        backdrop: false,
        keyboard: true,
        focus: true
    });

    // Handle close button clicks manually since we removed backdrop
    $('#importModal .btn-close, #importModal [data-bs-dismiss="modal"]').on('click', function(e) {
        e.preventDefault();
        importModal.hide();
    });

    $('#cleanLogsModal .btn-close, #cleanLogsModal [data-bs-dismiss="modal"]').on('click', function(e) {
        e.preventDefault();
        cleanLogsModal.hide();
    });

    // Handle import button click
    $('[data-bs-target="#importModal"]').on('click', function(e) {
        e.preventDefault();
        console.log('Import button clicked');
        // Reset form when opening modal
        $('#importModal form')[0].reset();
        importModal.show();
    });

    // Handle import form submission with AJAX
    $('#importModal form').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        const fileInput = $('#settings_file')[0];
        if (!fileInput.files.length) {
            alert('Please select a JSON file to import.');
            return false;
        }
        
        const file = fileInput.files[0];
        if (!file.name.toLowerCase().endsWith('.json')) {
            alert('Please select a valid JSON file.');
            return false;
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Importing...').prop('disabled', true);
        
        // Create FormData for file upload
        const formData = new FormData(this);
        
        // Submit via AJAX
        $.ajax({
            url: '<?= site_url('settings/importSettings') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Import response:', response);
                
                // Reset button
                submitBtn.html(originalText).prop('disabled', false);
                
                if (response.success) {
                    showAlert('success', response.message);
                    importModal.hide();
                    // Reload page to show updated settings
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert('danger', response.message || 'Import failed');
                }
            },
            error: function(xhr, status, error) {
                console.error('Import error:', error);
                console.error('Response:', xhr.responseText);
                
                // Reset button
                submitBtn.html(originalText).prop('disabled', false);
                
                let errorMessage = 'Import failed';
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMessage = response.message || errorMessage;
                } catch (e) {
                    errorMessage = 'Import failed: ' + error;
                }
                
                showAlert('danger', errorMessage);
            }
        });
    });

    // Handle clean logs button click  
    $('[data-bs-target="#cleanLogsModal"]').on('click', function(e) {
        e.preventDefault();
        console.log('Clean logs button clicked');
        cleanLogsModal.show();
    });

    // Handle escape key to close modals
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            if ($('#importModal').hasClass('show')) {
                importModal.hide();
            }
            if ($('#cleanLogsModal').hasClass('show')) {
                cleanLogsModal.hide();
            }
        }
    });

    // Modal event debugging
    $('#importModal').on('show.bs.modal', function() {
        console.log('Import modal is about to show');
    });

    $('#importModal').on('shown.bs.modal', function() {
        console.log('Import modal is fully shown');
        $('#settings_file').focus();
    });

    $('#cleanLogsModal').on('show.bs.modal', function() {
        console.log('Clean logs modal is about to show');
    });

    $('#cleanLogsModal').on('shown.bs.modal', function() {
        console.log('Clean logs modal is fully shown');
    });
    
    // Unit conversion preview updater
    function updateUnitPreview() {
        const selectedUnit = $('#default_weight_unit').val();
        const conversions = {
            'kg': [
                '1 kg = 0.001 MT',
                '1000 kg = 1 MT',
                '1 kg = 2.20462 lbs',
                '1 kg = 1000 g'
            ],
            'mt': [
                '1 MT = 1000 kg',
                '1 MT = 2204.62 lbs',
                '1 MT = 1,000,000 g',
                '0.001 MT = 1 kg'
            ],
            'ton': [
                '1 ton = 1000 kg',
                '1 ton = 2204.62 lbs',
                '1 ton = 1,000,000 g',
                '0.001 ton = 1 kg'
            ],
            'lbs': [
                '1 lb = 0.453592 kg',
                '1 lb = 0.000453592 MT',
                '1 lb = 453.592 g',
                '2.20462 lbs = 1 kg'
            ],
            'g': [
                '1 g = 0.001 kg',
                '1 g = 0.000001 MT',
                '1000 g = 1 kg',
                '1,000,000 g = 1 MT'
            ]
        };
        
        const unitNames = {
            'kg': 'Kilograms',
            'mt': 'Metric Tonnes',
            'ton': 'Tonnes',
            'lbs': 'Pounds',
            'g': 'Grams'
        };
        
        let html = '<p class="mb-1"><strong>Example conversions with ' + unitNames[selectedUnit] + ':</strong></p>';
        html += '<ul class="mb-0">';
        conversions[selectedUnit].forEach(function(conversion) {
            html += '<li>' + conversion + '</li>';
        });
        html += '</ul>';
        
        $('#unit-preview').html(html);
    }
    
    // Update preview when unit changes
    $('#default_weight_unit').on('change', updateUnitPreview);
    
    // Initialize preview on page load
    updateUnitPreview();
    
    }); // End $(document).ready
}); // End DOMContentLoaded
</script>

<?= $this->endSection() ?>
