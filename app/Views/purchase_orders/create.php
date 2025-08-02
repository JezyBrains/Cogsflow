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
                            <label for="supplier" class="form-label">Supplier</label>
                            <input type="text" class="form-control" id="supplier" name="supplier" required>
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
                                <option value="Corn">Corn</option>
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
                            <input type="number" class="form-control" id="unit_price" name="unit_price" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" min="0" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="payment_terms" class="form-label">Payment Terms</label>
                            <select class="form-select" id="payment_terms" name="payment_terms" required>
                                <option value="">Select payment terms</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="Net 30">Net 30</option>
                                <option value="Net 60">Net 60</option>
                                <option value="Advance Payment">Advance Payment</option>
                            </select>
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
                        <button type="submit" class="btn btn-primary">Create Purchase Order</button>
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
        
        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(unitPriceInput.value) || 0;
            const total = quantity * unitPrice;
            totalAmountInput.value = total.toFixed(2);
        }
        
        quantityInput.addEventListener('input', calculateTotal);
        unitPriceInput.addEventListener('input', calculateTotal);
    });
</script>
<?= $this->endSection() ?>
