<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active">Dashboard</li>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="d-flex gap-2">
    <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bx bx-plus me-1"></i> Quick Add
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="<?= site_url('batches/new') ?>"><i class="bx bx-package me-2"></i>New Batch</a></li>
        <li><a class="dropdown-item" href="<?= site_url('dispatches/new') ?>"><i class="bx bx-car me-2"></i>New Dispatch</a></li>
        <li><a class="dropdown-item" href="<?= site_url('purchase-orders/new') ?>"><i class="bx bx-receipt me-2"></i>Purchase Order</a></li>
    </ul>
    <button type="button" class="btn btn-outline-secondary">
        <i class="bx bx-refresh me-1"></i> Refresh
    </button>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total Batches</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2">0</h3>
                            <p class="text-success mb-0">(+0%)</p>
                        </div>
                        <p class="mb-0">Active inventory batches</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-package bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Active Dispatches</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2">0</h3>
                            <p class="text-success mb-0">(+0%)</p>
                        </div>
                        <p class="mb-0">In-transit shipments</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-car bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Purchase Orders</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2">0</h3>
                            <p class="text-warning mb-0">(+0%)</p>
                        </div>
                        <p class="mb-0">Pending orders</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-receipt bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total Inventory</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2">0</h3>
                            <p class="text-info mb-0">kg</p>
                        </div>
                        <p class="mb-0">Current stock levels</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-store bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-4">
    <!-- Left Column -->
    <div class="col-xl-8 col-lg-7 col-md-12">
        <!-- Inventory Overview -->
        <div class="card mb-4">
            <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h5 class="mb-0">Inventory Overview</h5>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-calendar me-1"></i> <span class="d-none d-sm-inline">This Month</span><span class="d-sm-none">Month</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">This Week</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Quarter</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#stock-levels" type="button" role="tab">
                            <i class="bx bx-bar-chart me-1"></i> Stock Levels
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#grain-types" type="button" role="tab">
                            <i class="bx bx-pie-chart me-1"></i> Grain Types
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="stock-levels" role="tabpanel">
                        <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                            <div class="text-center">
                                <div class="avatar avatar-xl mb-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="bx bx-bar-chart bx-lg"></i>
                                    </span>
                                </div>
                                <h6 class="mb-1">Stock Level Analytics</h6>
                                <p class="text-muted mb-0">Charts and analytics will be available in Phase 2</p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="grain-types" role="tabpanel">
                        <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                            <div class="text-center">
                                <div class="avatar avatar-xl mb-3">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="bx bx-pie-chart bx-lg"></i>
                                    </span>
                                </div>
                                <h6 class="mb-1">Grain Distribution</h6>
                                <p class="text-muted mb-0">Grain type analytics will be available in Phase 2</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Batches -->
        <div class="card mt-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-0">Recent Batch Entries</h5>
                    <small class="text-muted">Latest inventory additions</small>
                </div>
                <a href="<?= site_url('batches/new') ?>" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i> New Batch
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Batch</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Supplier</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Weight</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="avatar avatar-xl mb-3">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-package bx-lg"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-1">No Recent Batches</h6>
                                    <p class="text-muted mb-0">Start by creating your first batch entry</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Activity & Quick Actions Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
                <small class="text-muted">Common tasks and shortcuts</small>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= site_url('batches/new') ?>" class="btn btn-outline-primary">
                        <i class="bx bx-package me-2"></i> Add New Batch
                    </a>
                    <a href="<?= site_url('dispatches/new') ?>" class="btn btn-outline-success">
                        <i class="bx bx-car me-2"></i> Create Dispatch
                    </a>
                    <a href="<?= site_url('purchase-orders/new') ?>" class="btn btn-outline-warning">
                        <i class="bx bx-receipt me-2"></i> New Purchase Order
                    </a>
                    <a href="<?= site_url('inventory/adjust') ?>" class="btn btn-outline-info">
                        <i class="bx bx-edit me-2"></i> Adjust Inventory
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Activity</h5>
                <small class="text-muted">Latest system activities and updates</small>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-point timeline-point-primary"></div>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">System Initialized</h6>
                                <small class="text-muted">Just now</small>
                            </div>
                            <p class="mb-0">GrainFlow management system is ready for use</p>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">Activity tracking will expand in Phase 2</small>
                </div>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">System Status</h5>
                <small class="text-muted">Current system health</small>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-check"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Database</h6>
                        <small class="text-muted">Connected</small>
                    </div>
                    <span class="badge bg-success">Online</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-server"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Application</h6>
                        <small class="text-muted">Running smoothly</small>
                    </div>
                    <span class="badge bg-success">Healthy</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-time"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Last Backup</h6>
                        <small class="text-muted">Automated backups pending</small>
                    </div>
                    <span class="badge bg-warning">Pending</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard
    console.log('Dashboard loaded successfully');
    
    // Add smooth entrance animations
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Initialize refresh functionality
    const refreshBtn = document.querySelector('.btn-outline-secondary');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Refreshing...';
            this.disabled = true;
            
            // Simulate refresh (replace with actual data loading)
            setTimeout(() => {
                this.innerHTML = originalContent;
                this.disabled = false;
                
                // Show success notification
                if (window.GrainFlow && window.GrainFlow.showNotification) {
                    window.GrainFlow.showNotification('Dashboard refreshed successfully', 'success');
                }
            }, 1500);
        });
    }
});
</script>
<?= $this->endSection() ?>
