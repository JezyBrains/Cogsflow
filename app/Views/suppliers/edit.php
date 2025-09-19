<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Edit Supplier<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-edit me-2"></i>
                        Edit Supplier: <?= esc($supplier['name']) ?>
                    </h5>
                    <div class="btn-group">
                        <a href="<?= site_url('suppliers/' . $supplier['id']) ?>" class="btn btn-outline-info">
                            <i class="bx bx-show me-1"></i>View Details
                        </a>
                        <a href="<?= site_url('suppliers') ?>" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Back to Suppliers
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <h6 class="alert-heading mb-1">Validation Errors:</h6>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('suppliers/' . $supplier['id'] . '/update') ?>" method="post" id="supplierForm">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-muted mb-3">
                                    <i class="bx bx-info-circle me-1"></i>Basic Information
                                </h6>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="supplier_name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="supplier_name" name="supplier_name" 
                                       value="<?= old('supplier_name', $supplier['name']) ?>" required>
                                <div class="form-text">Unique identifier for this supplier</div>
                            </div>
                            <!-- Business name field removed since column doesn't exist -->
                        </div>

                        <!-- Contact Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-muted mb-3">
                                    <i class="bx bx-phone me-1"></i>Contact Information
                                </h6>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="contact_person" class="form-label">Primary Contact Person</label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person" 
                                       value="<?= old('contact_person', $supplier['contact_person']) ?>" placeholder="Full name of contact person">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?= old('phone', $supplier['phone']) ?>" placeholder="+254712345678">
                                <div class="form-text">Include country code for international numbers</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= old('email', $supplier['email']) ?>" placeholder="supplier@example.com">
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Physical Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" 
                                          placeholder="Complete physical address including city and country"><?= old('address', $supplier['address']) ?></textarea>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-muted mb-3">
                                    <i class="bx bx-note me-1"></i>Additional Information
                                </h6>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" <?= old('status', $supplier['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= old('status', $supplier['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                                <div class="form-text">Current operational status of this supplier</div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="bx bx-time me-1"></i>
                                        Created: <?= date('M j, Y g:i A', strtotime($supplier['created_at'])) ?>
                                    </small>
                                </div>
                                <?php if ($supplier['updated_at'] !== $supplier['created_at']): ?>
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="bx bx-edit me-1"></i>
                                            Last Updated: <?= date('M j, Y g:i A', strtotime($supplier['updated_at'])) ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="notes" class="form-label">Notes/Comments</label>
                                <textarea class="form-control" id="notes" name="notes" rows="4" 
                                          placeholder="Any additional notes about this supplier, special terms, or important information..."><?= old('notes', $supplier['notes'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <div>
                                <?php if ($supplier['status'] !== 'archived'): ?>
                                    <button type="button" class="btn btn-outline-warning" onclick="archiveSupplier()">
                                        <i class="bx bx-archive me-1"></i>Archive Supplier
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-outline-success" onclick="restoreSupplier()">
                                        <i class="bx bx-undo me-1"></i>Restore Supplier
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="<?= site_url('suppliers') ?>" class="btn btn-outline-secondary me-md-2">
                                    <i class="bx bx-x me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bx bx-check me-1"></i>Update Supplier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('supplierForm');
    const supplierNameInput = document.getElementById('supplier_name');
    const phoneInput = document.getElementById('phone');
    const emailInput = document.getElementById('email');

    // Real-time validation
    supplierNameInput.addEventListener('blur', function() {
        validateSupplierName(this.value);
    });

    emailInput.addEventListener('blur', function() {
        validateEmail(this.value);
    });

    phoneInput.addEventListener('input', function() {
        formatPhoneNumber(this);
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = ['supplier_name'];
        
        requiredFields.forEach(function(fieldName) {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Validate email if provided
        const email = emailInput.value.trim();
        if (email && !isValidEmail(email)) {
            emailInput.classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            showAlert('Please fill in all required fields correctly.', 'danger');
        }
    });

    function validateSupplierName(name) {
        if (name.length < 2) {
            supplierNameInput.classList.add('is-invalid');
            return false;
        }
        supplierNameInput.classList.remove('is-invalid');
        return true;
    }

    function validateEmail(email) {
        if (email && !isValidEmail(email)) {
            emailInput.classList.add('is-invalid');
            return false;
        }
        emailInput.classList.remove('is-invalid');
        return true;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '');
        
        // Auto-add Kenya country code if number starts with 7
        if (value.startsWith('7') && value.length === 9) {
            value = '254' + value;
        }
        
        // Format with country code
        if (value.startsWith('254') && value.length === 12) {
            input.value = '+254 ' + value.substring(3, 6) + ' ' + value.substring(6, 9) + ' ' + value.substring(9);
        }
    }

    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(alertDiv, cardBody.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
});

function archiveSupplier() {
    if (confirm('Are you sure you want to archive this supplier? This will hide them from active lists but preserve all historical data.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('suppliers/' . $supplier['id'] . '/archive') ?>';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function restoreSupplier() {
    if (confirm('Are you sure you want to restore this supplier? This will make them active again.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('suppliers/' . $supplier['id'] . '/restore') ?>';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>
