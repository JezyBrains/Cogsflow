<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Total Batches</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2"><?= $stats['total_batches'] ?></h3>
                            </div>
                            <p class="mb-0">All time</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-package bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Pending Approval</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2"><?= $stats['pending_batches'] ?></h3>
                            </div>
                            <p class="mb-0">Awaiting review</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-time bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Ready for Dispatch</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2"><?= $stats['approved_batches'] ?></h3>
                            </div>
                            <p class="mb-0">Approved batches</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-check-circle bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Total Weight</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2"><?= number_format($stats['total_weight_mt'], 2) ?></h3>
                                <small class="text-muted">MT</small>
                            </div>
                            <p class="mb-0">Metric tons</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-weight bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-list-ul me-2"></i>
                        Batch List
                    </h5>
                    <a href="<?= site_url('batches/new') ?>" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>New Batch
                    </a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Batch Number</th>
                                <th>Supplier</th>
                                <th>Grain Type</th>
                                <th>Weight</th>
                                <th>Quality</th>
                                <th>Status</th>
                                <th>Received</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php if (empty($batches)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-package display-4 text-muted mb-2"></i>
                                            <span class="text-muted">No batches found</span>
                                            <a href="<?= site_url('batches/new') ?>" class="btn btn-sm btn-primary mt-2">
                                                <i class="bx bx-plus me-1"></i>Create First Batch
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($batches as $batch): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($batch['batch_number']) ?></strong>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium"><?= esc($batch['supplier_name']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-info"><?= ucfirst(esc($batch['grain_type'])) ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium"><?= number_format($batch['total_weight_mt'], 3) ?> MT</span>
                                                <small class="text-muted"><?= $batch['total_bags'] ?> bags</small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                            $gradeClass = match($batch['quality_grade']) {
                                                'A+' => 'success',
                                                'A' => 'primary', 
                                                'B+' => 'info',
                                                'B' => 'warning',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-label-<?= $gradeClass ?>"><?= esc($batch['quality_grade']) ?></span>
                                            <small class="d-block text-muted"><?= number_format($batch['average_moisture'], 1) ?>% moisture</small>
                                        </td>
                                        <td>
                                            <?php 
                                            $statusClass = match($batch['status']) {
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'dispatched' => 'info',
                                                'delivered' => 'primary',
                                                'rejected' => 'danger',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-label-<?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $batch['status'])) ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= date('M j, Y', strtotime($batch['received_date'])) ?></small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?= site_url('batches/view/' . $batch['id']) ?>">
                                                        <i class="bx bx-show me-1"></i> View Details
                                                    </a>
                                                    <?php if ($batch['status'] === 'pending'): ?>
                                                        <a class="dropdown-item" href="#" 
                                                           onclick="showApproveModal(<?= $batch['id'] ?>, '<?= esc($batch['batch_number']) ?>')">
                                                            <i class="bx bx-check me-1"></i> Approve
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger" href="#" 
                                                           onclick="showRejectModal(<?= $batch['id'] ?>, '<?= esc($batch['batch_number']) ?>')">
                                                            <i class="bx bx-x me-1"></i> Reject
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($batch['status'] === 'approved'): ?>
                                                        <a class="dropdown-item" href="<?= site_url('dispatches/new?batch_id=' . $batch['id']) ?>">
                                                            <i class="bx bx-car me-1"></i> Create Dispatch
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
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
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" id="rejectForm">
                <div class="modal-body">
                    <p>Are you sure you want to reject batch <strong id="rejectBatchNumber"></strong>?</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason *</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-check-circle text-success me-2"></i>
                    Approve Batch
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" id="approveForm">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            <span class="avatar-initial rounded-circle bg-label-success">
                                <i class="bx bx-check display-6"></i>
                            </span>
                        </div>
                        <h4 class="mb-2">Approve Batch?</h4>
                        <p class="text-muted mb-0">
                            Are you sure you want to approve batch <strong id="approveBatchNumber"></strong>?
                        </p>
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="bx bx-info-circle me-2"></i>
                            Once approved, this batch will be available for dispatch.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-check me-1"></i>Yes, Approve Batch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showApproveModal(batchId, batchNumber) {
    try {
        document.getElementById('approveBatchNumber').textContent = batchNumber;
        document.getElementById('approveForm').action = '<?= site_url('batches/approve/') ?>' + batchId;
        
        const modalElement = document.getElementById('approveModal');
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        
        modal.show();
    } catch (error) {
        console.error('Error showing approve modal:', error);
        if (confirm('Approve batch ' + batchNumber + '?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('batches/approve/') ?>' + batchId;
            document.body.appendChild(form);
            form.submit();
        }
    }
}

function showRejectModal(batchId, batchNumber) {
    try {
        document.getElementById('rejectBatchNumber').textContent = batchNumber;
        document.getElementById('rejectForm').action = '<?= site_url('batches/reject/') ?>' + batchId;
        
        const modalElement = document.getElementById('rejectModal');
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        
        modal.show();
    } catch (error) {
        console.error('Error showing reject modal:', error);
    }
}

// Cleanup function for stuck modal backdrops
function cleanupModals() {
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

// Add escape key listener and page load cleanup
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        cleanupModals();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    cleanupModals();
});
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Batch specific scripts can be added here
</script>
<?= $this->endSection() ?>
