<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Adjust Inventory<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Inventory Adjustment</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('inventory/adjust') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
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
                        <div class="col-md-6">
                            <label for="adjustment_date" class="form-label">Adjustment Date</label>
                            <input type="date" class="form-control" id="adjustment_date" name="adjustment_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="adjustment_type" class="form-label">Adjustment Type</label>
                            <select class="form-select" id="adjustment_type" name="adjustment_type" required>
                                <option value="">Select adjustment type</option>
                                <option value="Stock In">Stock In</option>
                                <option value="Stock Out">Stock Out</option>
                                <option value="Stock Transfer">Stock Transfer</option>
                                <option value="Stock Correction">Stock Correction</option>
                                <option value="Damage/Loss">Damage/Loss</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity (kg)</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="reference" class="form-label">Reference</label>
                            <input type="text" class="form-control" id="reference" name="reference" placeholder="e.g., Batch ID, PO Number">
                        </div>
                        <div class="col-md-6">
                            <label for="adjusted_by" class="form-label">Adjusted By</label>
                            <input type="text" class="form-control" id="adjusted_by" name="adjusted_by" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="reason" class="form-label">Reason for Adjustment</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> This adjustment will update the inventory balance. Please ensure all information is accurate before submitting.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= site_url('inventory') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit Adjustment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Form validation scripts can be added here
</script>
<?= $this->endSection() ?>
