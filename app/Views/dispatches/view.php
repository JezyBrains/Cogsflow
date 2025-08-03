<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dispatch Details - #<?= $dispatch['id'] ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h5>Dispatch Details - #<?= $dispatch['id'] ?></h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('dispatches') ?>" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Dispatches
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bx bx-error-circle me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Dispatch Summary -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bx bx-package me-2"></i>Dispatch Information</h6>
                <?php
                $statusClasses = [
                    'pending' => 'bg-warning',
                    'in_transit' => 'bg-info',
                    'delivered' => 'bg-success',
                    'cancelled' => 'bg-danger'
                ];
                $statusClass = $statusClasses[$dispatch['status']] ?? 'bg-secondary';
                ?>
                <span class="badge <?= $statusClass ?> fs-6"><?= ucfirst($dispatch['status']) ?></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Dispatch ID:</td>
                                <td>#<?= $dispatch['id'] ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Dispatch Date:</td>
                                <td><?= date('M d, Y', strtotime($dispatch['dispatch_date'])) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Transporter:</td>
                                <td><?= esc($dispatch['transporter_name']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Vehicle Registration:</td>
                                <td><span class="badge bg-secondary"><?= esc($dispatch['vehicle_registration']) ?></span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Driver Name:</td>
                                <td><?= esc($dispatch['driver_name']) ?></td>
                            </tr>
                            <?php if (!empty($dispatch['driver_phone'])): ?>
                            <tr>
                                <td class="fw-bold">Driver Phone:</td>
                                <td><a href="tel:<?= esc($dispatch['driver_phone']) ?>"><?= esc($dispatch['driver_phone']) ?></a></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Destination:</td>
                                <td><?= esc($dispatch['destination']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Created:</td>
                                <td><?= date('M d, Y H:i', strtotime($dispatch['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Last Updated:</td>
                                <td><?= date('M d, Y H:i', strtotime($dispatch['updated_at'])) ?></td>
                            </tr>
                            <?php if (!empty($dispatch['notes'])): ?>
                            <tr>
                                <td class="fw-bold">Notes:</td>
                                <td><?= nl2br(esc($dispatch['notes'])) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Batch Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bx bx-box me-2"></i>Batch Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Batch Number:</td>
                                <td>
                                    <a href="<?= site_url('batches/view/' . $dispatch['batch_id']) ?>" class="text-decoration-none">
                                        <?= esc($dispatch['batch_number']) ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Grain Type:</td>
                                <td><?= esc($dispatch['grain_type']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Total Weight:</td>
                                <td><?= number_format($dispatch['total_weight_mt'], 2) ?> MT</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Total Bags:</td>
                                <td><?= number_format($dispatch['total_bags']) ?> bags</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Supplier:</td>
                                <td><?= esc($dispatch['supplier_name']) ?></td>
                            </tr>
                            <?php if (!empty($dispatch['contact_person'])): ?>
                            <tr>
                                <td class="fw-bold">Contact Person:</td>
                                <td><?= esc($dispatch['contact_person']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($dispatch['phone'])): ?>
                            <tr>
                                <td class="fw-bold">Supplier Phone:</td>
                                <td><a href="tel:<?= esc($dispatch['phone']) ?>"><?= esc($dispatch['phone']) ?></a></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Panel -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bx bx-cog me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                <?php if ($dispatch['status'] === 'pending'): ?>
                    <div class="d-grid gap-2">
                        <form method="post" action="<?= site_url('dispatches/updateStatus/' . $dispatch['id']) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="in_transit">
                            <button type="submit" class="btn btn-info w-100">
                                <i class="bx bx-car me-2"></i>Mark In Transit
                            </button>
                        </form>
                        
                        <form method="post" action="<?= site_url('dispatches/updateStatus/' . $dispatch['id']) ?>" 
                              onsubmit="return confirm('Are you sure you want to cancel this dispatch? The batch will be returned to the available pool.')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bx bx-x-circle me-2"></i>Cancel Dispatch
                            </button>
                        </form>
                    </div>
                <?php elseif ($dispatch['status'] === 'in_transit'): ?>
                    <div class="d-grid gap-2">
                        <form method="post" action="<?= site_url('dispatches/updateStatus/' . $dispatch['id']) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bx bx-check-circle me-2"></i>Mark Delivered
                            </button>
                        </form>
                        
                        <form method="post" action="<?= site_url('dispatches/updateStatus/' . $dispatch['id']) ?>" 
                              onsubmit="return confirm('Are you sure you want to cancel this dispatch? The batch will be returned to the available pool.')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bx bx-x-circle me-2"></i>Cancel Dispatch
                            </button>
                        </form>
                    </div>
                <?php elseif ($dispatch['status'] === 'delivered'): ?>
                    <div class="alert alert-success text-center">
                        <i class="bx bx-check-circle fs-1"></i>
                        <p class="mb-0 mt-2">Dispatch completed successfully!</p>
                    </div>
                <?php elseif ($dispatch['status'] === 'cancelled'): ?>
                    <div class="alert alert-danger text-center">
                        <i class="bx bx-x-circle fs-1"></i>
                        <p class="mb-0 mt-2">Dispatch was cancelled</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bx bx-time me-2"></i>Status Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item <?= $dispatch['status'] === 'pending' ? 'active' : 'completed' ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Pending</h6>
                            <small>Dispatch created</small>
                        </div>
                    </div>
                    
                    <div class="timeline-item <?= $dispatch['status'] === 'in_transit' ? 'active' : ($dispatch['status'] === 'delivered' ? 'completed' : '') ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>In Transit</h6>
                            <small>Vehicle on the road</small>
                        </div>
                    </div>
                    
                    <div class="timeline-item <?= $dispatch['status'] === 'delivered' ? 'active completed' : '' ?>">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Delivered</h6>
                            <small>Cargo delivered</small>
                        </div>
                    </div>
                    
                    <?php if ($dispatch['status'] === 'cancelled'): ?>
                    <div class="timeline-item cancelled">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Cancelled</h6>
                            <small>Dispatch cancelled</small>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e9ecef;
    border: 2px solid #fff;
}

.timeline-item.active .timeline-marker {
    background: #0d6efd;
}

.timeline-item.completed .timeline-marker {
    background: #198754;
}

.timeline-item.cancelled .timeline-marker {
    background: #dc3545;
}

.timeline-content h6 {
    margin-bottom: 2px;
    font-size: 14px;
}

.timeline-content small {
    color: #6c757d;
    font-size: 12px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
<?= $this->endSection() ?>
