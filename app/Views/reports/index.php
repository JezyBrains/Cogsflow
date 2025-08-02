<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-bar-chart-alt-2 me-2"></i>
                        Reports & Analytics
                    </h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bx bx-download me-1"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url('reports/export/pdf') ?>">
                                <i class="bx bx-file-blank me-2"></i>PDF Report
                            </a></li>
                            <li><a class="dropdown-item" href="<?= site_url('reports/export/excel') ?>">
                                <i class="bx bx-spreadsheet me-2"></i>Excel Report
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Categories -->
    <div class="row g-4">
        <!-- Batch Reports -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="bx bx-package fs-4"></i>
                        </span>
                    </div>
                    <h5 class="card-title">Batch Reports</h5>
                    <p class="card-text text-muted">
                        View detailed reports on batch processing, quality metrics, and production statistics.
                    </p>
                    <a href="<?= site_url('reports/batches') ?>" class="btn btn-outline-primary">
                        <i class="bx bx-show me-1"></i>View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Inventory Reports -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-success">
                            <i class="bx bx-store fs-4"></i>
                        </span>
                    </div>
                    <h5 class="card-title">Inventory Reports</h5>
                    <p class="card-text text-muted">
                        Track stock levels, movement patterns, and inventory valuation across all locations.
                    </p>
                    <a href="<?= site_url('reports/inventory') ?>" class="btn btn-outline-success">
                        <i class="bx bx-show me-1"></i>View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Financial Reports -->
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-info">
                            <i class="bx bx-wallet fs-4"></i>
                        </span>
                    </div>
                    <h5 class="card-title">Financial Reports</h5>
                    <p class="card-text text-muted">
                        Analyze revenue, expenses, profit margins, and financial performance metrics.
                    </p>
                    <a href="<?= site_url('reports/financial') ?>" class="btn btn-outline-info">
                        <i class="bx bx-show me-1"></i>View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        <i class="bx bx-package"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="small text-muted">Total Batches</div>
                                    <div class="fw-medium">1,247</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        <i class="bx bx-car"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="small text-muted">Dispatches</div>
                                    <div class="fw-medium">892</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-info">
                                        <i class="bx bx-store"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="small text-muted">Inventory Items</div>
                                    <div class="fw-medium">156</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-warning">
                                        <i class="bx bx-dollar"></i>
                                    </span>
                                </div>
                                <div>
                                    <div class="small text-muted">Revenue</div>
                                    <div class="fw-medium">$45,280</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
