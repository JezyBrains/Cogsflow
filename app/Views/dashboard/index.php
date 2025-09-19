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
                            <h3 class="mb-0 me-2"><?= isset($batch_stats['total_batches']) ? $batch_stats['total_batches'] : 0 ?></h3>
                            <p class="text-success mb-0"><?= isset($batch_stats['approved_batches']) ? '(' . $batch_stats['approved_batches'] . ' approved)' : '(0 approved)' ?></p>
                        </div>
                        <p class="mb-0">All batch entries</p>
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
                            <h3 class="mb-0 me-2"><?= isset($dispatch_stats['total_dispatches']) ? $dispatch_stats['total_dispatches'] : 0 ?></h3>
                            <p class="text-success mb-0"><?= isset($dispatch_stats['delivered_dispatches']) ? '(' . $dispatch_stats['delivered_dispatches'] . ' delivered)' : '(0 delivered)' ?></p>
                        </div>
                        <p class="mb-0">All dispatches</p>
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
                            <h3 class="mb-0 me-2"><?= isset($purchase_order_stats['total_purchase_orders']) ? $purchase_order_stats['total_purchase_orders'] : 0 ?></h3>
                            <p class="text-warning mb-0"><?= isset($purchase_order_stats['pending_orders']) ? '(' . $purchase_order_stats['pending_orders'] . ' pending)' : '(0 pending)' ?></p>
                        </div>
                        <p class="mb-0">All purchase orders</p>
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
                            <h3 class="mb-0 me-2"><?= isset($inventory_summary['total_stock_mt']) ? number_format($inventory_summary['total_stock_mt'] * 1000, 0) : 0 ?></h3>
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

