<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Create New Batch<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-package me-2"></i>
                        Create New Batch
                    </h5>
                    <a href="<?= site_url('batches') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Back to Batches
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
                    
                    <form action="<?= site_url('batches/create') ?>" method="post" id="batchForm">
                        <!-- Basic Batch Information -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="batch_number" class="form-label">Batch Number</label>
                                    <input type="text" class="form-control" id="batch_number" name="batch_number" 
                                           value="<?= $batch_number ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label">Supplier *</label>
                                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                                        <option value="">Select Supplier</option>
                                        <?php foreach ($suppliers as $supplier): ?>
                                            <option value="<?= $supplier['id'] ?>" <?= old('supplier_id') == $supplier['id'] ? 'selected' : '' ?>>
                                                <?= esc($supplier['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="grain_type" class="form-label">Grain Type *</label>
                                    <select class="form-select" id="grain_type" name="grain_type" required>
                                        <option value="">Select Grain Type</option>
                                        <option value="wheat" <?= old('grain_type') == 'wheat' ? 'selected' : '' ?>>Wheat</option>
                                        <option value="rice" <?= old('grain_type') == 'rice' ? 'selected' : '' ?>>Rice</option>
                                        <option value="corn" <?= old('grain_type') == 'corn' ? 'selected' : '' ?>>Corn</option>
                                        <option value="barley" <?= old('grain_type') == 'barley' ? 'selected' : '' ?>>Barley</option>
                                        <option value="soybean" <?= old('grain_type') == 'soybean' ? 'selected' : '' ?>>Soybean</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="received_date" class="form-label">Received Date *</label>
                                    <input type="datetime-local" class="form-control" id="received_date" name="received_date" 
                                           value="<?= old('received_date', date('Y-m-d\TH:i')) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <input type="text" class="form-control" id="notes" name="notes" 
                                           value="<?= old('notes') ?>" placeholder="Additional notes...">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bag Details Section -->
                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">
                                <i class="bx bx-package me-2"></i>
                                Bag Details
                            </h6>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addBagBtn">
                                <i class="bx bx-plus me-1"></i>Add Bag
                            </button>
                        </div>
                        
                        <!-- Bags Container -->
                        <div id="bagsContainer">
                            <div class="row bag-row" data-bag-index="1">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Bag Number *</label>
                                        <input type="number" class="form-control bag-number" name="bags[1][bag_number]" 
                                               value="1" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Weight (kg) *</label>
                                        <input type="number" class="form-control bag-weight" name="bags[1][weight_kg]" 
                                               step="0.01" min="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Moisture (%) *</label>
                                        <input type="number" class="form-control bag-moisture" name="bags[1][moisture_percentage]" 
                                               step="0.01" min="0.01" max="99.99" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-outline-danger btn-sm d-block remove-bag-btn" 
                                                style="display: none !important;">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Summary Section -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">
                                            <i class="bx bx-calculator me-2"></i>
                                            Batch Summary
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <div class="h4 mb-1" id="totalBags">1</div>
                                                    <small class="text-muted">Total Bags</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <div class="h4 mb-1" id="totalWeight">0.00</div>
                                                    <small class="text-muted">Total Weight (kg)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <div class="h4 mb-1" id="totalMT">0.000</div>
                                                    <small class="text-muted">Total Weight (MT)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <div class="h4 mb-1" id="avgMoisture">0.00</div>
                                                    <small class="text-muted">Avg Moisture (%)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="<?= site_url('batches') ?>" class="btn btn-outline-secondary me-md-2">
                                <i class="bx bx-x me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-check me-1"></i>Create Batch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let bagIndex = 1;
    
    // Add bag functionality
    document.getElementById('addBagBtn').addEventListener('click', function() {
        bagIndex++;
        const bagsContainer = document.getElementById('bagsContainer');
        const newBagRow = createBagRow(bagIndex);
        bagsContainer.appendChild(newBagRow);
        updateBagNumbers();
        updateSummary();
    });
    
    // Remove bag functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-bag-btn')) {
            const bagRow = e.target.closest('.bag-row');
            if (document.querySelectorAll('.bag-row').length > 1) {
                bagRow.remove();
                updateBagNumbers();
                updateSummary();
            }
        }
    });
    
    // Update summary when inputs change
    document.addEventListener('input', function(e) {
        if (e.target.matches('.bag-weight, .bag-moisture')) {
            updateSummary();
        }
    });
    
    function createBagRow(index) {
        const div = document.createElement('div');
        div.className = 'row bag-row';
        div.setAttribute('data-bag-index', index);
        div.innerHTML = `
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Bag Number *</label>
                    <input type="number" class="form-control bag-number" name="bags[${index}][bag_number]" 
                           value="${index}" min="1" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Weight (kg) *</label>
                    <input type="number" class="form-control bag-weight" name="bags[${index}][weight_kg]" 
                           step="0.01" min="0.01" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Moisture (%) *</label>
                    <input type="number" class="form-control bag-moisture" name="bags[${index}][moisture_percentage]" 
                           step="0.01" min="0.01" max="99.99" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm d-block remove-bag-btn">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        `;
        return div;
    }
    
    function updateBagNumbers() {
        const bagRows = document.querySelectorAll('.bag-row');
        bagRows.forEach((row, index) => {
            const bagNumberInput = row.querySelector('.bag-number');
            bagNumberInput.value = index + 1;
            
            // Update name attributes
            const inputs = row.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${index + 1}]`);
                    input.setAttribute('name', newName);
                }
            });
            
            row.setAttribute('data-bag-index', index + 1);
        });
        
        // Show/hide remove buttons
        const removeButtons = document.querySelectorAll('.remove-bag-btn');
        removeButtons.forEach((btn, index) => {
            btn.style.display = bagRows.length > 1 ? 'block' : 'none';
        });
    }
    
    function updateSummary() {
        const bagRows = document.querySelectorAll('.bag-row');
        let totalBags = bagRows.length;
        let totalWeight = 0;
        let totalMoisture = 0;
        let validBags = 0;
        
        bagRows.forEach(row => {
            const weight = parseFloat(row.querySelector('.bag-weight').value) || 0;
            const moisture = parseFloat(row.querySelector('.bag-moisture').value) || 0;
            
            if (weight > 0 && moisture > 0) {
                totalWeight += weight;
                totalMoisture += moisture;
                validBags++;
            }
        });
        
        const avgMoisture = validBags > 0 ? totalMoisture / validBags : 0;
        const totalMT = totalWeight / 1000;
        
        document.getElementById('totalBags').textContent = totalBags;
        document.getElementById('totalWeight').textContent = totalWeight.toFixed(2);
        document.getElementById('totalMT').textContent = totalMT.toFixed(3);
        document.getElementById('avgMoisture').textContent = avgMoisture.toFixed(2);
    }
    
    // Initialize summary
    updateSummary();
});
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Form validation scripts can be added here
</script>
<?= $this->endSection() ?>
