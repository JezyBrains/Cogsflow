<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Batch Receiving<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
<li class="breadcrumb-item"><a href="<?= base_url('/batches') ?>">Batch Management</a></li>
<li class="breadcrumb-item active">Batch Receiving</li>
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="d-flex gap-2">
    <button type="button" class="btn btn-primary" onclick="refreshPage()">
        <i class="bx bx-refresh me-1"></i> Refresh
    </button>
    <a href="<?= base_url('/batches') ?>" class="btn btn-outline-secondary">
        <i class="bx bx-package me-1"></i> View All Batches
    </a>
    <a href="<?= base_url('/batches/new') ?>" class="btn btn-outline-primary">
        <i class="bx bx-plus me-1"></i> Create New Batch
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Pending Inspections</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2"><?= count($arriveddispatches ?? []) ?></h3>
                                <p class="text-warning mb-0">(awaiting)</p>
                            </div>
                            <p class="mb-0">Dispatches to inspect</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-truck bx-sm"></i>
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
                            <span class="text-heading">Completed Today</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2"><?= $stats['completed_today'] ?? 0 ?></h3>
                                <p class="text-success mb-0">(today)</p>
                            </div>
                            <p class="mb-0">Inspections by you</p>
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
                            <span class="text-heading">Total Completed</span>
                            <div class="d-flex align-items-center my-2">
                                <h3 class="mb-0 me-2"><?= $stats['total_completed'] ?? 0 ?></h3>
                                <p class="text-info mb-0">(all time)</p>
                            </div>
                            <p class="mb-0">Your inspections</p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-clipboard bx-sm"></i>
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
                            <span class="text-heading">Current Inspector</span>
                            <div class="d-flex align-items-center my-2">
                                <h6 class="mb-0 me-2 text-truncate" style="max-width: 120px;" title="<?= $current_user ?>"><?= $current_user ?></h6>
                            </div>
                            <p class="mb-0"><?= date('M d, Y H:i') ?></p>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-user bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Inspections Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="bx bx-truck me-2"></i>Dispatches Awaiting Inspection
            </h5>
            <small class="text-muted">Batches that have been delivered/dispatched and need quality inspection</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dispatches-table">
                    <thead class="table-light">
                        <tr>
                            <th>Dispatch #</th>
                            <th>Batch #</th>
                            <th>Supplier</th>
                            <th>Grain Type</th>
                            <th>Expected Weight (<?= strtoupper(get_weight_unit()) ?>)</th>
                            <th>Bags</th>
                            <th>Arrival Date</th>
                            <th>Status</th>
                            <th>PO Number</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($arriveddispatches ?? [])): ?>
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bx bx-truck display-1 text-muted mb-3"></i>
                                        <h5 class="text-muted">No dispatches awaiting inspection</h5>
                                        <p class="text-muted">All delivered dispatches have been inspected.</p>
                                    </div>
                                </td>
                            </tr>
                                <?php else: ?>
                                    <?php foreach (($arriveddispatches ?? []) as $dispatch): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary"><?= esc($dispatch['dispatch_number']) ?></span>
                                            </td>
                                            <td>
                                                <strong><?= esc($dispatch['batch_number']) ?></strong>
                                            </td>
                                            <td><?= esc($dispatch['supplier_name'] ?? 'N/A') ?></td>
                                            <td>
                                                <span class="badge bg-info"><?= esc($dispatch['grain_type']) ?></span>
                                            </td>
                                            <td><?= number_format($dispatch['total_weight_mt'], 3) ?> MT</td>
                                            <td><?= number_format($dispatch['total_bags']) ?></td>
                                            <td>
                                                <?php if ($dispatch['actual_arrival']): ?>
                                                    <small class="text-muted"><?= date('M d, Y', strtotime($dispatch['actual_arrival'])) ?></small><br>
                                                    <small class="text-muted"><?= date('H:i', strtotime($dispatch['actual_arrival'])) ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">Not recorded</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (in_array($dispatch['status'], ['delivered', 'dispatched']) && empty($dispatch['received_by']) && empty($dispatch['inspection_date'])): ?>
                                                    <span class="badge bg-warning">Awaiting Inspection</span>
                                                <?php elseif (in_array($dispatch['status'], ['delivered', 'dispatched']) && !empty($dispatch['received_by']) && !empty($dispatch['inspection_date'])): ?>
                                                    <span class="badge bg-success">Inspected & Delivered</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?= ucfirst($dispatch['status']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($dispatch['po_number']): ?>
                                                    <span class="badge bg-secondary"><?= esc($dispatch['po_number']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= base_url('batch-receiving/inspection/' . $dispatch['id']) ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="bx bx-clipboard-check me-1"></i>
                                                    <?= (empty($dispatch['received_by']) && empty($dispatch['inspection_date'])) ? 'Inspect Delivery' : 'View Details' ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Inspections -->
    <?php if (!empty($recentInspections ?? [])): ?>
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="bx bx-history me-2"></i>Your Recent Inspections
            </h5>
            <small class="text-muted">Last 10 inspections completed by you</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Batch #</th>
                                <th>Grain Type</th>
                                <th>Inspection Date</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                            <tbody>
                                <?php foreach (($recentInspections ?? []) as $inspection): ?>
                                    <tr>
                                        <td><strong><?= esc($inspection['batch_number']) ?></strong></td>
                                        <td><span class="badge bg-info"><?= esc($inspection['grain_type']) ?></span></td>
                                        <td>
                                            <?php if ($inspection['inspection_date']): ?>
                                                <small><?= date('M d, Y H:i', strtotime($inspection['inspection_date'])) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Delivered</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-primary btn-sm" 
                                                    onclick="viewBatchHistory(<?= $inspection['batch_id'] ?>)">
                                                <i class="bx bx-show me-1"></i> Details
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Batch History Modal -->
<div class="modal fade" id="batchHistoryModal" tabindex="-1" aria-labelledby="batchHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="batchHistoryModalLabel">
                    <i class="bx bx-history me-2"></i>Batch Inspection History
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="batchHistoryContent" style="max-height: 70vh; overflow-y: auto;">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading batch history...</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function refreshPage() {
    // Show loading state
    const refreshBtn = document.querySelector('[onclick="refreshPage()"]');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Refreshing...';
    refreshBtn.disabled = true;
    
    // Refresh after a short delay
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

function viewBatchHistory(batchId) {
    const modal = new bootstrap.Modal(document.getElementById('batchHistoryModal'));
    modal.show();
    
    // Reset content with loading state
    document.getElementById('batchHistoryContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading batch history...</p>
        </div>
    `;
    
    // Load batch history via AJAX
    fetch(`<?= base_url('batch-receiving/batch-history/') ?>${batchId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.batch) {
                let historyHtml = `
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-3"><i class="bx bx-package me-2"></i>Batch Information</h6>
                                    <div class="mb-2"><strong>Batch Number:</strong> <span class="badge bg-primary ms-2">${data.batch.batch_number}</span></div>
                                    <div class="mb-2"><strong>Grain Type:</strong> <span class="badge bg-info ms-2">${data.batch.grain_type}</span></div>
                                    <div class="mb-2"><strong>Total Weight:</strong> <span class="text-primary fw-bold ms-2">${data.batch.total_weight_mt} MT</span></div>
                                    <div class="mb-0"><strong>Total Bags:</strong> <span class="text-primary fw-bold ms-2">${data.batch.total_bags || 'N/A'}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-3"><i class="bx bx-check-circle me-2"></i>Current Status</h6>
                                    <div class="mb-2"><strong>Status:</strong> <span class="badge bg-success ms-2">${data.batch.status}</span></div>
                                    <div class="mb-2"><strong>Supplier:</strong> <span class="text-muted ms-2">${data.batch.supplier_name || 'N/A'}</span></div>
                                    <div class="mb-0"><strong>Created:</strong> <small class="text-muted ms-2">${new Date(data.batch.created_at).toLocaleDateString()}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add bag inspection details if available
                if (data.bag_inspections && data.bag_inspections.length > 0) {
                    const inspectedBags = data.bag_inspections.filter(b => b.inspection_status === 'inspected').length;
                    const totalBags = data.bag_inspections.length;
                    const goodBags = data.bag_inspections.filter(b => b.condition_status === 'good').length;
                    const damagedBags = data.bag_inspections.filter(b => ['damaged', 'wet', 'contaminated'].includes(b.condition_status)).length;
                    const missingBags = data.bag_inspections.filter(b => b.condition_status === 'missing').length;
                    
                    historyHtml += `
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-3"><i class="bx bx-grid-alt me-2"></i>Bag Inspection Summary</h6>
                                <div class="row g-2 text-center">
                                    <div class="col-3">
                                        <div class="p-2 rounded" style="background: rgba(105, 108, 255, 0.1);">
                                            <h5 class="mb-0 text-primary">${totalBags}</h5>
                                            <small class="text-muted">Total Bags</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="p-2 rounded" style="background: rgba(40, 199, 111, 0.1);">
                                            <h5 class="mb-0 text-success">${goodBags}</h5>
                                            <small class="text-muted">Good</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="p-2 rounded" style="background: rgba(255, 159, 67, 0.1);">
                                            <h5 class="mb-0 text-warning">${damagedBags}</h5>
                                            <small class="text-muted">Damaged</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="p-2 rounded" style="background: rgba(234, 84, 85, 0.1);">
                                            <h5 class="mb-0 text-danger">${missingBags}</h5>
                                            <small class="text-muted">Missing</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: ${(goodBags/totalBags*100)}%"></div>
                                        <div class="progress-bar bg-warning" style="width: ${(damagedBags/totalBags*100)}%"></div>
                                        <div class="progress-bar bg-danger" style="width: ${(missingBags/totalBags*100)}%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Inspection Progress: ${inspectedBags}/${totalBags} bags (${Math.round(inspectedBags/totalBags*100)}%)</small>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                if (data.history && data.history.length > 0) {
                    historyHtml += `
                        <h6 class="mb-3"><i class="bx bx-time me-2"></i>Batch Lifecycle Timeline</h6>
                        <div class="timeline">
                    `;
                    
                    // Action icons and colors mapping
                    const actionConfig = {
                        'created': { icon: 'bx-plus-circle', color: 'primary', label: 'Batch Created', role: 'Creator' },
                        'approved': { icon: 'bx-check-circle', color: 'success', label: 'Batch Approved', role: 'Approver' },
                        'rejected': { icon: 'bx-x-circle', color: 'danger', label: 'Batch Rejected', role: 'Approver' },
                        'dispatched': { icon: 'bx-truck', color: 'info', label: 'Dispatched', role: 'Dispatcher' },
                        'arrived': { icon: 'bx-map-pin', color: 'warning', label: 'Arrived at Destination', role: 'Driver' },
                        'inspected': { icon: 'bx-search-alt', color: 'success', label: 'Inspection Completed', role: 'Inspector' },
                        'delivered': { icon: 'bx-package', color: 'success', label: 'Delivered & Stored', role: 'Receiver' },
                        'cancelled': { icon: 'bx-block', color: 'secondary', label: 'Cancelled', role: 'Admin' }
                    };
                    
                    data.history.forEach((item, index) => {
                        const isLast = index === data.history.length - 1;
                        const config = actionConfig[item.action] || { icon: 'bx-info-circle', color: 'secondary', label: item.action, role: 'User' };
                        
                        // Parse details if available
                        let detailsHtml = '';
                        if (item.details) {
                            try {
                                const details = typeof item.details === 'string' ? JSON.parse(item.details) : item.details;
                                if (details) {
                                    detailsHtml = '<div class="mt-2 small text-muted">';
                                    if (details.total_weight_mt) detailsHtml += `<div>Weight: ${details.total_weight_mt} MT</div>`;
                                    if (details.total_bags) detailsHtml += `<div>Bags: ${details.total_bags}</div>`;
                                    if (details.grain_type) detailsHtml += `<div>Type: ${details.grain_type}</div>`;
                                    if (details.vehicle_number) detailsHtml += `<div>Vehicle: ${details.vehicle_number}</div>`;
                                    if (details.dispatch_number) detailsHtml += `<div>Dispatch: ${details.dispatch_number}</div>`;
                                    if (details.has_discrepancies) detailsHtml += `<div class="text-warning"><i class="bx bx-error-circle"></i> Discrepancies Found</div>`;
                                    if (details.good_bags) detailsHtml += `<div>✓ Good: ${details.good_bags} | ⚠ Damaged: ${details.damaged_bags || 0} | ✗ Missing: ${details.missing_bags || 0}</div>`;
                                    detailsHtml += '</div>';
                                }
                            } catch (e) {
                                console.error('Error parsing details:', e);
                            }
                        }
                        
                        historyHtml += `
                            <div class="timeline-item ${isLast ? 'timeline-item-last' : ''}">
                                <div class="timeline-marker">
                                    <div class="timeline-marker-icon bg-${config.color}">
                                        <i class="bx ${config.icon}"></i>
                                    </div>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title mb-2">
                                        <span class="badge bg-${config.color} me-2">${config.label}</span>
                                    </h6>
                                    <p class="timeline-text mb-1">
                                        <strong>${config.role}:</strong> ${item.performed_by}
                                    </p>
                                    <small class="text-muted d-block mb-1">
                                        <i class="bx bx-time-five me-1"></i>${new Date(item.performed_at).toLocaleString('en-US', {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}
                                    </small>
                                    ${item.notes ? `<p class="small text-muted mb-1"><i class="bx bx-note me-1"></i>${item.notes}</p>` : ''}
                                    ${detailsHtml}
                                </div>
                            </div>
                        `;
                    });
                    
                    historyHtml += `</div>`;
                } else {
                    historyHtml += `
                        <div class="text-center py-4">
                            <i class="bx bx-info-circle display-4 text-muted"></i>
                            <p class="text-muted mt-2">No history records found for this batch.</p>
                            <small class="text-muted">History tracking will begin with the next action.</small>
                        </div>
                    `;
                }
                
                document.getElementById('batchHistoryContent').innerHTML = historyHtml;
            } else {
                document.getElementById('batchHistoryContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class="bx bx-error-circle display-4 text-danger"></i>
                        <p class="text-danger mt-2">Failed to load batch history.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('batchHistoryContent').innerHTML = `
                <div class="text-center py-4">
                    <i class="bx bx-error-circle display-4 text-danger"></i>
                    <p class="text-danger mt-2">Error loading batch history.</p>
                    <small class="text-muted">Please try again later.</small>
                </div>
            `;
        });
}

// Initialize DataTable with modern styling
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#dispatches-table').DataTable({
            "pageLength": 10,
            "ordering": true,
            "searching": true,
            "responsive": true,
            "language": {
                "search": "Search dispatches:",
                "lengthMenu": "Show _MENU_ dispatches per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ dispatches",
                "emptyTable": "No dispatches awaiting inspection",
                "zeroRecords": "No matching dispatches found"
            },
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Disable sorting on action column
            ]
        });
    }
    
    // Add hover effects to cards
    $('.card').hover(
        function() { $(this).addClass('shadow-sm'); },
        function() { $(this).removeClass('shadow-sm'); }
    );
});