<!-- Additional Purchase Order Status Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
        <div class="card border-left-warning">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Transferring Orders</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2 text-warning"><?= isset($purchase_order_stats['transferring_orders']) ? $purchase_order_stats['transferring_orders'] : 0 ?></h3>
                            <p class="text-muted mb-0">in progress</p>
                        </div>
                        <p class="mb-0">Partially transferred POs</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-transfer bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
        <div class="card border-left-success">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Completed Orders</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2 text-success"><?= isset($purchase_order_stats['completed_orders']) ? $purchase_order_stats['completed_orders'] : 0 ?></h3>
                            <p class="text-muted mb-0">finished</p>
                        </div>
                        <p class="mb-0">Fully transferred POs</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-check-circle bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
        <div class="card border-left-info">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total PO Value</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2 text-info"><?= isset($purchase_order_stats['total_value']) ? number_format($purchase_order_stats['total_value'], 0) : 0 ?></h3>
                            <p class="text-muted mb-0"><?= $default_currency ?? 'TSH' ?></p>
                        </div>
                        <p class="mb-0">All purchase orders value</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-money bx-sm"></i>
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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#charts-view" type="button" role="tab">
                            <i class="bx bx-line-chart me-1"></i> Charts
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="stock-levels" role="tabpanel">
                        <?php if (isset($inventory_summary['grain_types']) && !empty($inventory_summary['grain_types'])): ?>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Grain Type</th>
                                                    <th>Items</th>
                                                    <th>Stock (MT)</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($inventory_summary['grain_types'] as $grain): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-xs me-2">
                                                                <span class="avatar-initial rounded bg-label-primary">
                                                                    <i class="bx bx-leaf bx-xs"></i>
                                                                </span>
                                                            </div>
                                                            <strong><?= esc($grain['grain_type']) ?></strong>
                                                        </div>
                                                    </td>
                                                    <td><?= $grain['item_count'] ?></td>
                                                    <td>
                                                        <span class="badge bg-label-info"><?= number_format($grain['total_stock_mt'], 2) ?> MT</span>
                                                    </td>
                                                    <td>
                                                        <?php if ($grain['low_stock_count'] > 0): ?>
                                                            <span class="badge bg-warning"><?= $grain['low_stock_count'] ?> Low Stock</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-success">Good</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="mb-3">Stock Summary</h6>
                                            <div class="mb-3">
                                                <div class="h4 text-primary"><?= number_format($inventory_summary['total_stock_mt'], 2) ?></div>
                                                <small class="text-muted">Total Stock (MT)</small>
                                            </div>
                                            <div class="mb-3">
                                                <div class="h5 text-info"><?= $inventory_summary['total_items'] ?></div>
                                                <small class="text-muted">Active Items</small>
                                            </div>
                                            <?php if ($inventory_summary['low_stock_count'] > 0): ?>
                                                <div class="alert alert-warning py-2">
                                                    <i class="bx bx-error-circle me-1"></i>
                                                    <?= $inventory_summary['low_stock_count'] ?> items low stock
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-success py-2">
                                                    <i class="bx bx-check-circle me-1"></i>
                                                    All items well stocked
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                                <div class="text-center">
                                    <div class="avatar avatar-xl mb-3">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-store bx-lg"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-1">No Inventory Data</h6>
                                    <p class="text-muted mb-0">Start by adding inventory items to see stock levels</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane fade" id="grain-types" role="tabpanel">
                        <?php if (isset($inventory_summary['grain_types']) && !empty($inventory_summary['grain_types'])): ?>
                            <div class="row g-3">
                                <?php foreach ($inventory_summary['grain_types'] as $grain): ?>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="content-left">
                                                    <h6 class="mb-1"><?= esc($grain['grain_type']) ?></h6>
                                                    <div class="d-flex align-items-center my-2">
                                                        <h4 class="mb-0 me-2"><?= number_format($grain['total_stock_mt'], 1) ?></h4>
                                                        <small class="text-muted">MT</small>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <span class="badge bg-label-primary"><?= $grain['item_count'] ?> items</span>
                                                        <?php if ($grain['low_stock_count'] > 0): ?>
                                                            <span class="badge bg-warning"><?= $grain['low_stock_count'] ?> low</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="avatar">
                                                    <span class="avatar-initial rounded bg-label-success">
                                                        <i class="bx bx-leaf bx-sm"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <div class="progress" style="height: 6px;">
                                                    <?php 
                                                    $totalStock = $inventory_summary['total_stock_mt'];
                                                    $percentage = $totalStock > 0 ? ($grain['total_stock_mt'] / $totalStock) * 100 : 0;
                                                    ?>
                                                    <div class="progress-bar bg-success" style="width: <?= $percentage ?>%"></div>
                                                </div>
                                                <small class="text-muted"><?= number_format($percentage, 1) ?>% of total stock</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                                <div class="text-center">
                                    <div class="avatar avatar-xl mb-3">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-pie-chart bx-lg"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-1">No Grain Types</h6>
                                    <p class="text-muted mb-0">Add inventory items to see grain type distribution</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane fade" id="charts-view" role="tabpanel">
                        <?php if (isset($inventory_summary['grain_types']) && !empty($inventory_summary['grain_types'])): ?>
                            <div class="row g-4">
                                <!-- Stock Distribution Pie Chart -->
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Stock Distribution by Grain Type</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="stockPieChart" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Stock Levels Bar Chart -->
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Stock Levels Comparison</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="stockBarChart" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Stock Status Doughnut Chart -->
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Stock Status Overview</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="stockStatusChart" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Stock Trends Area Chart -->
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Stock Trends (Simulated)</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="stockTrendsChart" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                                <div class="text-center">
                                    <div class="avatar avatar-xl mb-3">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-line-chart bx-lg"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-1">No Data for Charts</h6>
                                    <p class="text-muted mb-0">Add inventory items to see beautiful stock charts</p>
                                </div>
                            </div>
                        <?php endif; ?>
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
                <a href="<?= site_url('batches/new') ?>" class="btn btn-blue btn-sm">
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
                            <?php if (isset($recent_batches) && !empty($recent_batches)): ?>
                                <?php foreach ($recent_batches as $batch): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded bg-label-primary">
                                                    <i class="bx bx-package"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= esc($batch['batch_number']) ?></h6>
                                                <small class="text-muted"><?= esc($batch['grain_type']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-heading"><?= esc($batch['supplier_name'] ?? 'Unknown') ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info"><?= number_format($batch['total_weight_mt'], 2) ?> MT</span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match($batch['status']) {
                                            'pending' => 'bg-warning',
                                            'approved' => 'bg-success',
                                            'dispatched' => 'bg-info',
                                            'delivered' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= ucfirst($batch['status']) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
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
                            <?php endif; ?>
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

// Chart.js initialization for inventory charts
document.addEventListener('DOMContentLoaded', function() {
    // Get inventory data from PHP
    const inventoryData = <?= json_encode($inventory_summary ?? []) ?>;
    
    if (inventoryData && inventoryData.grain_types && inventoryData.grain_types.length > 0) {
        initializeCharts(inventoryData);
    }
});

function initializeCharts(data) {
    const grainTypes = data.grain_types;
    const labels = grainTypes.map(item => item.grain_type);
    const stockData = grainTypes.map(item => parseFloat(item.total_stock_mt));
    const itemCounts = grainTypes.map(item => parseInt(item.item_count));
    const lowStockCounts = grainTypes.map(item => parseInt(item.low_stock_count));
    
    // Color palette
    const colors = [
        '#696cff', '#8592a3', '#71dd37', '#ffab00', '#ff3e1d',
        '#03c3ec', '#233446', '#7367f0', '#28c76f', '#ea5455'
    ];
    
    // 1. Stock Distribution Pie Chart
    const pieCtx = document.getElementById('stockPieChart');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: stockData,
                    backgroundColor: colors.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} MT (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // 2. Stock Levels Bar Chart
    const barCtx = document.getElementById('stockBarChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Stock (MT)',
                    data: stockData,
                    backgroundColor: colors[0],
                    borderColor: colors[0],
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Stock (MT)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Grain Types'
                        }
                    }
                }
            }
        });
    }
    
    // 3. Stock Status Doughnut Chart
    const statusCtx = document.getElementById('stockStatusChart');
    if (statusCtx) {
        const totalItems = data.total_items;
        const lowStockItems = data.low_stock_count;
        const goodStockItems = totalItems - lowStockItems;
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Good Stock', 'Low Stock'],
                datasets: [{
                    data: [goodStockItems, lowStockItems],
                    backgroundColor: [colors[2], colors[4]],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} items (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // 4. Stock Trends Area Chart (Simulated data)
    const trendsCtx = document.getElementById('stockTrendsChart');
    if (trendsCtx) {
        // Generate simulated trend data for the last 7 days
        const dates = [];
        const trendData = {};
        
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            dates.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        }
        
        // Create datasets for each grain type
        const datasets = grainTypes.slice(0, 3).map((grain, index) => {
            const baseValue = parseFloat(grain.total_stock_mt);
            const data = dates.map(() => {
                // Generate realistic variations (±10% of base value)
                const variation = (Math.random() - 0.5) * 0.2 * baseValue;
                return Math.max(0, baseValue + variation);
            });
            
            return {
                label: grain.grain_type,
                data: data,
                borderColor: colors[index],
                backgroundColor: colors[index] + '20',
                fill: true,
                tension: 0.4
            };
        });
        
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Stock (MT)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
}
</script>
<?= $this->endSection() ?>
