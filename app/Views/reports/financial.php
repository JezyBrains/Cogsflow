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
                            <i class="bx bx-wallet me-2"></i>
                            Financial Reports
                        </h5>
                        <p class="text-muted mb-0">Revenue, expenses, profit margins, and financial performance</p>
                    </div>
                    <a href="<?= site_url('reports') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Back to Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial KPIs -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Total Revenue</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">$45,280</h3>
                                <p class="text-success mb-0">(+18.2%)</p>
                            </div>
                            <p class="mb-0">This month</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-trending-up bx-sm"></i>
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
                            <span class="text-heading">Total Expenses</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">$28,450</h3>
                                <p class="text-danger mb-0">(+5.1%)</p>
                            </div>
                            <p class="mb-0">This month</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-trending-down bx-sm"></i>
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
                            <span class="text-heading">Net Profit</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">$16,830</h3>
                                <p class="text-success mb-0">(+24.8%)</p>
                            </div>
                            <p class="mb-0">This month</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
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
                            <span class="text-heading">Profit Margin</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2">37.2%</h3>
                                <p class="text-success mb-0">(+2.1%)</p>
                            </div>
                            <p class="mb-0">Current margin</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-pie-chart-alt bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue vs Expenses Chart -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Revenue vs Expenses Trend</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bx bx-bar-chart display-1 text-muted"></i>
                        <p class="text-muted mt-3">Interactive chart will be implemented in Phase 3</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Expense Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="expense-item d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    <i class="bx bx-car"></i>
                                </span>
                            </div>
                            <span>Transportation</span>
                        </div>
                        <div class="text-end">
                            <div class="fw-medium">$8,450</div>
                            <small class="text-muted">29.7%</small>
                        </div>
                    </div>
                    <div class="expense-item d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded-circle bg-label-success">
                                    <i class="bx bx-store"></i>
                                </span>
                            </div>
                            <span>Storage</span>
                        </div>
                        <div class="text-end">
                            <div class="fw-medium">$6,200</div>
                            <small class="text-muted">21.8%</small>
                        </div>
                    </div>
                    <div class="expense-item d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded-circle bg-label-info">
                                    <i class="bx bx-wrench"></i>
                                </span>
                            </div>
                            <span>Processing</span>
                        </div>
                        <div class="text-end">
                            <div class="fw-medium">$7,800</div>
                            <small class="text-muted">27.4%</small>
                        </div>
                    </div>
                    <div class="expense-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded-circle bg-label-warning">
                                    <i class="bx bx-cog"></i>
                                </span>
                            </div>
                            <span>Other</span>
                        </div>
                        <div class="text-end">
                            <div class="fw-medium">$6,000</div>
                            <small class="text-muted">21.1%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Financial Transactions</h5>
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
                                <th>Date</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>2024-01-15</td>
                                <td>Batch B001 Sale</td>
                                <td>Revenue</td>
                                <td><span class="badge bg-label-success">Income</span></td>
                                <td class="text-success">+$12,500</td>
                                <td><span class="badge bg-label-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>2024-01-14</td>
                                <td>Transportation Costs</td>
                                <td>Logistics</td>
                                <td><span class="badge bg-label-danger">Expense</span></td>
                                <td class="text-danger">-$2,450</td>
                                <td><span class="badge bg-label-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>2024-01-13</td>
                                <td>Storage Facility Rent</td>
                                <td>Facilities</td>
                                <td><span class="badge bg-label-danger">Expense</span></td>
                                <td class="text-danger">-$3,200</td>
                                <td><span class="badge bg-label-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td>2024-01-12</td>
                                <td>Batch B002 Sale</td>
                                <td>Revenue</td>
                                <td><span class="badge bg-label-success">Income</span></td>
                                <td class="text-success">+$8,900</td>
                                <td><span class="badge bg-label-success">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
