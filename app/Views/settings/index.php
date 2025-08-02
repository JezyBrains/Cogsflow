<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>System Settings<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <h5>System Settings</h5>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="row">
    <!-- General Settings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6>General Settings</h6>
            </div>
            <div class="card-body">
                <form action="<?= site_url('settings/update') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="setting_type" value="general">
                    
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" value="Grain Management Co.">
                    </div>
                    
                    <div class="mb-3">
                        <label for="company_address" class="form-label">Company Address</label>
                        <textarea class="form-control" id="company_address" name="company_address" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email">
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update General Settings</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Inventory Settings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6>Inventory Settings</h6>
            </div>
            <div class="card-body">
                <form action="<?= site_url('settings/update') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="setting_type" value="inventory">
                    
                    <div class="mb-3">
                        <label for="default_unit" class="form-label">Default Weight Unit</label>
                        <select class="form-select" id="default_unit" name="default_unit">
                            <option value="kg" selected>Kilograms (kg)</option>
                            <option value="tons">Tons</option>
                            <option value="lbs">Pounds (lbs)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="low_stock_threshold" class="form-label">Low Stock Threshold (%)</label>
                        <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" value="20" min="0" max="100">
                    </div>
                    
                    <div class="mb-3">
                        <label for="auto_reorder" class="form-label">Auto Reorder</label>
                        <select class="form-select" id="auto_reorder" name="auto_reorder">
                            <option value="0">Disabled</option>
                            <option value="1">Enabled</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Inventory Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- System Preferences -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6>System Preferences</h6>
            </div>
            <div class="card-body">
                <form action="<?= site_url('settings/update') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="setting_type" value="system">
                    
                    <div class="mb-3">
                        <label for="timezone" class="form-label">Timezone</label>
                        <select class="form-select" id="timezone" name="timezone">
                            <option value="UTC">UTC</option>
                            <option value="Africa/Nairobi" selected>Africa/Nairobi</option>
                            <option value="America/New_York">America/New_York</option>
                            <option value="Europe/London">Europe/London</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_format" class="form-label">Date Format</label>
                        <select class="form-select" id="date_format" name="date_format">
                            <option value="Y-m-d" selected>YYYY-MM-DD</option>
                            <option value="d/m/Y">DD/MM/YYYY</option>
                            <option value="m/d/Y">MM/DD/YYYY</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="currency" class="form-label">Currency</label>
                        <select class="form-select" id="currency" name="currency">
                            <option value="USD">USD ($)</option>
                            <option value="KES" selected>KES (KSh)</option>
                            <option value="EUR">EUR (€)</option>
                            <option value="GBP">GBP (£)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update System Settings</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Backup & Maintenance -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6>Backup & Maintenance</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Database Backup</label>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="alert('Backup functionality will be implemented in Phase 2')">
                            <i class="fas fa-download"></i> Create Backup
                        </button>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">System Maintenance</label>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-warning" onclick="alert('Maintenance functionality will be implemented in Phase 2')">
                            <i class="fas fa-tools"></i> Clear Cache
                        </button>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">System Information</label>
                    <div class="small text-muted">
                        <p>Version: 1.0.0</p>
                        <p>PHP Version: <?= phpversion() ?></p>
                        <p>CodeIgniter Version: <?= \CodeIgniter\CodeIgniter::CI_VERSION ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Settings specific scripts can be added here
</script>
<?= $this->endSection() ?>
