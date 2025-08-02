<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Log New Expense<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Expense Details</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('expenses/log') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="expense_date" class="form-label">Expense Date</label>
                            <input type="date" class="form-control" id="expense_date" name="expense_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Select category</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Storage">Storage</option>
                                <option value="Labor">Labor</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Utilities">Utilities</option>
                                <option value="Insurance">Insurance</option>
                                <option value="Administrative">Administrative</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Select payment method</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Check">Check</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Mobile Money">Mobile Money</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="vendor" class="form-label">Vendor/Payee</label>
                            <input type="text" class="form-control" id="vendor" name="vendor" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reference" class="form-label">Reference/Receipt No.</label>
                            <input type="text" class="form-control" id="reference" name="reference">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="recorded_by" class="form-label">Recorded By</label>
                            <input type="text" class="form-control" id="recorded_by" name="recorded_by" required>
                        </div>
                        <div class="col-md-6">
                            <label for="approved_by" class="form-label">Approved By</label>
                            <input type="text" class="form-control" id="approved_by" name="approved_by">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= site_url('expenses') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Log Expense</button>
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
