<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Purchase Orders<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Purchase Order List</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('purchase-orders/new') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Purchase Order
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
                        <th>PO #</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">No purchase orders found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Purchase order specific scripts can be added here
</script>
<?= $this->endSection() ?>
