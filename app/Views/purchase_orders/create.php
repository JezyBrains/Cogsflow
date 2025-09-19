<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Create Purchase Order<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Purchase Order Details</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('purchase-orders/create') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="supplier_search" class="form-label">Supplier</label>
                            <div class="position-relative">
                                <input type="text" class="form-control" id="supplier_search" name="supplier_search" placeholder="Search for supplier..." autocomplete="off" required>
                                <input type="hidden" id="supplier_id" name="supplier_id" required>
                                <div id="supplier_dropdown" class="position-absolute w-100 bg-white border rounded shadow-sm" style="display: none; max-height: 200px; overflow-y: auto; z-index: 1000; top: 100%;"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="order_date" class="form-label">Order Date</label>
                            <input type="date" class="form-control" id="order_date" name="order_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="expected_delivery" class="form-label">Expected Delivery Date</label>
                            <input type="date" class="form-control" id="expected_delivery" name="expected_delivery" required>
                        </div>
                        <div class="col-md-6">
                            <label for="grain_type" class="form-label">Grain Type</label>
                            <select class="form-select" id="grain_type" name="grain_type" required>
                                <option value="">Select grain type</option>
                                <option value="Wheat">Wheat</option>
                                <option value="Rice">Rice</option>
                                <option value="Corn">Maize</option>
                                <option value="Barley">Barley</option>
                                <option value="Sorghum">Sorghum</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity (kg)</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="unit_price" class="form-label">Unit Price</label>
                            <input type="text" class="form-control" id="unit_price" name="unit_price" placeholder="0.00" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="text" class="form-control" id="total_amount" name="total_amount">
                        </div>
                        <div class="col-md-6">
                            <label for="payment_terms" class="form-label">Payment Terms</label>
                            <select class="form-select" id="payment_terms" name="payment_terms" required>
                                <option value="">Select payment terms</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="Net 30">Net 30</option>
                                <option value="Net 60">Net 60</option>
                                <option value="Advance Payment">Advance Payment</option>
                                <option value="Paid in Full">Paid in Full</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="advance_payment" class="form-label">Amount Paid</label>
                            <input type="text" class="form-control" id="advance_payment" name="advance_payment" placeholder="0.00">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="paid_in_full" name="paid_in_full" value="1">
                                <label class="form-check-label" for="paid_in_full">
                                    Paid in Full
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= site_url('purchase-orders') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Create Purchase Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Calculate total amount when quantity or unit price changes
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const unitPriceInput = document.getElementById('unit_price');
        const totalAmountInput = document.getElementById('total_amount');
        const advancePaymentInput = document.getElementById('advance_payment');
        const paidInFullCheckbox = document.getElementById('paid_in_full');
        
        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            // Get raw value from formatted price input
            const unitPriceRaw = window.priceFormatter ? window.priceFormatter.unformatNumber(unitPriceInput.value) : unitPriceInput.value;
            const unitPrice = parseFloat(unitPriceRaw) || 0;
            const total = quantity * unitPrice;
            
            // Format and set total amount with thousands separator
            if (window.priceFormatter) {
                totalAmountInput.value = window.priceFormatter.formatNumber(total.toFixed(2));
            } else {
                totalAmountInput.value = total.toFixed(2);
            }

            if (paidInFullCheckbox.checked) {
                if (window.priceFormatter) {
                    advancePaymentInput.value = window.priceFormatter.formatNumber(total.toFixed(2));
                } else {
                    advancePaymentInput.value = total.toFixed(2);
                }
            }
        }
        
        quantityInput.addEventListener('input', calculateTotal);
        unitPriceInput.addEventListener('input', calculateTotal);

        paidInFullCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Set advance payment to total and disable editing
                const totalRaw = window.priceFormatter ? window.priceFormatter.unformatNumber(totalAmountInput.value) : totalAmountInput.value;
                const total = parseFloat(totalRaw) || 0;
                if (window.priceFormatter) {
                    advancePaymentInput.value = window.priceFormatter.formatNumber(total.toFixed(2));
                } else {
                    advancePaymentInput.value = total.toFixed(2);
                }
                advancePaymentInput.setAttribute('readonly', 'readonly');
            } else {
                advancePaymentInput.removeAttribute('readonly');
            }
        });
        
        // Wait for price formatter to load, then initialize
        setTimeout(function() {
            calculateTotal();
        }, 100);

        // Initialize supplier search
        initializeSupplierSearch();
    });

    function initializeSupplierSearch() {
        const supplierSearch = document.getElementById('supplier_search');
        const supplierIdInput = document.getElementById('supplier_id');
        const dropdown = document.getElementById('supplier_dropdown');
        let suppliers = [];
        let searchTimeout;

        // Load all suppliers initially
        console.log('Starting to fetch suppliers from:', '<?= site_url('suppliers/search') ?>');
        
        fetch('<?= site_url('suppliers/search') ?>')
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    suppliers = data.data;
                    console.log('Loaded suppliers:', suppliers);
                } else {
                    console.error('API returned success: false');
                }
            })
            .catch(error => {
                console.error('Error loading suppliers:', error);
            });

        // Search functionality
        supplierSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            console.log('Search query:', query, 'Suppliers available:', suppliers.length);
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (query.length < 1) {
                    dropdown.style.display = 'none';
                    supplierIdInput.value = ''; // Clear hidden field when search is empty
                    return;
                }

                const filteredSuppliers = suppliers.filter(supplier => 
                    (supplier.name && supplier.name.toLowerCase().includes(query)) ||
                    (supplier.contact_person && supplier.contact_person.toLowerCase().includes(query)) ||
                    (supplier.phone && supplier.phone.toLowerCase().includes(query))
                );

                console.log('Filtered suppliers:', filteredSuppliers);
                displaySupplierDropdown(filteredSuppliers);
            }, 200);
        });

        // Show all suppliers when input is focused and empty
        supplierSearch.addEventListener('focus', function() {
            if (this.value.length === 0 && suppliers.length > 0) {
                displaySupplierDropdown(suppliers.slice(0, 10)); // Show first 10 suppliers
            }
        });

        // Handle clicks outside dropdown
        document.addEventListener('click', function(e) {
            if (!supplierSearch.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        function displaySupplierDropdown(filteredSuppliers) {
            dropdown.innerHTML = '';
            
            if (filteredSuppliers.length === 0) {
                dropdown.innerHTML = '<div class="px-3 py-2 text-muted">No suppliers found</div>';
            } else {
                filteredSuppliers.forEach(supplier => {
                    const item = document.createElement('div');
                    item.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100';
                    item.style.cursor = 'pointer';
                    item.innerHTML = `
                        <div class="fw-bold">${supplier.name}</div>
                        <small class="text-muted">${supplier.contact_person || ''} ${supplier.phone ? '• ' + supplier.phone : ''}</small>
                    `;
                    
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        supplierSearch.value = supplier.name;
                        supplierIdInput.value = supplier.id;
                        dropdown.style.display = 'none';
                    });
                    
                    item.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f8f9fa';
                    });
                    
                    item.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '';
                    });
                    
                    dropdown.appendChild(item);
                });
            }
            
            dropdown.style.display = 'block';
        }
    }

</script>
<?= $this->endSection() ?>
