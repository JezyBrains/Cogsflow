<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Edit Expense<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Edit Expense</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('expenses') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= site_url('expenses/update/' . $expense['id']) ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="expense_date" class="form-label">Expense Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="expense_date" name="expense_date" 
                               value="<?= date('Y-m-d', strtotime($expense['expense_date'])) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" 
                                            <?= ($expense['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                        <?= esc($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">
                            <a href="<?= site_url('expenses/categories') ?>" target="_blank">Manage categories</a>
                        </small>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="3" 
                          placeholder="Enter expense description" required><?= esc($expense['description']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount ($) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="amount" name="amount" 
                               value="<?= $expense['amount'] ?>" 
                               placeholder="0.00" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="vendor_supplier" class="form-label">Vendor/Supplier</label>
                        <input type="text" class="form-control" id="vendor_supplier" name="vendor_supplier" 
                               value="<?= esc($expense['vendor_name']) ?>" 
                               placeholder="Enter vendor or supplier name">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="receipt_reference" class="form-label">Receipt Reference</label>
                <input type="text" class="form-control" id="receipt_reference" name="receipt_reference" 
                       value="<?= esc($expense['receipt_number']) ?>" 
                       placeholder="Enter receipt number or reference">
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= site_url('expenses') ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Expense
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
