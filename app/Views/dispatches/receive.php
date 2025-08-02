<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Receive Dispatch<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Dispatch Receiving Form</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('dispatches/receive') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="dispatch_id" value="<?= $id ?? '' ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="received_date" class="form-label">Receiving Date</label>
                            <input type="date" class="form-control" id="received_date" name="received_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="received_by" class="form-label">Received By</label>
                            <input type="text" class="form-control" id="received_by" name="received_by" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="verified_weight" class="form-label">Verified Weight (kg)</label>
                            <input type="number" class="form-control" id="verified_weight" name="verified_weight" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="condition" class="form-label">Cargo Condition</label>
                            <select class="form-select" id="condition" name="condition" required>
                                <option value="">Select condition</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                                <option value="Damaged">Damaged</option>
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
                        <a href="<?= site_url('dispatches') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Confirm Receipt</button>
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
