<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Inventory Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Inventory Status</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('inventory/adjust') ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Adjust Inventory
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- Inventory Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Total Stock</h6>
                <h3>0 kg</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Available</h6>
                <h3>0 kg</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">Reserved</h6>
                <h3>0 kg</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6 class="card-title">Low Stock</h6>
                <h3>0</h3>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Details Table -->
<div class="card">
    <div class="card-header">
        <h6>Inventory Details by Grain Type</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Grain Type</th>
                        <th>Total Stock (kg)</th>
                        <th>Available (kg)</th>
                        <th>Reserved (kg)</th>
                        <th>Minimum Level (kg)</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">No inventory data found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Stock Movements -->
<div class="card mt-4">
    <div class="card-header">
        <h6>Recent Stock Movements</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Grain Type</th>
                        <th>Quantity (kg)</th>
                        <th>Reference</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center">No recent movements found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Inventory specific scripts can be added here
</script>
<?= $this->endSection() ?>
