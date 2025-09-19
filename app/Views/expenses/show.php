<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Expense Details<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Expense Details</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('expenses') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="<?= site_url('expenses/edit/' . $expense['id']) ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <button class="btn btn-danger" onclick="deleteExpense(<?= $expense['id'] ?>)">
            <i class="fas fa-trash"></i> Delete
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Expense Information</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Expense Number:</strong></div>
                    <div class="col-sm-9"><?= esc($expense['expense_number']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Date:</strong></div>
                    <div class="col-sm-9"><?= date('F d, Y', strtotime($expense['expense_date'])) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Category:</strong></div>
                    <div class="col-sm-9">
                        <span class="badge bg-secondary"><?= esc(ucfirst($expense['category'])) ?></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Description:</strong></div>
                    <div class="col-sm-9"><?= esc($expense['description']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Amount:</strong></div>
                    <div class="col-sm-9">
                        <h5 class="text-primary">$<?= number_format($expense['amount'], 2) ?></h5>
                    </div>
                </div>
                <?php if (!empty($expense['vendor_name'])): ?>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Vendor/Supplier:</strong></div>
                    <div class="col-sm-9"><?= esc($expense['vendor_name']) ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($expense['receipt_number'])): ?>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Receipt Number:</strong></div>
                    <div class="col-sm-9"><?= esc($expense['receipt_number']) ?></div>
                </div>
                <?php endif; ?>
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Reference Type:</strong></div>
                    <div class="col-sm-9">
                        <span class="badge bg-info"><?= esc(ucfirst($expense['reference_type'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= site_url('expenses/edit/' . $expense['id']) ?>" class="btn btn-outline-warning">
                        <i class="fas fa-edit"></i> Edit Expense
                    </a>
                    <button class="btn btn-outline-danger" onclick="deleteExpense(<?= $expense['id'] ?>)">
                        <i class="fas fa-trash"></i> Delete Expense
                    </button>
                    <a href="<?= site_url('expenses/new') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i> Log New Expense
                    </a>
                </div>
            </div>
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
