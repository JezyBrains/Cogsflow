<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Add New Supplier<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-user-plus me-2"></i>
                        Add New Supplier
                    </h5>
                    <a href="<?= site_url('suppliers') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Back to Suppliers
                    </a>
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

                    <form action="<?= site_url('suppliers/create') ?>" method="post" id="supplierForm">
                        <?= csrf_field() ?>
                        
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
                                       value="<?= old('supplier_name') ?>" required>
                                <div class="form-text">Unique identifier for this supplier</div>
                            </div>
                            <!-- Business name field removed since column doesn't exist -->
                        </div>

                        <!-- TIN field removed since column doesn't exist -->

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
                                       value="<?= old('contact_person') ?>" placeholder="Full name of contact person">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?= old('phone') ?>" placeholder="+254712345678">
                                <div class="form-text">Include country code for international numbers</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= old('email') ?>" placeholder="supplier@example.com">
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Physical Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" 
                                          placeholder="Complete physical address including city and country"><?= old('address') ?></textarea>
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

                        <!-- Notes field removed since column doesn't exist -->

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= site_url('suppliers') ?>" class="btn btn-outline-secondary me-md-2">
                                <i class="bx bx-x me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-dark btn-lg">
                                <i class="bx bx-check me-1"></i>Create Supplier
                            </button>
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
    // businessNameInput removed since field doesn't exist
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
</script>
<?= $this->endSection() ?>
