<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Batch Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Batch List</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('batches/new') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Batch
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Weight (kg)</th>
                        <th>Moisture (%)</th>
                        <th>Date Received</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">No batches found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Batch specific scripts can be added here
</script>
<?= $this->endSection() ?>
