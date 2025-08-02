<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="bx bx-store me-2"></i>
                            Inventory Reports
                        </h5>
                        <p class="text-muted mb-0">Stock levels, movement patterns, and valuation analytics</p>
                    </div>
                    <a href="<?= site_url('reports') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Back to Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Total Items</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">156</h3>
                                <p class="text-success mb-0">(+8)</p>
                            </div>
                            <p class="mb-0">Active items</p>
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
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Total Value</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">$284K</h3>
                                <p class="text-success mb-0">(+15%)</p>
                            </div>
                            <p class="mb-0">Inventory value</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-dollar bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Low Stock</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">12</h3>
                                <p class="text-warning mb-0">(+3)</p>
                            </div>
                            <p class="mb-0">Items below threshold</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-error bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Turnover Rate</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">4.2x</h3>
                                <p class="text-success mb-0">(+0.3x)</p>
                            </div>
                            <p class="mb-0">Annual turnover</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-refresh bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Movement Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Inventory Movement Trends</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bx bx-line-chart display-1 text-muted"></i>
                        <p class="text-muted mt-3">Interactive chart will be implemented in Phase 3</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Stock Levels -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Current Stock Levels</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="bx bx-download me-1"></i>Export
                        </button>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Min Level</th>
                                <th>Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td><strong>WH001</strong></td>
                                <td>Premium Wheat Grade A</td>
                                <td>Grains</td>
                                <td>2,500 kg</td>
                                <td>500 kg</td>
                                <td>$12,500</td>
                                <td><span class="badge bg-label-success">Good</span></td>
                            </tr>
                            <tr>
                                <td><strong>RC002</strong></td>
                                <td>Basmati Rice Premium</td>
                                <td>Grains</td>
                                <td>180 kg</td>
                                <td>200 kg</td>
                                <td>$1,800</td>
                                <td><span class="badge bg-label-warning">Low</span></td>
                            </tr>
                            <tr>
                                <td><strong>CR003</strong></td>
                                <td>Yellow Corn Grade B</td>
                                <td>Grains</td>
                                <td>1,200 kg</td>
                                <td>300 kg</td>
                                <td>$4,800</td>
                                <td><span class="badge bg-label-success">Good</span></td>
                            </tr>
                            <tr>
                                <td><strong>SB004</strong></td>
                                <td>Soybean Premium</td>
                                <td>Legumes</td>
                                <td>85 kg</td>
                                <td>100 kg</td>
                                <td>$850</td>
                                <td><span class="badge bg-label-danger">Critical</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
