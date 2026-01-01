<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Batch Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="bx bx-package me-2"></i>
                            <?= esc($batch['batch_number']) ?>
                        </h5>
                        <p class="text-muted mb-0">Batch Details and Bag Information</p>
                    </div>
                    <div>
                        <a href="<?= site_url('batches') ?>" class="btn btn-outline-secondary me-2">
                            <i class="bx bx-arrow-back me-1"></i>Back to Batches
                        </a>
                        <?php if ($batch['status'] === 'pending'): ?>
                            <button type="button" class="btn btn-success me-2" 
                                    onclick="showApproveModal(<?= $batch['id'] ?>, '<?= esc($batch['batch_number']) ?>')">
                                <i class="bx bx-check me-1"></i>Approve
                            </button>
                            <button type="button" class="btn btn-danger" 
                                    onclick="showRejectModal(<?= $batch['id'] ?>, '<?= esc($batch['batch_number']) ?>')">
                                <i class="bx bx-x me-1"></i>Reject
                            </button>
                        <?php elseif ($batch['status'] === 'approved'): ?>
                            <a href="<?= site_url('dispatches/new?batch_id=' . $batch['id']) ?>" class="btn btn-primary">
                                <i class="bx bx-car me-1"></i>Create Dispatch
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Information -->
    <div class="row g-4 mb-4">
        <!-- Basic Information -->
        <div class="col-xl-8 col-lg-7">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        Batch Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Batch Number</label>
                            <div class="fw-medium"><?= esc($batch['batch_number']) ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Supplier</label>
                            <div class="fw-medium"><?= esc($batch['supplier_name']) ?></div>
                            <?php if (!empty($batch['contact_person'])): ?>
                                <small class="text-muted d-block"><?= esc($batch['contact_person']) ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Grain Type</label>
                            <div>
                                <span class="badge bg-label-info fs-6"><?= ucfirst(esc($batch['grain_type'])) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Quality Grade</label>
                            <div>
                                <?php 
                                $gradeClass = match($batch['quality_grade']) {
                                    'A+' => 'success',
                                    'A' => 'primary', 
                                    'B+' => 'info',
                                    'B' => 'warning',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-label-<?= $gradeClass ?> fs-6"><?= esc($batch['quality_grade']) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Received Date</label>
                            <div class="fw-medium"><?= date('F j, Y g:i A', strtotime($batch['received_date'])) ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <div>
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
                                <span class="badge bg-label-<?= $statusClass ?> fs-6"><?= ucfirst(str_replace('_', ' ', $batch['status'])) ?></span>
                            </div>
                        </div>
                        <?php if (!empty($batch['notes'])): ?>
                            <div class="col-12">
                                <label class="form-label text-muted">Notes</label>
                                <div class="fw-medium"><?= nl2br(esc($batch['notes'])) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-xl-4 col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bx bx-calculator me-2"></i>
                        Batch Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total Bags</span>
                        <span class="fw-medium h5 mb-0"><?= number_format($batch['total_bags']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total Weight (kg)</span>
                        <span class="fw-medium h5 mb-0"><?= number_format($batch['total_weight_kg'], 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total Weight (MT)</span>
                        <span class="fw-medium h5 mb-0 text-primary"><?= number_format($batch['total_weight_mt'], 3) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Average Moisture</span>
                        <span class="fw-medium h5 mb-0"><?= number_format($batch['average_moisture'], 2) ?>%</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Avg Weight/Bag</span>
                        <span class="fw-medium h5 mb-0"><?= number_format($batch['total_weight_kg'] / $batch['total_bags'], 2) ?> kg</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Management -->
    <?php if ($batch['status'] === 'pending'): ?>
    <div class="row mb-4">
        <div class="col-12">
            <?= view('documents/upload_widget', [
                'workflow_stage' => 'batch_approval',
                'reference_type' => 'batch',
                'reference_id' => $batch['id'],
                'document_types' => $document_types,
                'existing_documents' => $existing_documents,
                'required_documents' => $required_documents
            ]) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bag Details -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bx bx-package me-2"></i>
                        Individual Bag Details (<?= count($bags) ?> bags)
                    </h6>
                    <div class="btn-group">
                        <a href="<?= base_url('batch-receiving/print-labels-from-batch/' . $batch['id']) ?>" 
                           class="btn btn-primary btn-sm" target="_blank">
                            <i class="bx bx-printer me-1"></i>Print Bag Labels
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bx bx-export me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bx bx-file-pdf me-2"></i>Export PDF</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bx bx-file-excel me-2"></i>Export Excel</a></li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Bag #</th>
                                <th>Weight (kg)</th>
                                <th>Moisture (%)</th>
                                <th>Weight Status</th>
                                <th>Moisture Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php if (empty($bags)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        <span class="text-muted">No bag details available</span>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $avgWeight = $batch['total_weight_kg'] / $batch['total_bags'];
                                foreach ($bags as $bag): 
                                    $weightVariance = (($bag['weight_kg'] - $avgWeight) / $avgWeight) * 100;
                                    $moistureContent = $bag['moisture_content'] ?? 0;
                                    $moistureStatus = $moistureContent <= 14 ? 'good' : ($moistureContent <= 18 ? 'acceptable' : 'high');
                                ?>
                                    <tr>
                                        <td><strong><?= $bag['bag_number'] ?></strong></td>
                                        <td><?= number_format($bag['weight_kg'], 2) ?></td>
                                        <td><?= number_format($moistureContent, 2) ?></td>
                                        <td>
                                            <?php if (abs($weightVariance) <= 5): ?>
                                                <span class="badge bg-label-success">Normal</span>
                                            <?php elseif (abs($weightVariance) <= 10): ?>
                                                <span class="badge bg-label-warning">Variance</span>
                                            <?php else: ?>
                                                <span class="badge bg-label-danger">High Variance</span>
                                            <?php endif; ?>
                                            <small class="d-block text-muted"><?= sprintf('%+.1f%%', $weightVariance) ?></small>
                                        </td>
                                        <td>
                                            <?php if ($moistureStatus === 'good'): ?>
                                                <span class="badge bg-label-success">Good</span>
                                            <?php elseif ($moistureStatus === 'acceptable'): ?>
                                                <span class="badge bg-label-warning">Acceptable</span>
                                            <?php else: ?>
                                                <span class="badge bg-label-danger">High</span>
                                            <?php endif; ?>
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
        // Set the batch number
        document.getElementById('approveBatchNumber').textContent = batchNumber;
        // Set the form action
        document.getElementById('approveForm').action = '<?= site_url('batches/approve/') ?>' + batchId;
        
        // Get modal element
        const modalElement = document.getElementById('approveModal');
        
        // Create and show modal with proper options
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        
        modal.show();
    } catch (error) {
        console.error('Error showing approve modal:', error);
        // Fallback to browser confirm if modal fails
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
    // Remove any stuck modal backdrops
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    
    // Remove modal-open class from body
    document.body.classList.remove('modal-open');
    
    // Reset body styles
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

// Add escape key listener to cleanup modals
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        cleanupModals();
    }
});

// Cleanup on page load
document.addEventListener('DOMContentLoaded', function() {
    cleanupModals();
});
</script>
<?= $this->endSection() ?>
