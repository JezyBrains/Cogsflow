<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Add Expense Category<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url('expenses') ?>">Expenses</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('expenses/categories') ?>">Categories</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
        <h4 class="fw-bold">Add New Expense Category</h4>
    </div>
</div>

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= site_url('expenses/categories/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?= old('name') ?>" required>
                    <small class="text-muted">Enter a unique category name</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1" <?= old('is_active', '1') == '1' ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= old('is_active') == '0' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
                <small class="text-muted">Provide a brief description of this category</small>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Save Category
                </button>
                <a href="<?= site_url('expenses/categories') ?>" class="btn btn-secondary">
                    <i class="bx bx-x"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
