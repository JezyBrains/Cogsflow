<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Expense Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Expense List</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('expenses/new') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Log New Expense
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<!-- Expense Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Total This Month</h6>
                <h3>$0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Transportation</h6>
                <h3>$0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Storage</h6>
                <h3>$0.00</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-title">Other</h6>
                <h3>$0.00</h3>
            </div>
        </div>
    </div>
</div>

<!-- Expenses Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Reference</th>
                        <th>Recorded By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">No expenses found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Expense specific scripts can be added here
</script>
<?= $this->endSection() ?>
