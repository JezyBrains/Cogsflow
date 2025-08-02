<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Create New Dispatch<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Dispatch Details</h5>
            </div>
            <div class="card-body">
                <form action="<?= site_url('dispatches/create') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="batch_id" class="form-label">Select Batch</label>
                            <select class="form-select" id="batch_id" name="batch_id" required>
                                <option value="">Select a batch</option>
                                <!-- Batch options will be populated from database in Phase 2 -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="dispatch_date" class="form-label">Dispatch Date</label>
                            <input type="date" class="form-control" id="dispatch_date" name="dispatch_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="transporter" class="form-label">Transporter Name</label>
                            <input type="text" class="form-control" id="transporter" name="transporter" required>
                        </div>
                        <div class="col-md-6">
                            <label for="vehicle_reg" class="form-label">Vehicle Registration</label>
                            <input type="text" class="form-control" id="vehicle_reg" name="vehicle_reg" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="driver_name" class="form-label">Driver Name</label>
                            <input type="text" class="form-control" id="driver_name" name="driver_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="driver_contact" class="form-label">Driver Contact</label>
                            <input type="text" class="form-control" id="driver_contact" name="driver_contact" required>
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
                        <button type="submit" class="btn btn-primary">Create Dispatch</button>
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
