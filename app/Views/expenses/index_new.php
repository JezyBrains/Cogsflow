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

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" action="<?= site_url('expenses') ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="keyword" 
                       placeholder="Expense number, description..." 
                       value="<?= esc($filters['keyword'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Category</label>
                <select class="form-select" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" 
                                <?= ($filters['category'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                            <?= esc($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="pending" <?= ($filters['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= ($filters['status'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date" 
                       value="<?= esc($filters['start_date'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="end_date" 
                       value="<?= esc($filters['end_date'] ?? '') ?>">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-search-alt"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Expenses Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Expense Records</h5>
        <span class="badge bg-label-primary"><?= count($expenses) ?> records</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Expense #</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Recorded By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($expenses)): ?>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($expense['expense_date'])) ?></td>
                                <td>
                                    <strong><?= esc($expense['expense_number']) ?></strong>
                                    <?php if (!empty($expense['receipt_number'])): ?>
                                        <br><small class="text-muted">Receipt: <?= esc($expense['receipt_number']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-label-secondary">
                                        <?= esc($expense['category_name'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <?= esc(substr($expense['description'], 0, 50)) ?>
                                    <?= strlen($expense['description']) > 50 ? '...' : '' ?>
                                </td>
                                <td>
                                    <strong><?= format_currency($expense['amount']) ?></strong>
                                    <br><small class="text-muted"><?= esc($expense['payment_method']) ?></small>
                                </td>
                                <td>
                                    <?= esc($expense['recorded_by_name'] ?? 'N/A') ?>
                                    <br><small class="text-muted"><?= date('M d, H:i', strtotime($expense['created_at'])) ?></small>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'pending' => 'bg-label-warning',
                                        'approved' => 'bg-label-success',
                                        'rejected' => 'bg-label-danger'
                                    ];
                                    $statusIcon = [
                                        'pending' => 'bx-time',
                                        'approved' => 'bx-check-circle',
                                        'rejected' => 'bx-x-circle'
                                    ];
                                    ?>
                                    <span class="badge <?= $statusClass[$expense['approval_status']] ?? 'bg-label-secondary' ?>">
                                        <i class="bx <?= $statusIcon[$expense['approval_status']] ?? 'bx-question-mark' ?>"></i>
                                        <?= ucfirst($expense['approval_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?= site_url('expenses/show/' . $expense['id']) ?>">
                                                <i class="bx bx-show me-1"></i> View Details
                                            </a>
                                            <?php if ($expense['approval_status'] === 'pending'): ?>
                                                <a class="dropdown-item" href="<?= site_url('expenses/edit/' . $expense['id']) ?>">
                                                    <i class="bx bx-edit me-1"></i> Edit
                                                </a>
                                                <?php if (isAdmin()): ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-success" href="#" 
                                                       onclick="approveExpense(<?= $expense['id'] ?>); return false;">
                                                        <i class="bx bx-check me-1"></i> Approve
                                                    </a>
                                                    <a class="dropdown-item text-danger" href="#" 
                                                       onclick="rejectExpense(<?= $expense['id'] ?>); return false;">
                                                        <i class="bx bx-x me-1"></i> Reject
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (isAdmin()): ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" 
                                                   onclick="deleteExpense(<?= $expense['id'] ?>); return false;">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bx bx-search-alt bx-lg text-muted mb-3"></i>
                                <p class="text-muted">No expenses found. Try adjusting your filters or add a new expense.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Approval Notes (Optional)</label>
                        <textarea class="form-control" name="approval_notes" rows="3" 
                                  placeholder="Add any comments or notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectionForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="rejection_notes" rows="3" required
                                  placeholder="Please provide a reason for rejecting this expense..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function approveExpense(id) {
    const form = document.getElementById('approvalForm');
    form.action = '<?= site_url('expenses/approve/') ?>' + id;
    const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
    modal.show();
}

function rejectExpense(id) {
    const form = document.getElementById('rejectionForm');
    form.action = '<?= site_url('expenses/reject/') ?>' + id;
    const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
    modal.show();
}

function deleteExpense(id) {
    if (confirm('Are you sure you want to delete this expense? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('expenses/delete/') ?>' + id;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>
