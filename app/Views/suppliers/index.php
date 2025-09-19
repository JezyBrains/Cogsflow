<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Supplier Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="bx bx-user-check me-2"></i>
                            Supplier Management
                        </h5>
                        <small class="text-muted">Manage your suppliers and vendor relationships</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('suppliers/export') ?>" class="btn btn-outline-success">
                            <i class="bx bx-download me-1"></i>Export CSV
                        </a>
                        <a href="<?= site_url('suppliers/new') ?>" class="btn btn-dark btn-icon">
                            <i class="bx bx-plus me-1"></i>Add Supplier
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?= site_url('suppliers') ?>" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search Suppliers</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?= esc($search) ?>" placeholder="Search by name, business, contact...">
                        </div>
                        <!-- Supplier type filter removed since column doesn't exist -->
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bx bx-search me-1"></i>Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Suppliers List -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        Suppliers List 
                        <span class="badge bg-primary ms-2"><?= $totalSuppliers ?> Total</span>
                    </h6>
                    <?php if (!empty($search) || !empty($type) || $status !== 'active'): ?>
                        <a href="<?= site_url('suppliers') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-x me-1"></i>Clear Filters
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($suppliers)): ?>
                        <div class="text-center py-5">
                            <i class="bx bx-user-x display-4 text-muted"></i>
                            <h5 class="mt-3">No suppliers found</h5>
                            <p class="text-muted">Try adjusting your search criteria or add a new supplier</p>
                            <a href="<?= site_url('suppliers/new') ?>" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i>Add First Supplier
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Supplier Details</th>
                                        <th>Contact Information</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                                            <?= strtoupper(substr($supplier['name'], 0, 2)) ?>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($supplier['name']) ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if (!empty($supplier['contact_person'])): ?>
                                                    <div><strong><?= esc($supplier['contact_person']) ?></strong></div>
                                                <?php endif; ?>
                                                <?php if (!empty($supplier['phone'])): ?>
                                                    <div><i class="bx bx-phone me-1"></i><?= esc($supplier['phone']) ?></div>
                                                <?php endif; ?>
                                                <?php if (!empty($supplier['email'])): ?>
                                                    <div><i class="bx bx-envelope me-1"></i><?= esc($supplier['email']) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <!-- Supplier type column removed since column doesn't exist -->
                                            <td>
                                                <?php
                                                $statusClass = match($supplier['status']) {
                                                    'active' => 'bg-success',
                                                    'inactive' => 'bg-warning',
                                                    'archived' => 'bg-secondary',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?= $statusClass ?>"><?= ucfirst($supplier['status']) ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M j, Y', strtotime($supplier['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="<?= site_url('suppliers/' . $supplier['id']) ?>">
                                                                <i class="bx bx-show me-2"></i>View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= site_url('suppliers/' . $supplier['id'] . '/edit') ?>">
                                                                <i class="bx bx-edit me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        <?php if ($supplier['status'] === 'active'): ?>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item text-warning" 
                                                                   href="<?= site_url('suppliers/' . $supplier['id'] . '/archive') ?>"
                                                                   onclick="return confirm('Are you sure you want to archive this supplier?')">
                                                                    <i class="bx bx-archive me-2"></i>Archive
                                                                </a>
                                                            </li>
                                                        <?php elseif ($supplier['status'] === 'archived'): ?>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item text-success" 
                                                                   href="<?= site_url('suppliers/' . $supplier['id'] . '/restore') ?>">
                                                                    <i class="bx bx-undo me-2"></i>Restore
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <div class="card-footer">
                                <nav aria-label="Suppliers pagination">
                                    <ul class="pagination justify-content-center mb-0">
                                        <?php if ($currentPage > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= site_url('suppliers?' . http_build_query(array_merge($_GET, ['page' => $currentPage - 1]))) ?>">
                                                    <i class="bx bx-chevron-left"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                                <a class="page-link" href="<?= site_url('suppliers?' . http_build_query(array_merge($_GET, ['page' => $i]))) ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($currentPage < $totalPages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= site_url('suppliers?' . http_build_query(array_merge($_GET, ['page' => $currentPage + 1]))) ?>">
                                                    <i class="bx bx-chevron-right"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        Showing page <?= $currentPage ?> of <?= $totalPages ?> 
                                        (<?= $totalSuppliers ?> total suppliers)
                                    </small>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit search form on type/status change
    const typeSelect = document.getElementById('type');
    const statusSelect = document.getElementById('status');
    
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    // Search input with debounce
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }
});
</script>
<?= $this->endSection() ?>
