<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active">Dashboard</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-boxes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Batches</span>
                <span class="info-box-number">
                    0
                    <small>batches</small>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-truck"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Active Dispatches</span>
                <span class="info-box-number">
                    0
                    <small>active</small>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-invoice"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Purchase Orders</span>
                <span class="info-box-number">
                    0
                    <small>pending</small>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-warehouse"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Inventory</span>
                <span class="info-box-number">
                    0
                    <small>kg</small>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Inventory Overview
                </h3>
                <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Stock Levels</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#sales-chart" data-toggle="tab">Grain Types</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content p-0">
                    <!-- Morris chart - Stock Levels -->
                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Stock level charts will be available in Phase 2</p>
                            </div>
                        </div>
                    </div>
                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center">
                                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Grain type distribution will be available in Phase 2</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Batches -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-boxes mr-1"></i>
                    Recent Batch Entries
                </h3>
                <div class="card-tools">
                    <a href="<?= site_url('batches/new') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> New Batch
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Batch ID</th>
                                <th>Supplier</th>
                                <th>Weight</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    No recent batch entries found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Right col -->
    <section class="col-lg-5 connectedSortable">
        <!-- Calendar -->
        <div class="card bg-gradient-success">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="far fa-calendar-alt"></i>
                    Calendar
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="calendar" style="width: 100%">
                    <div class="text-center text-white py-4">
                        <i class="far fa-calendar fa-3x mb-3"></i>
                        <p>Calendar widget will be implemented in Phase 2</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Dispatches -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-truck mr-1"></i>
                    Recent Dispatches
                </h3>
                <div class="card-tools">
                    <a href="<?= site_url('dispatches/new') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> New Dispatch
                    </a>
                </div>
            </div>
            <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                    <li class="text-center text-muted py-4">
                        <i class="fas fa-truck fa-2x mb-2"></i><br>
                        No recent dispatches found.
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-1"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <a href="<?= site_url('batches/new') ?>" class="btn btn-primary btn-block btn-sm">
                            <i class="fas fa-plus"></i> New Batch
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('dispatches/new') ?>" class="btn btn-success btn-block btn-sm">
                            <i class="fas fa-truck"></i> New Dispatch
                        </a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-6">
                        <a href="<?= site_url('purchase-orders/new') ?>" class="btn btn-warning btn-block btn-sm">
                            <i class="fas fa-file-invoice"></i> New PO
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('inventory/adjust') ?>" class="btn btn-info btn-block btn-sm">
                            <i class="fas fa-edit"></i> Adjust Stock
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize dashboard widgets
    console.log('Dashboard loaded successfully');
    
    // Add fade-in animation to cards
    $('.card').addClass('fade-in');
    
    // Initialize tooltips for quick actions
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
<?= $this->endSection() ?>
