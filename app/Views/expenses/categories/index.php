<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Expense Categories<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h4 class="fw-bold">Expense Categories</h4>
        <p class="text-muted mb-0">Manage expense categories for better organization</p>
    </div>
    <div class="col-md-6 text-end">
        <?php if (isAdmin()): ?>
        <a href="<?= site_url('expenses/categories/create') ?>" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add New Category
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Categories Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Categories</h5>
        <span class="badge bg-label-primary"><?= count($categories) ?> categories</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Total Expenses</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($category['name']) ?></strong>
                                </td>
                                <td>
                                    <?= esc($category['description'] ?? 'No description') ?>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">
                                        <?= $category['expense_count'] ?? 0 ?> expenses
                                    </span>
                                </td>
                                <td>
                                    <strong><?= format_currency($category['total_amount'] ?? 0) ?></strong>
                                </td>
                                <td>
                                    <?php if ($category['is_active']): ?>
                                        <span class="badge bg-label-success">
                                            <i class="bx bx-check-circle"></i> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-label-secondary">
                                            <i class="bx bx-x-circle"></i> Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isAdmin()): ?>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?= site_url('expenses/categories/edit/' . $category['id']) ?>">
                                                <i class="bx bx-edit me-1"></i> Edit
                                            </a>
                                            <form action="<?= site_url('expenses/categories/toggle/' . $category['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bx bx-toggle-<?= $category['is_active'] ? 'left' : 'right' ?> me-1"></i> 
                                                    <?= $category['is_active'] ? 'Deactivate' : 'Activate' ?>
                                                </button>
                                            </form>
                                            <?php if (($category['expense_count'] ?? 0) == 0): ?>
                                            <div class="dropdown-divider"></div>
                                            <form action="<?= site_url('expenses/categories/delete/' . $category['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bx bx-category bx-lg text-muted mb-3"></i>
                                <p class="text-muted">No categories found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
