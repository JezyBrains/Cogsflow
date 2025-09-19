<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Expense Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Expense List</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('expenses/new') ?>" class="btn btn-dark">
            <i class="fas fa-plus"></i> Log New Expense
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- Expense Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Total This Month</h6>
                <h3>$<?= number_format($stats['this_month_amount'] ?? 0, 2) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Transportation</h6>
                <h3>$0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Storage</h6>
                <h3>$0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">Other</h6>
                <h3>$0.00</h3>
            </div>
        </div>
    </div>
</div>

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
                                <td>$<?= number_format($expense['amount'], 2) ?></td>
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
                                        <button class="btn btn-sm btn-outline-danger" title="Delete" 
                                                onclick="deleteExpense(<?= $expense['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<?= $this->section('scripts') ?>
<script>
function deleteExpense(id) {
    if (confirm('Are you sure you want to delete this expense? This action cannot be undone.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('expenses/delete/') ?>' + id;
        
        // Add CSRF token
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
