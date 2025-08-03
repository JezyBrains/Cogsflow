<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dispatch Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><?= $stats['total_dispatches'] ?></h4>
                        <small>Total Dispatches</small>
                    </div>
                    <i class="bx bx-package fs-2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><?= $stats['pending_dispatches'] ?></h4>
                        <small>Pending</small>
                    </div>
                    <i class="bx bx-time fs-2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><?= $stats['in_transit_dispatches'] ?></h4>
                        <small>In Transit</small>
                    </div>
                    <i class="bx bx-car fs-2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><?= $stats['delivered_dispatches'] ?></h4>
                        <small>Delivered</small>
                    </div>
                    <i class="bx bx-check-circle fs-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <h5>Dispatch List</h5>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= site_url('dispatches/new') ?>" class="btn btn-primary">
            <i class="bx bx-plus"></i> New Dispatch
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

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Batch</th>
                        <th>Dispatcher</th>
                        <th>Vehicle Reg</th>
                        <th>Destination</th>
                        <th>Est. Arrival</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dispatches)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bx bx-package fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No dispatches found. <a href="<?= site_url('dispatches/new') ?>">Create your first dispatch</a></p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dispatches as $dispatch): ?>
                            <tr>
                                <td><strong>#<?= $dispatch['id'] ?></strong></td>
                                <td>
                                    <div>
                                        <strong><?= esc($dispatch['batch_number']) ?></strong>
                                        <br><small class="text-muted"><?= esc($dispatch['grain_type']) ?> - <?= number_format($dispatch['total_weight_mt'], 2) ?> MT</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= esc($dispatch['dispatcher_name']) ?></strong>
                                        <br><small class="text-muted"><?= esc($dispatch['driver_name']) ?></small>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary"><?= esc($dispatch['vehicle_number']) ?></span></td>
                                <td><?= esc($dispatch['destination']) ?></td>
                                <td><?= date('M d, Y', strtotime($dispatch['estimated_arrival'])) ?></td>
                                <td>
                                    <?php
                                    $statusClasses = [
                                        'pending' => 'bg-warning',
                                        'in_transit' => 'bg-info',
                                        'delivered' => 'bg-success',
                                        'cancelled' => 'bg-danger'
                                    ];
                                    $statusClass = $statusClasses[$dispatch['status']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($dispatch['status']) ?></span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?= site_url('dispatches/view/' . $dispatch['id']) ?>"><i class="bx bx-show me-2"></i>View Details</a></li>
                                            <?php if ($dispatch['status'] === 'pending'): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="post" action="<?= site_url('dispatches/updateStatus/' . $dispatch['id']) ?>" class="d-inline">
                                                        <input type="hidden" name="status" value="in_transit">
                                                        <button type="submit" class="dropdown-item text-info"><i class="bx bx-car me-2"></i>Mark In Transit</button>
                                                    </form>
                                                </li>
                                            <?php elseif ($dispatch['status'] === 'in_transit'): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="post" action="<?= site_url('dispatches/updateStatus/' . $dispatch['id']) ?>" class="d-inline">
                                                        <input type="hidden" name="status" value="delivered">
                                                        <button type="submit" class="dropdown-item text-success"><i class="bx bx-check-circle me-2"></i>Mark Delivered</button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (in_array($dispatch['status'], ['pending', 'in_transit'])): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="post" action="<?= site_url('dispatches/updateStatus/' . $dispatch['id']) ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this dispatch? The batch will be returned to the available pool.')">
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="dropdown-item text-danger"><i class="bx bx-x-circle me-2"></i>Cancel Dispatch</button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
<?= $this->endSection() ?>
