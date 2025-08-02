<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dispatch Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Dispatch List</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('dispatches/new') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Dispatch
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
                        <th>Transporter</th>
                        <th>Vehicle Reg</th>
                        <th>Batch ID</th>
                        <th>Dispatch Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">No dispatches found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Dispatch specific scripts can be added here
</script>
<?= $this->endSection() ?>
