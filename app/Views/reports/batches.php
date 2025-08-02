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
                            <i class="bx bx-package me-2"></i>
                            Batch Reports
                        </h5>
                        <p class="text-muted mb-0">Comprehensive batch processing and quality analytics</p>
                    </div>
                    <a href="<?= site_url('reports') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Back to Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Total Batches</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">1,247</h3>
                                <p class="text-success mb-0">(+12.5%)</p>
                            </div>
                            <p class="mb-0">This month</p>
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
                            <span class="text-heading">Quality Score</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">94.2%</h3>
                                <p class="text-success mb-0">(+2.1%)</p>
                            </div>
                            <p class="mb-0">Average quality</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-check-shield bx-sm"></i>
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
                            <span class="text-heading">Processing Time</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">2.4h</h3>
                                <p class="text-danger mb-0">(-0.3h)</p>
                            </div>
                            <p class="mb-0">Average time</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-time bx-sm"></i>
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
                            <span class="text-heading">Rejected Batches</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">23</h3>
                                <p class="text-success mb-0">(-5)</p>
                            </div>
                            <p class="mb-0">This month</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-x-circle bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Batches Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Batch Reports</h5>
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
                                <th>Batch ID</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Weight (kg)</th>
                                <th>Quality</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td><strong>B001</strong></td>
                                <td>2024-01-15</td>
                                <td>ABC Grains Ltd</td>
                                <td>2,500</td>
                                <td><span class="badge bg-label-success">A+</span></td>
                                <td><span class="badge bg-label-success">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>B002</strong></td>
                                <td>2024-01-14</td>
                                <td>XYZ Suppliers</td>
                                <td>1,800</td>
                                <td><span class="badge bg-label-info">A</span></td>
                                <td><span class="badge bg-label-success">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>B003</strong></td>
                                <td>2024-01-13</td>
                                <td>Farm Fresh Co</td>
                                <td>3,200</td>
                                <td><span class="badge bg-label-warning">B+</span></td>
                                <td><span class="badge bg-label-warning">Processing</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
