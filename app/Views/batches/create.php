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
                        <?= csrf_field() ?>
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
                                    <label for="po_search" class="form-label">Purchase Order *</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" id="po_search" 
                                               placeholder="Search purchase orders..." autocomplete="off">
                                        <input type="hidden" id="purchase_order_id" name="purchase_order_id" required>
                                        <input type="hidden" id="supplier_id" name="supplier_id" required>
                                        <div id="po_dropdown" class="dropdown-menu w-100" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                                    </div>
                                    <div class="form-text">
                                        <i class="bx bx-search me-1"></i>
                                        Start typing to search for approved purchase orders
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="supplier_name" class="form-label">Supplier</label>
                                    <input type="text" class="form-control" id="supplier_name" name="supplier_name" readonly>
                                    <div class="form-text">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Auto-filled from selected purchase order
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="grain_type" class="form-label">Grain Type</label>
                                    <input type="text" class="form-control" id="grain_type" name="grain_type" readonly>
                                    <div class="form-text">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Auto-filled from selected purchase order
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="batch_created_date" class="form-label">Batch Created Date *</label>
                                    <input type="datetime-local" class="form-control" id="batch_created_date" name="batch_created_date" 
                                           value="<?= old('batch_created_date', date('Y-m-d\TH:i')) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <input type="text" class="form-control" id="notes" name="notes" 
                                           value="<?= old('notes') ?>" placeholder="Additional notes...">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Purchase Order Details Section -->
                        <div id="po_details_section" class="card bg-light mb-4" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bx bx-file-blank me-2"></i>
                                    Purchase Order Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="h5 mb-1" id="po_number">-</div>
                                            <small class="text-muted">PO Number</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="h5 mb-1" id="po_quantity">0.00</div>
                                            <small class="text-muted">Total Quantity (MT)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="h5 mb-1" id="po_remaining">0.00</div>
                                            <small class="text-muted">Remaining (MT)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="h5 mb-1" id="po_delivered">0.00</div>
                                            <small class="text-muted">Delivered (MT)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Existing Batches Section -->
                        <div id="existing_batches_section" class="card mb-4" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bx bx-list-ul me-2"></i>
                                    Existing Batches from this Purchase Order
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="existing_batches_list">
                                    <div class="text-center text-muted">
                                        <i class="bx bx-info-circle me-1"></i>
                                        No batches created yet for this purchase order
                                    </div>
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
                            <button type="submit" class="btn btn-success btn-lg shadow-sm">
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

    // Auto-add next bag after moisture is entered on the last row
    document.addEventListener('change', function(e) {
        if (e.target.matches('.bag-moisture')) {
            const bagRows = document.querySelectorAll('.bag-row');
            const lastRow = bagRows[bagRows.length - 1];
            const row = e.target.closest('.bag-row');
            const weight = parseFloat(row.querySelector('.bag-weight').value) || 0;
            const moisture = parseFloat(row.querySelector('.bag-moisture').value) || 0;
            if (row === lastRow && weight > 0 && moisture > 0) {
                bagIndex++;
                const newBagRow = createBagRow(bagIndex);
                document.getElementById('bagsContainer').appendChild(newBagRow);
                updateBagNumbers();
                updateSummary();
                // Focus the weight field of the new row
                const newWeight = newBagRow.querySelector('.bag-weight');
                if (newWeight) newWeight.focus();
            }
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

    // Purchase Order Search Functionality
    const poSearch = document.getElementById('po_search');
    const poIdInput = document.getElementById('purchase_order_id');
    const supplierIdInput = document.getElementById('supplier_id');
    const poDropdown = document.getElementById('po_dropdown');
    let purchaseOrders = [];
    let searchTimeout;

    // Fetch purchase orders on search
    async function searchPurchaseOrders(query) {
        try {
            console.log('Searching purchase orders with query:', query);
            const response = await fetch(`<?= site_url('purchase-orders/search') ?>?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            console.log('Purchase orders loaded:', data);
            
            // Handle both direct array response and wrapped response
            if (Array.isArray(data)) {
                purchaseOrders = data;
                displayPurchaseOrders(purchaseOrders);
            } else if (data.success) {
                purchaseOrders = data.data || [];
                displayPurchaseOrders(purchaseOrders);
            } else if (data.error) {
                console.error('API error:', data.error);
                purchaseOrders = [];
                displayPurchaseOrders([]);
            } else {
                console.error('Unexpected API response format');
                purchaseOrders = [];
                displayPurchaseOrders([]);
            }
        } catch (error) {
            console.error('Error loading purchase orders:', error);
            purchaseOrders = [];
            displayPurchaseOrders([]);
        }
    }

    // Display purchase orders in dropdown
    function displayPurchaseOrders(poList) {
        if (poList.length === 0) {
            poDropdown.innerHTML = '<div class="dropdown-item text-muted">No purchase orders found</div>';
        } else {
            poDropdown.innerHTML = poList.map(po => `
                <div class="dropdown-item po-option" data-id="${po.id}" data-po-number="${po.po_number}" 
                     data-supplier-id="${po.supplier_id}" data-supplier-name="${po.supplier_name}" 
                     data-grain-type="${po.grain_type}" data-quantity="${po.quantity_mt}" 
                     data-remaining="${po.remaining_quantity_mt}">
                    <div class="fw-bold">${po.po_number} - ${po.supplier_name}</div>
                    <small class="text-muted">Grain: ${po.grain_type} | Remaining: ${po.remaining_quantity_mt} MT</small>
                </div>
            `).join('');
        }
        
        poDropdown.style.display = 'block';
        poDropdown.classList.add('show');
    }

    // Handle purchase order search input
    poSearch.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        
        if (query.length < 1) {
            poDropdown.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchPurchaseOrders(query);
        }, 300);
    });

    // Handle purchase order selection
    poDropdown.addEventListener('click', function(e) {
        const option = e.target.closest('.po-option');
        if (option) {
            const poId = option.getAttribute('data-id');
            const poNumber = option.getAttribute('data-po-number');
            const supplierId = option.getAttribute('data-supplier-id');
            const supplierName = option.getAttribute('data-supplier-name');
            const grainType = option.getAttribute('data-grain-type');
            const quantity = option.getAttribute('data-quantity');
            const remaining = option.getAttribute('data-remaining');
            
            console.log('Selected PO:', poId, poNumber);
            
            // Fill form fields
            poSearch.value = `${poNumber} - ${supplierName}`;
            poIdInput.value = poId;
            supplierIdInput.value = supplierId;
            document.getElementById('supplier_name').value = supplierName;
            document.getElementById('grain_type').value = grainType;
            
            // Show and populate PO details section
            const poDetailsSection = document.getElementById('po_details_section');
            poDetailsSection.style.display = 'block';
            document.getElementById('po_number').textContent = poNumber;
            document.getElementById('po_quantity').textContent = parseFloat(quantity).toFixed(2);
            document.getElementById('po_remaining').textContent = parseFloat(remaining).toFixed(2);
            document.getElementById('po_delivered').textContent = (parseFloat(quantity) - parseFloat(remaining)).toFixed(2);
            
            // Load existing batches for this PO
            loadExistingBatches(poId);
            
            poDropdown.style.display = 'none';
            poDropdown.classList.remove('show');
        }
    });

    // Load existing batches for selected PO
    async function loadExistingBatches(poId) {
        try {
            const response = await fetch(`<?= site_url('purchase-orders/getBatches') ?>/${poId}`);
            const data = await response.json();
            
            const existingBatchesSection = document.getElementById('existing_batches_section');
            const existingBatchesList = document.getElementById('existing_batches_list');
            
            if (data.success && data.data.length > 0) {
                existingBatchesSection.style.display = 'block';
                
                const batchesHtml = data.data.map(batch => `
                    <div class="row mb-2 p-2 border rounded">
                        <div class="col-md-3">
                            <strong>${batch.batch_number}</strong><br>
                            <small class="text-muted">Status: <span class="badge bg-${batch.status === 'approved' ? 'success' : batch.status === 'pending' ? 'warning' : 'danger'}">${batch.status}</span></small>
                        </div>
                        <div class="col-md-3">
                            <strong>${batch.total_weight_mt} MT</strong><br>
                            <small class="text-muted">${batch.total_bags} bags</small>
                        </div>
                        <div class="col-md-3">
                            <strong>${batch.average_moisture}%</strong><br>
                            <small class="text-muted">Avg. Moisture</small>
                        </div>
                        <div class="col-md-3">
                            <strong>${new Date(batch.created_at).toLocaleDateString()}</strong><br>
                            <small class="text-muted">Created</small>
                        </div>
                    </div>
                `).join('');
                
                existingBatchesList.innerHTML = `
                    <div class="mb-3">
                        <strong>Total Transferred: ${data.total_transferred_mt.toFixed(2)} MT</strong>
                    </div>
                    ${batchesHtml}
                `;
            } else {
                existingBatchesSection.style.display = 'block';
                existingBatchesList.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="bx bx-info-circle me-1"></i>
                        No batches created yet for this purchase order
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading existing batches:', error);
        }
    }

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#po_search') && !e.target.closest('#po_dropdown')) {
            poDropdown.style.display = 'none';
            poDropdown.classList.remove('show');
        }
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Form validation scripts can be added here
</script>
<?= $this->endSection() ?>
