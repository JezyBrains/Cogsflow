<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Expense Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h4 class="fw-bold">Expense Management</h4>
        <p class="text-muted mb-0">Track and manage all business expenses</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('expenses/export') ?>" class="btn btn-outline-secondary me-2">
            <i class="bx bx-download"></i> Export CSV
        </a>
        <a href="<?= site_url('expenses/new') ?>" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add New Expense
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1 text-muted">This Month</span>
                        <h3 class="card-title mb-1"><?= format_currency($stats['this_month_amount'] ?? 0) ?></h3>
                        <small class="text-success fw-semibold">
                            <i class="bx bx-up-arrow-alt"></i> <?= $stats['this_month_expenses'] ?? 0 ?> expenses
                        </small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-calendar-alt bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1 text-muted">This Year</span>
                        <h3 class="card-title mb-1"><?= format_currency($stats['this_year_amount'] ?? 0) ?></h3>
                        <small class="text-info fw-semibold">
                            <i class="bx bx-trending-up"></i> <?= $stats['this_year_expenses'] ?? 0 ?> expenses
                        </small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-line-chart bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1 text-muted">Total Expenses</span>
                        <h3 class="card-title mb-1"><?= format_currency($stats['total_amount'] ?? 0) ?></h3>
                        <small class="text-muted fw-semibold">
                            <?= $stats['total_expenses'] ?? 0 ?> total
                        </small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-wallet bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1 text-muted">Pending Approval</span>
                        <h3 class="card-title mb-1"><?= $stats['pending_approval'] ?? 0 ?></h3>
                        <small class="text-warning fw-semibold">
                            <i class="bx bx-time"></i> Awaiting review
                        </small>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-hourglass bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Breakdown -->
<?php if (!empty($categoryBreakdown)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">This Month's Expenses by Category</h5>
                <a href="<?= site_url('expenses/analytics') ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-bar-chart-alt-2"></i> View Analytics
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach (array_slice($categoryBreakdown, 0, 4) as $cat): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="badge badge-center rounded-pill bg-label-primary me-3 p-2">
                                <i class="bx bx-category bx-sm"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?= esc($cat['category'] ?? 'Uncategorized') ?></h6>
                                <small class="text-muted"><?= format_currency($cat['total_amount']) ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Expenses Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Reference</th>
                        <th>Recorded By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($expenses)): ?>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($expense['expense_date'])) ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?= esc(ucfirst($expense['category'])) ?></span>
                                </td>
                                <td><?= esc($expense['description']) ?></td>
                                <td><strong><?= format_currency($expense['amount']) ?></strong></td>
                                <td>
                                    <div><?= esc($expense['expense_number']) ?></div>
                                    <?php if (!empty($expense['receipt_number'])): ?>
                                        <div class="text-muted small">Receipt: <?= esc($expense['receipt_number']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($expense['vendor_name'])): ?>
                                        <?= esc($expense['vendor_name']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= site_url('expenses/show/' . $expense['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= site_url('expenses/edit/' . $expense['id']) ?>" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= site_url('expenses/delete/' . $expense['id']) ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No expenses found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
