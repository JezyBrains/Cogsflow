<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Create New Batch<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Batch Details</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('batches/create') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="supplier" class="form-label">Supplier</label>
                            <input type="text" class="form-control" id="supplier" name="supplier" required>
                        </div>
                        <div class="col-md-6">
                            <label for="batch_date" class="form-label">Batch Date</label>
                            <input type="date" class="form-control" id="batch_date" name="batch_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" class="form-control" id="weight" name="weight" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="moisture" class="form-label">Moisture (%)</label>
                            <input type="number" class="form-control" id="moisture" name="moisture" step="0.01" min="0" max="100" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= site_url('batches') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Batch</button>
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