// Add custom CSS for timeline
const timelineCSS = `
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(.timeline-item-last):before {
    content: '';
    position: absolute;
    left: -19px;
    top: 30px;
    height: calc(100% - 10px);
    width: 2px;
    background-color: #e9ecef;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
}

.timeline-marker-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-content {
    background: #f8f9fa;
    padding: 12px 15px;
    border-radius: 8px;
    border-left: 3px solid #696cff;
}

.timeline-title {
    margin-bottom: 8px;
    color: #5a5c69;
    font-size: 0.95rem;
    font-weight: 600;
}

.timeline-text {
    margin-bottom: 8px;
    color: #6c757d;
    font-size: 0.875rem;
}

.empty-state {
    padding: 40px 20px;
}

.card:hover {
    transition: all 0.3s ease;
}

/* Enhanced button styling */
.btn-primary {
    background: linear-gradient(135deg, #696cff 0%, #5a5fcf 100%);
    border: none;
    box-shadow: 0 2px 4px rgba(105, 108, 255, 0.3);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(105, 108, 255, 0.4);
    background: linear-gradient(135deg, #5a5fcf 0%, #4c51bf 100%);
}

/* Table enhancements */
.table-hover tbody tr:hover {
    background-color: rgba(105, 108, 255, 0.05);
    transform: scale(1.001);
    transition: all 0.2s ease;
}

/* Badge enhancements */
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

/* Card header styling */
.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
}

/* Debug: Ensure buttons are visible */
.btn {
    z-index: 1;
    position: relative;
}

/* Debug: Page actions visibility */
.d-flex.gap-2 {
    visibility: visible !important;
    display: flex !important;
}
</style>
`;

// Inject timeline CSS
document.head.insertAdjacentHTML('beforeend', timelineCSS);
</script>
<?= $this->endSection() ?>
