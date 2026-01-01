<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Edit Purchase Order<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Edit Purchase Order</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('purchase-orders') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= site_url('purchase-orders/' . $purchaseOrder['id']) ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <option value="">Select supplier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= $supplier['id'] ?>" <?= ($supplier['id'] == $purchaseOrder['supplier_id']) ? 'selected' : '' ?>>
                                <?= esc($supplier['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="order_date" class="form-label">Order Date</label>
                    <input type="date" class="form-control" id="order_date" name="order_date" value="<?= date('Y-m-d', strtotime($purchaseOrder['order_date'])) ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="expected_delivery_date" class="form-label">Expected Delivery Date</label>
                    <input type="date" class="form-control" id="expected_delivery_date" name="expected_delivery_date" value="<?= date('Y-m-d', strtotime($purchaseOrder['expected_delivery_date'])) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="grain_type" class="form-label">Grain Type</label>
                    <select class="form-select" id="grain_type" name="grain_type" required>
                        <option value="">Select grain type</option>
                        <option value="Wheat" <?= ($purchaseOrder['grain_type'] == 'Wheat') ? 'selected' : '' ?>>Wheat</option>
                        <option value="Rice" <?= ($purchaseOrder['grain_type'] == 'Rice') ? 'selected' : '' ?>>Rice</option>
                        <option value="Corn" <?= ($purchaseOrder['grain_type'] == 'Corn') ? 'selected' : '' ?>>Maize</option>
                        <option value="Barley" <?= ($purchaseOrder['grain_type'] == 'Barley') ? 'selected' : '' ?>>Barley</option>
                        <option value="Sorghum" <?= ($purchaseOrder['grain_type'] == 'Sorghum') ? 'selected' : '' ?>>Sorghum</option>
                        <option value="Other" <?= ($purchaseOrder['grain_type'] == 'Other') ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="quantity_mt" class="form-label"><?= get_weight_label('Quantity') ?></label>
                    <input type="number" class="form-control" id="quantity_mt" name="quantity_mt" step="0.01" min="0" value="<?= esc($purchaseOrder['quantity_mt']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="unit_price" class="form-label">Unit Price (per <?= strtoupper(get_weight_unit()) ?>)</label>
                    <input type="number" class="form-control" id="unit_price" name="unit_price" step="0.01" min="0" value="<?= esc($purchaseOrder['unit_price']) ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="total_amount" class="form-label">Total Amount</label>
                    <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" min="0" value="<?= esc($purchaseOrder['total_amount']) ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Purchase Order
                    </button>
                    <a href="<?= site_url('purchase-orders') ?>" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity_mt');
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
